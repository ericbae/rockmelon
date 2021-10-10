<div style="font-family: 'AvenirNext-Regular',Avenir,'Helvetica Neue',Helvetica,Arial,sans-serif;
            width:640px;
            margin-top:30px;
            padding:0 20px;
            font-size:15px;
            color:#000;">

  <div>
    Reset your {{ config('app.name') }} password.
  </div>

  <div style="margin-top:30px;">
    Please click on the below link which will take you to a page where you can 
    reset your {{ config('app.name') }} account password.
  </div>

  <div style='margin-top:30px;'>
    <a style="color:#FF1B31;"
       href="{{ env('APP_URL') }}/auth/reset-password/{{ encrypt(time() . '-' . $user->id) }}" 
       target="_blank">RESET MY PASSWORD</a>
  </div>
  
  <div style="padding:10px;margin-top:50px;border:1px solid #A6A6A6;color:#808080;font-size:13px;">
    <div style="">
      You're receiving this email because email address {{ $user->email }} was used to sign up for an 
      account at <a style="color:#3E3E3E;" href="{{ config('app.url') }}">{{ config('app.name') }}</a> and the user of this 
      account requested to reset password because previous one was forgotten.
    </div>

    <div style="margin-top:10px;">
      If this is not you, then please reply to this email indicating that you did not request a password reset
      and we'll attend to this immediately.
    </div>
  </div>

</div>