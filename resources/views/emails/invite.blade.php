<div style="font-family: 'AvenirNext-Regular',Avenir,'Helvetica Neue',Helvetica,Arial,sans-serif;
            width:640px;
            margin-top:30px;
            padding:0 20px;
            font-size:18px;
            color:#000;">

  <div style="">
    Hello there.
  </div>

  <br/>

  <div style="">
    A user {{ $user->first_name }} {{ $user->last_name }} has invited you to join a group called
    <b>{{ $group->name }}</b> at
    
    <a style="color:#008BF2;text-decoration:underline;"
       href="{{ env('APP_URL') }}" 
       target="_blank">{{ env('APP_NAME') }}</a>.
  </div>

  <br/>

  <div style="">
    What is {{ env('APP_NAME') }}? It's a super fun way to <i>ketch-up</i> (😎 see what we did there?) with your friends and colleagues on a regular basis.
  </div>

  <div style="margin-top:30px;">
    
  </div>

  <div style="margin-top:30px;">
    You create a ketchup group, invite members and send out a <b>ketch-up</b> on a regular interval (e.g. weekly, monthly, once every 3 months).
  </div>

  <div style="margin-top:30px;">
    At each ketchup, members can ask questions, respond to other's messages, leave images and memes to interact.
  </div>

  <div style="margin-top:30px;">
    It's private, invitation-only, ad-free and privacy-focused. It's perfect for long distance families, friends and colleages you want to 
    stay in touch. Please check out 
    <a style="color:#008BF2;text-decoration:underline;" href="{{ env('APP_URL') }}" target="_blank">our homepage</a> for more information
  </div>

  <div style="margin-top:30px;">
    Let's get started by joining {{ $group->name }} below!
  </div>

  <div style='margin-top:30px;'>
    <a style="background-color: #FF1B31;color:#fff;border:1px solid #FF1B31;display:inline-block;padding:20px;text-decoration: none;"
       href="{{ env('APP_URL') }}/auth/register?e={{ $member->invited_email }}" 
       target="_blank"><b>Join {{ $group->name }}</b>!</a>
  </div>
  

  <div style="margin-top:30px;">
    If you have any questions, please send an email to us at 
    <a href="mailto:admin@easyexpense.com.au" style="color: #292a75">{{ config('mail.from.address') }}</a>.
  </div>

  <div style="margin-top:30px;">
    {{ env('APP_NAME') }} team.
  </div>

  <div style="padding:10px;margin-top:50px;border:1px solid #A6A6A6;color:#808080;font-size:13px;">
    <div style="">
      If the email {{ $member->invited_email }} does not belong to you, please reply to this email and we will attend to this immediately.
    </div>
  </div>

</div>
</div>