<template>
  <div class="">
    <Header />

    <div class="mx-auto max-w-full lg:w-1/3 px-8 lg:px-0 my-8">

      <div class="text-center">
        <img src="https://cdn2.iconfinder.com/data/icons/icontober/64/Inkcontober_Screech_Psyduck-512.png" class="inline w-20" />
      </div>

      <div class="mt-8 text-base text-gray-800">
        Oh dear. It looks like you have not confirmed your registration, which
        means certain features like 
        <span class="text-orange-700">submitting your project</span>,
        <span class="text-orange-700">contacting project makers</span> and 
        <span class="text-orange-700">commenting</span> etc are not available.

        <div class="mt-6">
          Please click on the link in the email that we sent you
          to get started right away!

          If you have any problems or didn't receive the email, please 
          <a href="mailto:sideprojectors.com" class="text-blue-600 underline">let us know</a> and we will look into this immediately.
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Header       from '@/shared/components/header/Home'

  export default {

    components : {
      Header,
    },

    mounted() {
      document.body.className = 'bg-gray-200'
    },
  }
</script>