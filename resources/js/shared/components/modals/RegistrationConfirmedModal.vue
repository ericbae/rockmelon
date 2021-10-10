<template>
  <modal 
    name="registration-confirmed-modal" 
    :pivotY="0.2"
    :height="'auto'"
    :width="'480px'"
    :adaptive="true"
    :scrollable="true"
    @opened="opened">
    
    <div class="modal-header">
      <div class="modal-title">
        Welcome to {{ getAppName }}!
      </div>

      <a href="#"
        v-on:click.prevent="$modal.hide('registration-confirmed-modal')"
        class="text-grey hover:text-grey-darker">
        <svg width="20" height="20" stroke-width="2" class="align-middle" stroke="currentColor" fill="none">
          <use xlink:href="/img/feather-sprite.svg#x"/>
        </svg>
      </a>
    </div>
    
    <div class="modal-body">

      <div class="text-base leading-normal">
        Hello there friend!
      </div>

      <div class="mt-3 text-base leading-normal">
        Your account has now been confirmed and it's all good to go.
        We are very excited to have you and can't wait to see what your domains can do! 
      </div>

      <!-- <div class="mt-2 text-sm leading-normal">
        Before we move forward, please confirm your account by clicking on the link we sent to your email address
        <span class="font-semibold">{{ $store.state.user.email }}</span>. 

        Please also make sure that you check the <span class="text-orange font-semibold">SPAM folder</span> so that you don't miss this email.
      </div> -->

      <div class="mt-2 text-base leading-normal">
        If you have any problems or didn't receive the email, please let us know via 
        <a href="mailto:hello@newsy.co" class="text-blue-500 underline">email</a> 
        or you can use the feedback link
        and we will look into this immediately.
      </div>

      <div class="mt-8 text-left">
        <SubmitButton label="Got it!" v-bind:onClick="submit" v-bind:loading="loading" />
      </div>

    </div>
  </modal>
</template>

<script>
  import SubmitButton from '@/shared/components/form/SubmitButton'

  export default {

    components : {
      SubmitButton
    },

    data() {
      return {
        domain    : null,
        createWhat: null,
        loading   : false
      }
    },

    methods : {
      opened() {
        if(this.$router.name == 'home') {
          this.$router.push({ 
            name  : 'home',
            params: {
              welcome : null
            }
          })
        }
      },

      submit() {
        this.$modal.hide('registration-confirmed-modal')
      }
    }
  }
</script>