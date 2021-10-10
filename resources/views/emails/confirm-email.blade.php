<div style="font-family: 'AvenirNext-Regular',Avenir,'Helvetica Neue',Helvetica,Arial,sans-serif;
            width:640px;
            margin-top:30px;
            padding:0 20px;
            font-size:15px;
            color:#000;">

  @if($emailConfirmation->type == 'user-confirmation')
    Thank you for registering for {{ env('APP_NAME') }}.

  @elseif($emailConfirmation->type == 'comparison-subscriber-confirmation')
    Thank you for subscribing for updates to {{ json_decode($emailConfirmation->data, true)['comparisonName'] }}.

  @endif

  <div style="margin-top:30px;">
    Please confirm your email by clicking on the below link.
  </div>

  <div style='margin-top:30px;'>
    <a style="color:#FF1B31;"
       href="{{ env('APP_URL') }}/auth/confirm-email/{{ encrypt(time() . '-' . $emailConfirmation->id) }}" 
       target="_blank">Confirm my email address</a>
  </div>
  
  <div style="padding:10px;margin-top:50px;border:1px solid #A6A6A6;color:#808080;font-size:13px;">
    You're receiving this email because email address {{ $emailConfirmation->to_email }} was used to sign up for an 
    account at <a style="color:#3E3E3E;" href="{{ config('app.url') }}">{{ config('app.name') }}</a>.
    If this is not you, then please reply to this email indicating that you did not sign up for an account and we'll attend to this immediately.
  </div>
</div>