@extends('shared/layout')

@section('meta')
  <title>{{ env('APP_NAME') }} - Collect multiple links and open them in one click!</title>
  <meta name="keywords" content="" />
  <meta property="og:title" content="{{ env('APP_NAME') }}" />
  <meta property="og:locale" content="en_US" />
  <meta name="description" content="{{ env('APP_NAME') }}" />
  <meta property="og:description" content="{{ env('APP_NAME') }}" />
@endsection

@section('main')
  
  <div class="px-6 lg:px-12 max-w-full lg:max-w-7xl mx-auto">
    
    {{-- header --}}
    <div class="lg:flex lg:items-center py-10 lg:justify-between">
      <div class="flex items-center">
        <img src="{{ config(strtolower(env('APP_NAME')) . '.logo') }}" class="inline w-10 mr-3" />
        <div class="text-2xl font-black inline-block text-green-700">{{ env('APP_NAME') }}</div>
      </div>

      <div class="md:flex md:items-center mt-8 md:mt-0">
        <a href="#why" class="text-base md:ml-12">Why {{ env('APP_NAME') }}?</a>
        <a href="#features" class="text-base ml-12">Features</a>
        <a href="/api" class="text-base ml-12">API</a>
        <a href="/auth/login" class="text-base ml-12">Login</a>
        <a href="/auth/register" class="block mt-4 md:mt-0 md:inline text-white text-sm font-semibold bg-gray-700 px-8 py-3 rounded md:ml-12 hover:bg-gray-900">Let's Get Started!</a>
      </div>
    </div>


    {{-- hero --}}
    <div class="mt-12 font-black text-3xl lg:text-5xl text-center">
      <span class="gradient-text">One Link to Rule Them All</span>
    </div>

    <div class="mt-8 font-semibold text-4xl text-center">
      {{ env('APP_NAME') }} combines many URLs into a single link.
    </div>

    <div class="mt-4 font-semibold text-4xl text-center">
      Share it &amp; click on the link to open all URLs at once 😎
    </div>

    <div class="text-center mt-12">
      <a href="/try" class="inline-block bg-green-700 hover:bg-green-800 text-white text-lg font-semibold rounded-lg shadow-lg px-12 py-6 text-center" style="width: 400px;">
        Try it! No registration required.
      </a>
    </div>

    <div class="mt-12 flex justify-center">
      <div class="w-1/2 border-2 border-yellow-500 p-4 rounded-lg">
        Hello there! {{ env('APP_NAME') }} has been developed for <a href="https://larajam.dev/" class="link" target="_blank">Larajam</a> event.
        You might find not everything is working exactly the way we hope. We aim to complete all the features once the event is over and have some more time!
      </div>
    </div>
  </div>

  {{-- use cases --}}
  <div class="mt-24 py-16 bg-green-50" id="why">
    <div class="max-w-full lg:max-w-6xl mx-auto px-8 lg:px-0">

      <div class="font-black text-3xl lg:text-4xl text-center text-green-700">
        Why {{ env('APP_NAME') }}?
      </div>

      <div class="flex flex-wrap mt-6 -mx-8">
        <div class="w-full lg:w-1/3 p-8">
          <div class="p-5 rounded-lg border-2 border-green-500 bg-white hover:border-gray-400">
            <img src="https://img.icons8.com/color/96/000000/time-machine--v1.png" class="w-12">
            <div class="mt-4 text-lg font-bold">Save your precious time</div>
            <div class="mt-4 text-base leading-relaxed text-gray-800">
              Did you know on average you click on at least 100 links per day? Isn't it about time we save those precious finger clicks and time wasted on 
              sites to load?
            </div>
          </div>
        </div>

        <div class="w-full lg:w-1/3 p-8">
          <div class="p-5 rounded-lg border-2 border-green-500 bg-white hover:border-gray-400">
            <img src="https://img.icons8.com/color/96/000000/fast-track.png" class="w-12">
            <div class="mt-4 text-lg font-bold">Quicker browsing</div>
            <div class="mt-4 text-base leading-relaxed text-gray-800">
              Stop clicking on all those links on the sites like ProductHunt or newsletter emails. {{ env('APP_NAME') }} lets you open them all at once
              in one single click.
            </div>
          </div>
        </div>

        <div class="w-full lg:w-1/3 p-8">
          <div class="p-5 rounded-lg border-2 border-green-500 bg-white hover:border-gray-400">
            <img src="https://img.icons8.com/color/96/000000/welfare.png" class="w-12">
            <div class="mt-4 text-lg font-bold">Better support for your customers</div>
            <div class="mt-4 text-base leading-relaxed text-gray-800">
              If you run a website or newsletter, use {{ env('APP_NAME') }} to boost your user experience. Nobody enjoys clicking on 
              all those links.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  {{-- features --}}
  <div class="py-16 bg-white" id="features">
    <div class="max-w-full lg:max-w-6xl mx-auto px-8 lg:px-0">

      <div class="font-black text-3xl lg:text-4xl text-center text-green-700">
        {{ env('APP_NAME') }} Features.
      </div>

      <div class="flex flex-wrap mt-6 -mx-8">
        <div class="w-full lg:w-1/3 p-8">
          <div class="p-5 rounded-lg border-2 border-green-500 bg-white hover:border-gray-400">
            <img src="https://img.icons8.com/color/96/000000/easy.png" class="w-12">
            <div class="mt-4 text-lg font-bold">Link clicking management</div>
            <div class="mt-4 text-base leading-relaxed text-gray-800">
              Bundle 10 or 300 links. {{ env('APP_NAME') }} handles all. Open all or 10, 20 links at a time.
            </div>
          </div>
        </div>

        <div class="w-full lg:w-1/3 p-8">
          <div class="p-5 rounded-lg border-2 border-green-500 bg-white hover:border-gray-400">
            <img src="https://img.icons8.com/color/96/000000/link--v1.png" class="w-12">
            <div class="mt-4 text-lg font-bold">Easy link bundling with API</div>
            <div class="mt-4 text-base leading-relaxed text-gray-800">
              You can use {{ env('APP_NAME') }} on the web or you can use our API to combine links
              and manage them.
            </div>
          </div>
        </div>

        <div class="w-full lg:w-1/3 p-8">
          <div class="p-5 rounded-lg border-2 border-green-500 bg-white hover:border-gray-400">
            <img src="https://img.icons8.com/color/96/000000/code-folder.png" class="w-12">
            <div class="mt-4 text-lg font-bold">Collect link bundles into one</div>
            <div class="mt-4 text-base leading-relaxed text-gray-800">
              Collect link bundles into one page - great for ongoing newsletters and websites with
              frequent updates.
            </div>
          </div>
        </div>

        <div class="w-full lg:w-1/3 p-8">
          <div class="p-5 rounded-lg border-2 border-green-500 bg-white hover:border-gray-400">
            <img src="https://img.icons8.com/color/96/000000/check-all--v1.png" class="w-12">
            <div class="mt-4 text-lg font-bold">Frequent link health check</div>
            <div class="mt-4 text-base leading-relaxed text-gray-800">
              {{ env('APP_NAME') }} frequently checks links to ensure they are still valid 
              &amp; let you know if they become broken.
            </div>
          </div>
        </div>

        <div class="w-full lg:w-1/3 p-8">
          <div class="p-5 rounded-lg border-2 border-green-500 bg-white hover:border-gray-400">
            <img src="https://img.icons8.com/color/96/000000/lock--v1.png" class="w-12">
            <div class="mt-4 text-lg font-bold">Private links (coming soon!)</div>
            <div class="mt-4 text-base leading-relaxed text-gray-800">
              Password protect links you create so that only selected users can access your link bundles.
            </div>
          </div>
        </div>

        <div class="w-full lg:w-1/3 p-8">
          <div class="p-5 rounded-lg border-2 border-green-500 bg-white hover:border-gray-400">
            <img src="https://img.icons8.com/color/96/000000/line-chart--v1.png" class="w-12">
            <div class="mt-4 text-lg font-bold">Analytics (coming soon!)</div>
            <div class="mt-4 text-base leading-relaxed text-gray-800">
              Find out who clicked on your link bundle and its usage to better understand your users.
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>



  {{-- Pricing --}}
{{--   <div class="py-16 bg-gray-100" id="pricing">
    <div class="max-w-full lg:max-w-6xl mx-auto px-8 lg:px-0">

      <div class="font-black text-3xl lg:text-4xl text-center">
        {{ env('APP_NAME') }} Pricing
      </div>

      <div class="mt-4 text-xl text-gray-700 font-light text-center leading-relaxed">
        We are completely free (for now)
      </div>

      <div class="mt-8 flex flex-wrap -mx-3">
        <div class="w-full lg:w-1/4 p-3">
          <div class="bg-white shadow-lg rounded-lg p-4 border-4 border-gray-300 hover:border-blue-300 relative text-base">
            <div class="text-center">
              <img src="https://img.icons8.com/office/80/000000/seahorse.png" class="w-8 inline">
            </div>

            <div class="text-center mt-4 text-lg text-gray-600">Free Plan - $0 per month</div>

            <div class="mt-2 text-center text-gray-400 text-sm">
              Includes
            </div>

            <div class="mt-2 flex justify-center">
              <div class="bg-gray-100 rounded-lg px-4 py-2 inline-block text-base font-semibold text-gray-600">
                20 Domains
              </div>
            </div>
          </div>
        </div>

        <div class="w-full lg:w-1/4 p-3">
          <div class="bg-white shadow-lg rounded-lg p-4 border-4 border-gray-300 hover:border-blue-300 relative text-base">
            <div class="text-center">
              <img src="https://img.icons8.com/office/80/000000/parrot.png" class="w-8 inline">
            </div>

            <div class="text-center mt-4 text-lg text-gray-600">$3 per month</div>

            <div class="mt-2 text-center text-gray-400 text-sm">
              Includes
            </div>

            <div class="mt-2 flex justify-center">
              <div class="bg-gray-100 rounded-lg px-4 py-2 inline-block text-base font-semibold text-gray-600">
                100 Domains
              </div>
            </div>
          </div>
        </div>

        <div class="w-full lg:w-1/4 p-3">
          <div class="bg-white shadow-lg rounded-lg p-4 border-4 border-gray-300 hover:border-blue-300 relative text-base">
            <div class="text-center">
              <img src="https://img.icons8.com/office/80/000000/teddy-bear.png" class="w-8 inline">
            </div>

            <div class="text-center mt-4 text-lg text-gray-600">$5 per month</div>

            <div class="mt-2 text-center text-gray-400 text-sm">
              Includes
            </div>

            <div class="mt-2 flex justify-center">
              <div class="bg-gray-100 rounded-lg px-4 py-2 inline-block text-base font-semibold text-gray-600">
                500 Domains
              </div>
            </div>
          </div>
        </div>

        <div class="w-full lg:w-1/4 p-3">
          <div class="bg-white shadow-lg rounded-lg p-4 border-4 border-gray-300 hover:border-blue-300 relative text-base">
            <div class="text-center">
              <img src="https://img.icons8.com/office/80/000000/kawaii-pizza.png" class="w-8 inline">
            </div>

            <div class="text-center mt-4 text-lg text-gray-600">$10 per month</div>

            <div class="mt-2 text-center text-gray-400 text-sm">
              Includes
            </div>

            <div class="mt-2 flex justify-center">
              <div class="bg-gray-100 rounded-lg px-4 py-2 inline-block text-base font-semibold text-gray-600">
                2,000 Domains
              </div>
            </div>
          </div>
        </div>

      </div>
    </div> --}}

    <div class="text-center mt-12">
      <a href="/auth/register" class="inline-block bg-green-600 hover:bg-green-700 text-white text-lg font-semibold rounded-lg shadow-lg px-12 py-6 text-center" style="width: 400px;">
        Sounds good? Let's Get Started!
      </a>
    </div>

    <br/><br/><br/>
  </div>

  {{-- Footer --}}
  @include('shared/footer')
  
<style>
  .gradient-text {
    background: linear-gradient(to right, #00AB45 0%, #0F33F4 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;

  }
</style>
@endsection