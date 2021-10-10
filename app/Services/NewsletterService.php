<?php 

namespace App\Services;
use DB, Auth, Config, Mail;
use App\Models\Subscriber;
use App\Models\Member;
use App\Models\Site;
use App\Models\User;
use App\Models\Domain;
use App\Helpers\Helper;
use App\Models\SiteNewsletterSetting;
use App\Mail\Newsletter;

class NewsletterService {

  public function sendNewsletters() {
    $frequencies = [
      'daily'       => now()->subDays(1),
      'weekly'      => now()->subDays(7), 
      'fortnightly' => now()->subDays(14), 
      'monthly'     => now()->subDays(30)
    ];

    // loop through and then get the users for each frequency
    foreach($frequencies as $key => $f) {

      $users = $this->getUsersForNewsletter($key, $f);
      
      // now we have users to whom we need to send the newsletters to
      foreach($users as $user) {
        User::where('id', $user->id)->update(['newsletter_sent_at' => now()]);
        $this->sendNewsletterToUser($user);
      }
    }
  }

  public function sendNewsletterToUser($user) {
    // domain data
    $domains = Domain::where('user_id', $user->id)->orderBy('registry_expiry_date', 'asc')->get();

    if(Helper::isEmailValid($user->email)) {
      $total     = count($domains);
      $available = [];
      $expired   = [];
      
      foreach($domains as $domain) {
        if($domain->status == 'available') {
          $available[] = $domain;
        }

        else if($domain->status == 'expired') {
          $expired[] = $domain;
        }
      }

      $expire7  = Helper::getDomainsExpiringIn($domains, now()->addDays(7), now()->addDays(1));
      $expire30 = Helper::getDomainsExpiringIn($domains, now()->addDays(30), now()->addDays(7));

      // work out the rest
      $allAbove = array_merge($available, $expired, $expire7, $expire30);
      $others   = [];

      foreach($domains as $a) {
        $exists = false;
        
        foreach($allAbove as $b) {
          if($b->domain == $a->domain) {
            $exists = true;
          }
        }

        if(!$exists) {
          $others[] = $a;
        }
      }

      Mail::to($user->email)->send(new Newsletter($user, count($domains), $available, $expired, $expire7, $expire30, $others));
    }
  }

  public function getUsersForNewsletter($frequency, $lastSentAt) {
    $q = "SELECT u.* 
          FROM users u
          WHERE u.created_at < ? AND u.deleted_at IS NULL AND u.confirmed_at IS NOT NULL AND 
          u.newsletter_frequency=? AND (u.newsletter_sent_at IS NULL OR u.newsletter_sent_at < ?)
          ORDER BY RANDOM() LIMIT 10";

    return DB::select($q, [
      $lastSentAt,
      $frequency,
      $lastSentAt
    ]);
  }



  public function getPopularContentsForSiteNewsletter($person, $fromTime) {
    $sns = SiteNewsletterSetting::where('site_id', $person->site_id)->first();

    $q = "SELECT c.* FROM contents c WHERE c.site_id=? AND c.created_at > ? ";

    if(isset($person->categories) && !is_null($person->categories)) {
      $q .= " AND (c.category_id IN (" . $person->categories . ") OR c.category_id IN (SELECT id FROM categories WHERE parent_category_id IN (" . $person->categories . ")))";
    }

    $q .= " ORDER BY c.num_views DESC NULLS LAST LIMIT ?";
    return DB::select($q, [$person->site_id, $fromTime, $sns->content_limit]);
  }

  public function getLatestContentsForSiteNewsletter($siteId, $fromTime, $contentsToExclude) {
    $exclude = [];
    foreach($contentsToExclude as $p) {
      $exclude[] = $p->id;
    }

    // get the latest contents, but make sure that they are not in the popular contents
    $q = "SELECT c.* FROM contents c " . 
         "WHERE c.approved IS TRUE AND c.site_id=? AND c.created_at > ? AND ";

    if(count($exclude) > 0) {
      $q .= " c.id NOT IN (" . implode(",", $exclude) . ") ";
    } else {
      $q .= " 1=1 ";
    }

    $q .= "ORDER BY c.created_at DESC NULLS LAST LIMIT 15 ";
    return DB::select($q, [$siteId, $fromTime]);
  }

  































