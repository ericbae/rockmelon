<template>
  <modal 
    name="message-modal" 
    :pivotY="0.2"
    :height="'auto'"
    :width="500"
    :adaptive="true"
    :scrollable="true"
    @before-open="beforeOpen"
    @opened="opened">
    
    <div class="modal-header">
      <div class="title-box">
        <div class="modal-title">
          {{ title }}
        </div>

        <CloseModalIcon modalName="message-modal" />
      </div>
    </div>

    <div class="modal-body">
      <div class="text-base">
        {{ message }}
      </div>

      <div class="mt-8 text-left">
        <SubmitButton label="Got it" :onClick="close" :isFullWidth="true" />
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
        title  : null,
        message: null
      }
    },

    methods : {

      beforeOpen(event) {
        this.title   = event.params.title
        this.message = event.params.message
      },

      opened() {

      },

      close() {
        this.$modal.hide('message-modal')
      },
    },
  }
</script>