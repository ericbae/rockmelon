{{-- Footer --}}
<div class="bg-gray-900 px-12 lg:px-0">
  <div class="w-full lg:max-w-6xl mx-auto py-20 flex flex-wrap">
    <div class="w-full lg:w-1/4">
      
      <img src="{{ config(strtolower(env('APP_NAME')) . '.logo') }}" class="inline w-10 mr-3" />

      <div class="mt-2 text-sm text-gray-200">
        &copy; {{ date("Y") }}
        <span class="mx-2">/</span>
        <a href="{{ env('APP_URL') }}" class="text-gray-200">{{ env('APP_NAME') }}</a>
      </div>

      <div class="mt-4 text-sm text-white">
        Made with <span class="mx-2">❤️</span> from Sydney, Australia
      </div>
    </div>

    <div class="w-full lg:w-1/4 lg:text-right mt-12 lg:mt-0">
      {{-- <div class="text-white font-semibold border-b border-white pb-1 inline-block">
        User Stuff
      </div>

      <div class="mt-4">
        <a href="{{ env('ADMIN_URL') }}/auth/login" class="mt-1 hover:underline text-sm block text-white">Log in</a>
        <a href="{{ env('ADMIN_URL') }}/auth/register{{ request()->query('r') ? '?r=' . request()->query('r') : '' }}" 
          target="_blank" class="mt-1 hover:underline text-sm block text-white">Sign up</a>
      </div>       --}}
    </div>

    {{-- <div class="w-full lg:w-1/4 lg:text-right mt-12 lg:mt-0">
      <div class="text-white font-semibold border-b border-white pb-1 inline-block">
        {{ env('APP_NAME') }} Stuff
      </div>

      <div class="mt-4">
        <a href="#" class="mt-1 hover:underline text-sm block text-white contact-us-link">Contact us</a>
        <a href="https://twitter.com/get_pyro" target="_blank" class="mt-1 hover:underline text-sm block text-white">Follow us on Twitter</a>
      </div>      
    </div>

    <div class="w-full lg:w-1/4 lg:text-right mt-12 lg:mt-0">
      <div class="text-white font-semibold border-b border-white pb-1 inline-block">
        Important Stuff
      </div>

      <div class="mt-4">
        <a href="{{ env('WEB_URL') }}#features" class="mt-1 hover:underline text-sm block text-white">Features</a>
        <a href="{{ env('WEB_URL') }}/privacy-policy" class="mt-1 hover:underline text-sm block text-white">Privacy Policy</a>
        <a href="{{ env('WEB_URL') }}/terms-of-service" class="mt-1 hover:underline text-sm block text-white">Terms of Service</a>
      </div>
    </div> --}}
  </div>
</div>

<div id="contact-us-app"><contact-us /></div>
<script src="{{ mix('js/shared/embeddable-components/contactus/app.js') }}" type="text/javascript"></script>