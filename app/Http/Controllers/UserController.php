<?php

namespace App\Http\Controllers;
use Cache;
use App\Models\User;
use App\Models\Domain;
use App\Helpers\Helper;

class UserController extends Controller {

  public function getHome() {
		return view('user/home', [
      'message' => session('message'),
    ]);
	}

  public function postAccount() {
    $email = strtolower(trim(request()->email));
    $user  = auth()->user();

    // make sure there is no other email
    $exists = User::where('email', $email)->where('id', '<>', $user->id)->first();
    if($exists) {
      return response()->json([
        'error' => 'Email is already in use'          
      ]);
    }

    $user->email                = request()->email;
    $user->username             = request()->username;
    $user->newsletter_frequency = request()->newsletterFrequency;

    // is there a password change?
    if(request()->filled('password')) {
      $user->password = Hash::make(request()->password);
    }

    $user->save();
    return response()->json($user);
  }

  public function getBilling() {
    return view('user/billing', [
      'subscriptionPlans' => config('subscription-plans')
    ]);
  }

  public function postSettings() {
    $user           = auth()->user();
    $user->settings = json_encode(request()->settings);
    $user->save();
  }

  public function getNumDomainsLeft() {
    $user = request()->attributes->get('user');
    return $this->billingService->getNumDomainsLeft($user);
  }

  public function postGodaddy() {
    if(request()->filled('key') && request()->filled('secret')) {
      $user                     = auth()->user();
      $user->godaddy_api_key    = encrypt(request()->key);
      $user->godaddy_api_secret = encrypt(request()->secret);
      $user->save();
      return response()->json($user);
    }
  }

  public function postNamecheap() {
    if(request()->filled('apiUser') && request()->filled('apiKey')) {
      $user                     = auth()->user();
      $user->namecheap_api_user = encrypt(request()->apiUser);
      $user->namecheap_api_key  = encrypt(request()->apiKey);
      $user->save();
      return response()->json($user);
    }
  }

  public function postGandi() {
    if(request()->filled('apiKey')) {
      $user                = auth()->user();
      $user->gandi_api_key = encrypt(request()->apiKey);
      $user->save();
      return response()->json($user);
    }
  }

  public function postNotification() {
    $user                        = auth()->user();
    $user->newsletter_frequency  = request()->newsletterFrequency;
    $user->notify_when_available = request()->notifyWhenAvailable;
    $user->save();
    return response()->json($user);
  }

  public function getMFA() {
    $user = request()->attributes->get('user');

    // Initialise the 2FA class
    $google2fa = app('pragmarx.google2fa');

    // Add the secret key to the registration data
    $secret = $google2fa->generateSecretKey();

    // Generate the QR image. This is the image the user will scan with their app to set up two factor authentication
    // $google2fa->setQrcodeService(new \PragmaRX\Google2FAQRCode\QRCode\Bacon(new \BaconQrCode\Renderer\Image\SvgImageBackEnd()));
    $QR_Image = $google2fa->getQRCodeInline(
      config('app.name'),
      $user->email,
      $secret
    );

    // Pass the QR barcode image to our view
    return view('user/mfa', [
      'QR_Image' => $QR_Image, 
      'secret'   => $secret
    ]);
  }

  public function postMFA() {
    $user             = request()->attributes->get('user');
    $u                = request()->user;
    $user->enable_mfa = $u['enable_mfa'];
    $user->save();
    return response()->json($user);
  }
}
