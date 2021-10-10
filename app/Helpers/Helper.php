<?php

namespace App\Helpers;
use Exception, phpQuery, Image, Requests, DB, Log, DOMDocument, MailChecker, Embed\Embed, File;
use App\Models\Email;
use App\Models\LinkGroup;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Helper {

  public static function getGravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
  }

  public static function isEmailDisposable($email) {
    try {
      $data = json_decode(file_get_contents('http://is-email-disposable.ercicbae.workers.dev?email=' . $email), true);
      return $data['valid'] == 0;
    }

    catch(Exception $ex) {
      return false;
    }
  }

  // function to insert a new email
  public static function createNewEmail($args) {
    $email                  = new Email;
    $email->from_email      = empty($args['fromEmail'])      ? config('mail.from.address') : $args['fromEmail'];
    $email->from_name       = empty($args['fromName'])       ? config('mail.from.name')    : $args['fromName'];
    $email->reply_to_email  = empty($args['replyToEmail'])   ? config('mail.from.address') : $args['replyToEmail'];
    $email->reply_to_name   = empty($args['replyToName'])    ? config('mail.from.name')    : $args['replyToName'];
    $email->to_email        = empty($args['toEmail'])        ? config('mail.from.name')    : $args['toEmail'];
    $email->to_name         = empty($args['toName'])         ? config('mail.from.name')    : $args['toName'];
    $email->subject         = $args['subject'];
    $email->data            = empty($args['data'])           ? null : json_encode($args['data']);
    $email->email_type      = $args['emailType'];
    $email->receiver_id     = isset($args['receiverId'])     ? $args['receiverId'] : null;
    $email->sender_id       = isset($args['senderId'])       ? $args['senderId'] : null;
    $email->site_id         = isset($args['siteId'])         ? $args['siteId'] : null;
    $email->parent_email_id = isset($args['parentEmailId']) ? $args['parentEmailId'] : null;
    $email->save();
    return $email;
  }

  public static function markEmailAsSent(Email $email) {
    $email->sent_at = Carbon::now();
    $email->save();
  }

  public static function isEmailValid($email) {
    // if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //   return false;
    // }

    // // make sure that the email is not a disposable email
    // if(self::isEmailDisposable($email)) {
    //   return false;
    // }

    // return true;
    return MailChecker::isValid($email);
  }

  public static function isValidMailbox($email) {
    try {
      list($user, $domain) = explode('@', $email);
      $arr = @dns_get_record($domain, DNS_MX);

      if(count($arr) > 0){
        return true;
      }

      return false;
    }

    catch(Exception $ex) {
      return true;
    }
  }

  public static function hasThereBeenSpam($emailA, $emailB) {
    $q = "SELECT id FROM emails 
          WHERE marked_as_spam_at IS NOT NULL AND 
          ((to_email=? AND reply_to_email=?) OR (to_email=? AND reply_to_email=?))";

    $data = DB::select($q, [$emailA, $emailB, $emailB, $emailA]);
    return count($data) > 0;
  }

  public static function getSiteUrl($siteId) {
    if(env('APP_ENV') == 'local') {
      return env('SITE_URL');
    }

    $site = Site::find($siteId);

    if(!$site->domain) {
      return 'https://' . $site->pyro_subdomain . '.pyro.app';
    }

    return ($site->is_https ? 'https://' : 'http://') . ($site->subdomain ? $site->subdomain . '.' : '') . $site->domain;
  }

  public static function getEnvParams($siteId) {
    if(env('APP_ENV') == 'local') {
      return '?siteId=' . $siteId;
    }

    else {
      return '?';
    }
  }

  // public static function getUrlImage($url) {
  //   $page_content = file_get_contents($url);
  //   $dom_obj = new DOMDocument();
  //   @$dom_obj->loadHTML($page_content);
  //   $meta_val = null;

  //   foreach($dom_obj->getElementsByTagName('meta') as $meta) {
  //     if($meta->getAttribute('property')=='og:image'){ 
  //       return $meta->getAttribute('content');
  //     }
  //   }
  // }

  public static function getYoutubeId($url) {
    $url_string = parse_url($url, PHP_URL_QUERY);
    parse_str($url_string, $args);
    return isset($args['v']) ? $args['v'] : null;
  }

  public static function getContentUrl($site, $content) {
    return self::getSiteUrl($site->id) . '/' . ($site->subpath ? $site->subpath . '/' : '') . 
           (isset($content->comment) ? $content->content_id : $content->id) . '/' . 
           $content->slug . 
           self::getEnvParams($content->site_id);
  }

  // combine default and site translations
  public static function getSiteTranslation($site) {
    $dt = config('translation');
    $st = $site->translation ? json_decode($site->translation, true) : null;

    foreach($dt as $sectionKey => $section) {
      foreach($section as $itemKey => $item) {
        if($st && isset($st[$sectionKey][$itemKey])) {
          $dt[$sectionKey][$itemKey] = $st[$sectionKey][$itemKey];
        }

        // handle variables
        $dt[$sectionKey][$itemKey] = str_replace('[[[site-name]]]', $site->name, $dt[$sectionKey][$itemKey]);
      }
    }

    return $dt;
  }

  public static function getCustomPages($siteId) {
    $s = new \App\Services\CustomPageService;
    return $s->getCustomPages([
      'siteId'    => $siteId,
      'orderBy'   => 'order',
      'orderType' => 'asc'
    ]);
  }

  public static function getCategories($siteId, $getNumContents = 1) {
    $s = new \App\Services\CategoryService;
    return $s->getCategories([
      'siteId'         => $siteId,
      'getNumContents' => $getNumContents,
      'orderBy'        => 'order',
      'orderType'      => 'asc'
    ]);
  }

  public static function getPLaylists($siteId, $getNumContents = 1) {
    $s = new \App\Services\PlaylistService;
    return $s->getPlaylists([
      'siteId'         => $siteId,
      'getNumContents' => $getNumContents,
      'orderBy'        => 'name',
      'orderType'      => 'asc'
    ]);
  }

  public static function getCustomLinks($siteId) {
    $s = new \App\Services\CustomLinkService;
    return $s->getCustomLinks([
      'siteId'    => $siteId,
      'inNav'     => true,
      'orderBy'   => 'order',
      'orderType' => 'asc'
    ]);
  }

  public static function getThemeDefaultSettings(SiteDesignSetting $sds, $onlyIfNull = false) {
    foreach(config('themes')[$sds->theme]['defaults'] as $key => $value) {

      // if($onlyIfNull) {
        if(is_null($sds->{$key})) {
          $sds->{$key} = $value;
        }
      // }

      // else {
        // $sds->{$key} = $value;
      // }

    }

    return $sds;
  }

  public static function getSiteData($site) {
    $site->accessSettings     = SiteAccessSetting::where('site_id', $site->id)->first();
    $site->designSettings     = SiteDesignSetting::where('site_id', $site->id)->first();
    $site->pageSettings       = SitePageSetting::where('site_id', $site->id)->first();
    $site->designSettings     = self::getThemeDefaultSettings($site->designSettings, true);
    $site->newsletter         = SiteNewsletterSetting::where('site_id', $site->id)->first();
    $site->analytics          = SiteAnalyticsSetting::where('site_id', $site->id)->first();
    // $site->twitterSettings = SiteTwitterSetting::where('site_id', $site->id)->first();
    // $site->sale            = SiteSaleSetting::where('site_id', $site->id)->first();
    $site->contentSettings    = SiteContentSetting::where('site_id', $site->id)->first();
    $site->translation        = Helper::getSiteTranslation($site);
    $site->seo                = SiteSeoSetting::where('site_id', $site->id)->first();

    // get size
    $size = DB::select("SELECT SUM(size) AS size FROM video_files WHERE site_id=?", [$site->id]);
    if(count($size) > 0) {
      $site->size = $size[0]->size;
    }

    if($site->seo) {
      if($site->seo->homepage_meta) {
        $site->seo->homepage_meta = json_decode($site->seo->homepage_meta, true);
      }

      if($site->seo->custom_meta) {
        $site->seo->custom_meta = json_decode($site->seo->custom_meta, true);
      }
    }

    if($site->contentSettings && $site->contentSettings->submit_via) {
      $site->contentSettings->submit_via = json_decode($site->contentSettings->submit_via, true);
    }

    return $site;
  }

  public static function isOkToPostToSocialMedia($text) {
    $text = strtolower($text);

    $words = [
      'rape', 'islam', 'muslim', 'racial', 'kkk', 'white supremacy', 'nazi', 'sex', 'fuck', 'orgasm', 'orgy', 'nigger', 'negro',
      'shit', 'tits', 'cunt', 'pussy', 'vagina', 'penis', 'dick', 'trump', 'republican', 'democrat', 'cult'
    ];

    foreach($words as $word) {
      if(strpos($text, $word) !== false) {
        return false;
      }
    }

    return true;
  }

  public static function linkify($value, $protocols = array('http', 'mail'), array $attributes = array()) {
    // Link attributes
    $attr = '';
    foreach ($attributes as $key => $val) {
        $attr .= ' ' . $key . '="' . htmlentities($val) . '"';
    }
    
    $links = array();
    
    // Extract existing links and tags
    $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) { return '<' . array_push($links, $match[1]) . '>'; }, $value);
    
    // Extract text links for each protocol
    foreach ((array)$protocols as $protocol) {
        switch ($protocol) {
            case 'http':
            case 'https':   $value = preg_replace_callback('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { if ($match[1]) $protocol = $match[1]; $link = $match[2] ?: $match[3]; return '<' . array_push($links, "<a $attr href=\"$protocol://$link\">$link</a>") . '>'; }, $value); break;
            case 'mail':    $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"mailto:{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
            case 'twitter': $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"https://twitter.com/" . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1]  . "\">{$match[0]}</a>") . '>'; }, $value); break;
            default:        $value = preg_replace_callback('~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { return '<' . array_push($links, "<a $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
        }
    }
    
    // Insert all link
    return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) { return $links[$match[1] - 1]; }, $value);
  }

  public static function getUrlsFromText($text) {
    $regex = '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i';
    preg_match_all($regex, $text, $matches);
    $urls = $matches[0];
    return $urls;
  }

  public static function getPyroSubDomain($name) {
    $name = Str::slug(trim(strtolower($name)));

    // we want to make sure that the name obeys some rules
    if(strlen($name) < 4 || in_array($name, ['admin', 'blog', 'data', 'help'])) {
      $name = $name . '-' . rand(1000, 9999);
    }

    $num = Site::where('pyro_subdomain', '=', Str::slug($name))->count();
    return Str::slug($name) . ($num == 0 ? '' : $num + 1);
  }

  public static function isSiteAdmin($site, $member) {
    return !is_null($member) && ($site->user_id == $member->user_id || $member->is_admin);
  }

  public static function shorten($text, $length) {
    if(strlen($text) > $length) {
      return substr($text, 0, $length) . '..';
    }

    return $text;
  }

  public static function getVideoViaUrl($url) {
    $data  = [];
    $embed = new Embed;

    //Load any url:
    $info = $embed->get($url);

    // Log::error(print_r($info->image, true));
    
    // if(isset($info->title)) {
      $data['title'] = $info->title;
    // }

    // if(isset($info->description)) {
      $data['description'] = $info->description;
    // }

    // if(isset($info->url)) {
      $data['url'] = $info->url;
    // }

    // if(isset($info->keywords)) {
      $data['keywords'] = $info->keywords;
    // }

    // if(isset($info->image)) {
      $data['image'] = $info->image->getScheme() . '://' . $info->image->getHost() . $info->image->getPath();
    // }

    if(isset($info->code->html)) {
      $data['embedCode'] = $info->code->html;
    }

    if(isset($info->code->width)) {
      $data['embedWidth'] = $info->code->width;
    }

    if(isset($info->code->height)) {
      $data['embedHeight'] = $info->code->height;
    }

    if(isset($info->code->ratio)) {
      $data['embedRatio'] = $info->code->ratio;
    }

    // if(isset($info->authorName)) {
      $data['authorName'] = $info->authorName;
    // }

    // if(isset($info->authorUrl)) {
      $data['authorUrl'] = $info->authorUrl;
    // }

    // if(isset($info->language)) {
      $data['language'] = $info->language;
    // }

    // if(isset($info->languages)) {
      $data['languages'] = $info->languages;
    // }

    // if(isset($info->publishedTime)) {
      $data['publishedTime'] = (new Carbon($info->publishedTime))->toDateTimeString();
    // }
    // dd($data);

    $data['source'] = self::getContentSourceFromUrl($url);
    return count($data) > 0 ? $data : null;
  }

  public static function getContentSourceFromUrl($url) {
    if(strpos($url, 'dailymotion.') !== false) {
      return 'dailymotion';
    }

    else if(strpos($url, 'vimeo.') !== false) {
      return 'vimeo';
    }

    else if(strpos($url, 'youtube.') !== false) {
      return 'youtube';
    }

    else if(strpos($url, 'reddit.') !== false) {
      return 'reddit';
    }

    else if(strpos($url, 'ted.') !== false) {
      return 'ted';
    }

    else if(strpos($url, 'ccc.de') !== false) {
      return 'ccc';
    }
  }

  public static function getImage() {
    $image = 'http://img.youtube.com/vi/xRBZxauRlXQ/hqdefault.jpg';

    $ext   = "jpg";

    if(strpos(strtolower($image), ".png") !== false) {
      $ext = "png";
    } else if(strpos(strtolower($image), ".jpeg") !== false) {
      $ext = "jpeg";
    } else if(strpos(strtolower($image), ".gif") !== false) {
      $ext = "gif";
    }

    try {
      // $img = @file_get_contents();
      $context = stream_context_create([
        'http' => [
          'ignore_errors' => true
        ]
      ]);

      $img = file_get_contents($image, false, $context);
    } catch(Exception $ex) {
      $img = null;
    }
  
    $tmpFileName = storage_path('app') . '/' . time() . '.' . $ext;
  
    if($img) {
      file_put_contents($tmpFileName, $img);
    }

    // only proceed if the file exists
    if(File::exists($tmpFileName)) {

      // // file is now saved, so we can now tweet it
      // $handle = fopen($tmpFileName,'rb');
      // $image  = fread($handle, filesize($tmpFileName));
      // fclose($handle);

      // $params = [
      //   'media[]' => "{$image};type=image/" . $ext . ";filename={$tmpFileName}",
      //   'status'  => $msg
      // ];

      // $res = $t->post('statuses/update_with_media', $params, true);

      // // let's clean up the file
      // File::delete($tmpFileName);
    }
  }

  public static function isValidDomain($domain) {
    // Check for starting and ending hyphen(s)
    if(preg_match('/-./', $domain) || substr($domain, 1) == '-') {
        return false;
    }

    // Detect and convert international UTF-8 domain names to IDNA ASCII form
    if(mb_detect_encoding($domain) != "ASCII") {
        $idn_dom = idn_to_ascii($domain);
    } else {
        $idn_dom = $domain;
    }

    // Validate
    if(filter_var($idn_dom, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) != false) {
        return true;
    }
    return false;
  }

  public static function getDomainsExpiringIn($domains, $toDate, $fromDate = null) {
    $arr = [];
    foreach($domains as $domain) {
      if($domain->status != 'expired' && $domain->registry_expiry_date && $domain->registry_expiry_date < $toDate) {

        if(is_null($fromDate)) {
          $arr[] = $domain;
        }

        else if($domain->registry_expiry_date > $fromDate) {
          $arr[] = $domain; 
        }
      }
    }

    return $arr;
  }

  public static function getTLDs() {
    $arr  = [];
    $tlds = TopLevelDomain::orderBy('tld', 'asc')->get();
    
    foreach($tlds as $tld) {
      $arr[] = $tld->tld;
    }

    return $arr;
  }

  public static function getIdentifier() {
    $identifier = Str::random(10);

    while(LinkGroup::where('identifier', $identifier)->exists()) {
      $identifier = Str::random(10);
    }

    return $identifier;
  }
}