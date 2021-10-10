<template>
  <modal 
    name="feedback-modal" 
    :pivotY="0.2"
    height="auto"
    :width="'560px'"
    :adaptive="true"
    :scrollable="true"
    @before-open="beforeOpen"
    @opened="opened">
    
    <div class="modal-header">
      <div class="modal-title">
        Please send us your feedback
      </div>

      <a href="#"
        v-on:click.prevent="$modal.hide('feedback-modal')"
        class="text-gray-500 hover:text-gray-700">
        <svg width="20" height="20" stroke-width="2" class="align-middle" stroke="currentColor" fill="none">
          <use xlink:href="/img/feather-sprite.svg#x-circle"/>
        </svg>
      </a>
    </div>
    
    <div class="modal-body">

      <label class="label-standard">
        How are you finding {{ getAppName }}? Is there anything we can do to help you more?
        Please let us know if there is anything we can do to improve.
      </label>

      <div class="mt-4">
        <textarea v-model="message" ref="message" class="input-standard h-32"></textarea>
        <div v-if="messageError !== null" class="text-red-600 mt-1 text-sm">{{ messageError }}</div>
      </div>

      <div class="mt-4">
        <SubmitButton v-bind:onClick="submit" v-bind:loading="loading" label="Submit" :isFullWidth="true" :isRounded="true" color="green" />
      </div>

    </div>
  </modal>
</template>

<script>
  import { EventBus }  from '@/shared/event-bus'
  import SubmitButton  from '@/shared/components/form/SubmitButton'

  export default {

    components : {
      SubmitButton
    },

    data() {
      return {
        message     : null,
        messageError: null,
        loading     : false
      }
    },

    methods : {
      
      beforeOpen(event) {
        this.loading      = false
        this.message      = null
        this.messageError = null
      },

      opened(event) {
        this.$refs.message.focus()
      },   

      submit() {
        let valid         = true
        this.messageError = null

        if(this.message == null || this.message.length == 0) {
          this.messageError = "Please type a valid feedback message."
          valid = false;
        }

        if(valid) {
          this.loading = true
          this.apiPost({
            message : this.message,
          }, '/auth/feedback', () => {
            this.message = null
            this.loading = false
            this.$modal.hide('feedback-modal')
            EventBus.$emit('notification-event', {
              'type'   : 'success',
              'message': "Thank you."
            })
          })
        }
      }
    }
  }
</script>