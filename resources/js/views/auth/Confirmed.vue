<template>
  <div class="">
    <Header />

    <div class="mx-auto max-w-full lg:w-1/4 px-8 lg:px-0 my-8">

      <div class="text-center">
        <img src="https://img.icons8.com/ios/100/000000/bird.png" class="inline w-12" />
      </div>

      <div class="mt-8">
        Your email has been successfully verified. 
        <a href="/auth/login" class="underline">You can now login!</a>.
      </div>
    </div>
  </div>
</template>

<script>
  import Header from '@/shared/components/header/Home'

  export default {

    components : {
      Header,
    },

    data() {
      return {

      }
    },

    mounted() {

    },
  }
</script>