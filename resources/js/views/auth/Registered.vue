<template>
  <div class="mx-auto max-w-full lg:w-1/3 px-8 lg:px-0 my-12">

    <div class="flex items-center">
      <img src="https://img.icons8.com/cute-clipart/64/000000/web.png" class="inline w-10 mr-3" />
      <div class="text-base inline-block text-gray-800 font-black">All set! - Please confirm your email</div>
    </div>

    <div class="bg-white p-6 rounded-lg border border-gray-400 mt-6" @keyup.enter="submit">
      <div class="text-base">
        You are now registered and signed in! We have also sent you an email to confirm your email.
        Please note that some actions are not available unless you confirm youre email.
      </div>
    </div>

    <div class="mt-6 ml-4">
      <Footer />
    </div>
  </div>
</template>

<script>
  import Footer from './Footer'

  export default {

    components : {
      Footer,
    },

    mounted() {
      document.body.className = 'bg-gray-200'
    }
  }
</script>