<?php 

namespace App\Services;
use DB, Carbon\Carbon, Mail;
use App\Models\Subscriber;
use App\Models\Site;
use App\Helpers\Helper;

class SubscriberService {

  public function __construct() {
    $this->emailService = resolve('EmailService');
  }

  public function saveSubscriber($args) {

    $site = Site::find($args['siteId']);

    if(isset($args['subscriber'])) {
      $email = strtolower(trim($args['subscriber']['email']));
    }

    else {
      $email = strtolower(trim($args['email']));  
    }
    
    $sendConfirmEmail = false;

    if(isset($args['subscriber'])) {
      $s = Subscriber::where('site_id', $args['siteId'])->where('email', $email)->where('id', '<>', $args['subscriber']['id'])->first();
    } 

    else if(isset($args['subscriberId'])) {
      $s = Subscriber::where('site_id', $args['siteId'])->where('email', $email)->where('id', '<>', $args['subscriberId'])->first();    
    }

    else {
      $s = Subscriber::where('site_id', $args['siteId'])->where('email', $email)->first();    
    }

    if($s) {
      return [
        'error' => 'Another subscriber with this email already exists.'
      ];
    }

    if(!Helper::isEmailValid($email)) {
      return [
        'error' => 'Please type in a valid email address.'
      ]; 
    }

    if(isset($args['subscriber'])) {
      $s            = Subscriber::find($args['subscriber']['id']);
      $s->email     = $email;
      $s->frequency = $args['subscriber']['frequency'];
      $s->confirmed = $args['subscriber']['confirmed'];
      // $s->categories = $args['allOrSpecific'] != 'all' ? $args['selectedCategories'] : null;
      $s->save();
    }

    else {

      if(isset($args['subscriberId'])) {
        $s = Subscriber::find($args['subscriberId']);
      }

      else {
        $s                     = new Subscriber;  
        $s->site_id            = $args['siteId'];
        $s->confirmed          = isset($args['confirmed']) ? $args['confirmed'] : false;
        $s->newsletter_sent_at = Carbon::now();
        $sendConfirmEmail      = !$s->confirmed;
      }
      
      $s->email     = $email;
      $s->frequency = $args['frequency'];
      $s->timezone  = isset($args['timezone']) ? $args['timezone'] : null;
      // $s->categories = $args['allOrSpecific'] != 'all' ? $args['selectedCategories'] : null;
      $s->save();

      if($sendConfirmEmail) {

        if($this->emailService->alreadyConfirmed($s->email)) {
          $s->confirmed = true;
          $s->save();
        }

        else {
          $this->emailService->confirmEmail(
            'site-subscriber-confirmation', 
            $s->email, 
            null,
            $site->name,
            config('mail.from.address'), 
            Helper::getSiteUrl($site->id) . '/newsletter/confirmed-webhook',
            [
              'subscriberId' => $s->id,
              'siteId'       => $site->id,
              'siteName'     => $site->name
            ]
          );
        }
      }
    }

    return $s;
  }

}