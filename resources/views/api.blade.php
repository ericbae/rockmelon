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
      <a href="/" class="flex items-center">
        <img src="{{ config(strtolower(env('APP_NAME')) . '.logo') }}" class="inline w-10 mr-3" />
        <div class="text-2xl font-black inline-block text-green-700">{{ env('APP_NAME') }}</div>
      </a>

      <div class="md:flex md:items-center mt-8 md:mt-0">
        <a href="#why" class="text-base md:ml-12">Why {{ env('APP_NAME') }}?</a>
        <a href="#features" class="text-base ml-12">Features</a>
        <a href="/api" class="text-base ml-12">API</a>
        <a href="/auth/login" class="text-base ml-12">Login</a>
        <a href="/auth/register" class="block mt-4 md:mt-0 md:inline text-white text-sm font-semibold bg-gray-700 px-8 py-3 rounded md:ml-12 hover:bg-gray-900">Let's Get Started!</a>
      </div>
    </div>
  </div>

  <div class="max-w-5xl mx-auto mt-12">

    <div class="text-3xl font-light">Using {{ env('APP_NAME') }} API</div>

    <div class="text-base mt-8 leading-loose">
      To combine multiple links into one, first create a {{ env('APP_NAME') }} Link.
    </div>

    <div class="mt-4 bg-green-50 p-4 text-base border border-green-600 rounded-lg">
      <div class="font-semibold">POST {{ env('APP_URL') }}/api/link-group/create</div>
      <div class="mt-2">
        Parameters
      </div>

      <div class="mt-2">
        api_key - Available when you create an account
      </div>

      <div class="mt-2">
        name - Name of the link group
      </div>

      <div class="mt-2">
        urls - List of URLs to add to the link group
      </div>

      <div class="mt-2">
        Response
      </div>

      <div class="mt-2">
        <pre>
          { 
            linkGroup : { 
              identifier : 10-character-unique-identifier,
              linkGroupUrl : {{ env('APP_URL') }}/rl/10-character-unique-identifier
              name : name-of-the-link-group,
              urls : [
                url1,
                url2,
                url3
              ]
            }
          }
        </pre>
      </div>
    </div>

    <div class="text-base mt-8 leading-loose">
      You can then take the <span class="emphasis">linkGroupUrl</span> which will take you to a page that can open all the URLs at once.
    </div>

    {{-- <div class="text-base mt-8 leading-loose">
      To add links to the link group
    </div>

    <div class="mt-4 bg-green-50 p-4 text-base border border-green-600 rounded-lg">
      <div class="font-semibold">POST {{ env('APP_URL') }}/api/link/create</div>
      <div class="mt-2">
        Parameters
      </div>

      <div class="mt-2">
        API token - Available when you create an account
      </div>

      <div class="mt-2">
        Identifier - Identifier of the link group you want to add this to
      </div>

      <div class="mt-2">
        URL - URL you want to add
      </div>

      <div class="mt-2">
        Response
      </div>

      <div class="mt-2">
        <pre>
          { 
            linkGroup : { 
              identifier : 10-character-unique-identifier,
              name : name-of-the-link-group,
              urls : [
                url1,
                url2,
                url3
              ]
            }
          }
        </pre>
      </div>
    </div> --}}
  </div>
  

  <br/><br/><br/>

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