<template>
  <modal 
    name="confirmed-modal" 
    :pivotY="0.2"
    :height="'auto'">
    
    <div class="modal-header">
      <div class="modal-title">
        Hooray 🎉🍹🕺 !
      </div>

      <a href="#"
        v-on:click.prevent="$modal.hide('confirmed-modal')"
        class="text-grey hover:text-grey-darker">
        <svg width="20" height="20" stroke-width="2" class="align-middle" stroke="currentColor" fill="none">
          <use xlink:href="/img/feather-sprite.svg#x-circle"/>
        </svg>
      </a>
    </div>
    
    <div class="p-4">

      <div class="text-base leading-normal">
        Your registration has been verified and you are now all good to go!
      </div>

      <div class="mt-2 text-base leading-normal">
        Why don't we start by posting one of <a href="#" class="text-orange-700 underline" v-on:click.prevent="postProject">your projects</a>?
      </div>

      <div class="mt-8 text-left">
        <SubmitButton label="Close" v-bind:onClick="submit" v-bind:loading="loading" />
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
      submit() {
        this.loading = true
        window.location.href = "/"
      },

      postProject() {
        this.$modal.hide('confirmed-modal')
        this.$modal.show('new-project-modal')
      }
    }
  }
</script>