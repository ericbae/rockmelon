{{-- header --}}
<div class="px-6 lg:px-12 max-w-full lg:max-w-7xl mx-auto">
  <div class="lg:flex lg:items-center py-10 lg:justify-between">
    <div class="flex items-center">
      <a href="/">
        <img src="https://img.icons8.com/color/96/000000/bodyguard-male.png" class="inline w-8 mr-3" />
        <div class="text-xl inline-block" style="font-family: Fredoka One;">{{ env('APP_NAME') }}</div>
      </a>
    </div>

    <div class="md:flex md:items-center mt-8 md:mt-0">
      {{-- <a href="#features" class="text-base hover:underline md:ml-12">Features</a> --}}
      <a href="#why" class="text-base hover:underline md:ml-12 ml-4">Why {{ env('APP_NAME') }}?</a>
      <a href="#how" class="text-base hover:underline md:ml-12 ml-4">How does it work?</a>
      <a href="#feedback" class="text-base hover:underline md:ml-12 ml-4">Feedback</a>
      {{-- <a href="#pricing" class="text-base hover:underline md:ml-12 ml-4">Pricing</a> --}}
      <a href="#" class="text-base hover:underline md:ml-12 ml-4 contact-us-link">Contact us</a>
      {{-- <a href="/auth/login" class="text-base hover:underline md:ml-12 ml-4">Login</a> --}}
      <button class="block mt-4 md:mt-0 md:inline text-white text-sm font-semibold bg-gray-700 px-8 py-3 rounded md:ml-12 hover:bg-gray-900 get-started-link">Sign up &amp; get notified!</button>

      <script type="text/javascript">
        $(document).ready(function() {
          $(".get-started-link").on("click", function() {
            $(".email:first").focus()
          })
        })
      </script>
    </div>
  </div>
</div>