  public function sendAdminNewsletters() {
    $users = [];

    // need to loop through different frequencies
    $frequencies = [
      'daily' => [
        'curr' => Carbon::now()->subHours(24),
        'prev' => Carbon::now()->subHours(48)
      ],

      'weekly' => [
        'curr' => Carbon::now()->subDays(7),
        'prev' => Carbon::now()->subDays(14),
      ],

      'fortnightly' => [
        'curr' => Carbon::now()->subDays(14), 
        'prev' => Carbon::now()->subDays(28)
      ],

      'monthly' => [
        'curr' => Carbon::now()->subDays(30),
        'prev' => Carbon::now()->subDays(60)
      ]
    ];

    foreach($frequencies as $key => $frequency) {

      // grab all the users who have a site that is working
      $q = "SELECT u.* FROM users u
            JOIN
            (
              SELECT MAX(created_at) AS created_at, user_id FROM sites WHERE deleted_at IS NULL GROUP BY user_id
            ) s ON s.user_id=u.id
            WHERE u.newsletter_frequency=? AND u.created_at < ? AND 
            (u.newsletter_sent_at IS NULL OR u.newsletter_sent_at < ?) AND 
            u.deleted_at IS NULL AND s.created_at < ?
            ORDER BY u.newsletter_sent_at ASC NULLS FIRST LIMIT 10";

      $data = DB::select($q, [
        $key,
        $frequency['curr'],
        $frequency['curr'],
        $frequency['curr']
      ]);      

      foreach($data as $d) {
        User::where('id', $d->id)->update(['newsletter_sent_at' => Carbon::now()]);
        $data = $this->_getDataForAdminNewsletter($frequency['curr'], $frequency['prev'], $d->id);
        Mail::to($d->email)->send(new AdminNewsletterMail(User::find($d->id), $data));
      }
    }
  }

  public function _getDataForAdminNewsletter($currPeriod, $prevPeriod, $userId) {

    $sites = Site::where('user_id', $userId)->get();

    // for each site, grab its data
    foreach($sites as $site) {

      // what was total pageviews since last time it was sent?
      $q          = "SELECT COUNT(a.id) AS value FROM activity_log a WHERE a.site_id=? AND a.created_at > ? ";
      $pvtw       = DB::select($q, [$site->id, $currPeriod]);
      $site->pvtw = $pvtw[0]->value;

      $q          = "SELECT COUNT(a.id) AS value FROM activity_log a WHERE a.site_id=? AND a.created_at < ? AND a.created_at > ? ";
      $pvpw       = DB::select($q, [$site->id, $currPeriod, $prevPeriod]);
      $site->pvpw = $pvpw[0]->value;


      // what was total unique visitors since last time it was sent?
      $q          = "SELECT COUNT(DISTINCT a.ip) AS value FROM activity_log a WHERE a.site_id=? AND a.created_at > ? ";
      $uvtw       = DB::select($q, [$site->id, $currPeriod]);
      $site->uvtw = $uvtw[0]->value;

      $q          = "SELECT COUNT(DISTINCT a.ip) AS value FROM activity_log a WHERE a.site_id=? AND a.created_at < ? AND a.created_at > ? ";
      $uvpw       = DB::select($q, [$site->id, $currPeriod, $prevPeriod]);
      $site->uvpw = $uvpw[0]->value;

      // what is the total number of members
      $q         = "SELECT COUNT(m.id) AS value FROM members m WHERE m.site_id=? AND m.user_id != ? ";
      $nmt       = DB::select($q, [$site->id, $userId]);
      $site->nmt = $nmt[0]->value;

      // what was total number of members
      $q          = "SELECT COUNT(m.id) AS value FROM members m WHERE m.site_id=? AND m.created_at > ? AND m.user_id != ? ";
      $nmtw       = DB::select($q, [$site->id, $currPeriod, $userId]);
      $site->nmtw = $nmtw[0]->value;

      $q          = "SELECT COUNT(m.id) AS value FROM members m WHERE m.site_id=? AND m.created_at < ? AND m.created_at > ? AND m.user_id != ?";
      $nmpw       = DB::select($q, [$site->id, $currPeriod, $prevPeriod, $userId]);
      $site->nmpw = $nmpw[0]->value;


      // what is the total number of subscribers
      $q         = "SELECT COUNT(s.id) AS value FROM subscribers s WHERE s.deleted_at IS NULL AND s.site_id=?";
      $nst       = DB::select($q, [$site->id]);
      $site->nst = $nst[0]->value;

      // what was total number of subscribers
      $q          = "SELECT COUNT(s.id) AS value FROM subscribers s WHERE s.deleted_at IS NULL AND s.site_id=? AND s.created_at > ? ";
      $nstw       = DB::select($q, [$site->id, $currPeriod]);
      $site->nstw = $nstw[0]->value;

      $q          = "SELECT COUNT(s.id) AS value FROM subscribers s WHERE s.deleted_at IS NULL AND s.site_id=? AND s.created_at < ? AND s.created_at > ?";
      $nspw       = DB::select($q, [$site->id, $currPeriod, $prevPeriod]);
      $site->nspw = $nspw[0]->value;
    }

    return $sites;
  }

}