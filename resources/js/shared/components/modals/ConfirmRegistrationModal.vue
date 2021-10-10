<template>
  <modal 
    name="confirm-registration-modal" 
    :pivotY="0.2"
    :height="'auto'"
    :width="550" 
    :adaptive="true"
    :scrollable="true">
    
    <div class="modal-header">
      <div class="title-box">
        <div class="modal-title">
          Please confirm your registration.
        </div>

        <CloseModalIcon modalName="confirm-registration-modal" />
      </div>
    </div>
    
    <div class="modal-body">

      <div class="text-base">
        Hello &amp; welcome my friend!
      </div>

      <div class="mt-3 text-base">
        We are very excited to have you here at {{ getAppName }}!
      </div>

      <div class="mt-3 text-base">
        Before we move forward, please confirm your email address by clicking on the link we sent.
        Please also make sure that you check the <span class="text-red-700 font-semibold">SPAM folder</span> so that you don't miss this email.
      </div>

      <div class="mt-3 text-base">
        Please note that without validating your email, <span class="underline">there are some features that cannot be accessed</span>.
      </div>

      <div class="mt-3 text-base">
        If you have any problems or didn't receive the email, please 
        <a href="mailto:hello@pyro.app" class="text-blue-600 underline font-semibold">let us know</a> and we will look into this immediately.
      </div>

      <div class="mt-8">
        <SubmitButton label="Got it!" :onClick="submit" :loading="loading" :isFullWidth="true" />
      </div>

    </div>
  </modal>
</template>

<script>
  import SubmitButton   from '@/shared/components/form/SubmitButton'
  import CloseModalIcon from '@/shared/components/modals/CloseModalIcon'

  export default {

    components : {
      CloseModalIcon,
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
      submit() {
        if(window.location.href.indexOf('confirm=') > -1) {
          this.loading = true
          window.location.href = "/"
        }

        else {
          this.$modal.hide('confirm-registration-modal')
        }
      }
    }
  }
</script>