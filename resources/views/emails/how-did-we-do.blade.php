<div style="font-family: 'AvenirNext-Regular',Avenir,'Helvetica Neue',Helvetica,Arial,sans-serif;
            width:640px;
            margin-top:30px;
            padding:0 20px;
            font-size:14px;
            color:#000;">

  <div>
    Hello {{ $user->username }},
  </div>

  <p>
    My name is Eric and I'm in charge of product development here at 
    {{ env('APP_NAME') }}.
  </p>

  <p>
    I noticed that you have created an account a few days ago, and I just wanted
    to quickly check to see whether there's anything I can do to improve your
    experience on our product?
  </p>

  <p>
    We really want to make sure that {{ env('APP_NAME') }} does what it says it does
    and we want it to do well. So if you could provide any feedback, I would really 
    appreciate it.
  </p>

  <p>
    Thanks again for signing up and look forward to hearing from you.
  </p>
  
  <br/>

  <p>
    Eric Bae
    <br/>
    <a style="color:#FF1B31;" href="{{ env('WEB_URL') }}" target="_blank">@newsyco</a>
  </p>  
</div>