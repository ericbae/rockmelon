<template>
  <div class="">
    <Header />

    <div class="mx-auto max-w-full lg:w-1/4 px-8 lg:px-0 my-8">

      <!-- <div class="text-center">
        <img src="https://img.icons8.com/windows/96/000000/message-in-green-bottle.png" class="inline w-12" />
      </div>

      <div class="mt-8">
        <template v-if="account.user_id == getCurrUser.id">
          It looks like you have already added this Twitter account. If you want to add another account, 
          please make sure you are logged into the account you want to add in Twitter first.
          <a href="/account/connect/a" class="underline">Should we try again?</a>
        </template>

        <template v-else>
          This account has already been added via another user.
        </template>
      </div> -->
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
        account : window.currAccount
      }
    },

    mounted() {

    },
  }
</script>