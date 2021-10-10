<template>
  <modal 
    name="confirm-modal" 
    :pivotY="0.2"
    :height="'auto'"
    :width="width"
    :adaptive="true"
    :scrollable="true"
    @opened="opened"
    @before-open="beforeOpen">

    <div class="modal-header">
      <div class="title-box">
        <div class="modal-title">{{ title }}</div>

        <a href="#"
          v-on:click.prevent="$modal.hide('confirm-modal')"
          class="text-gray-600 hover:text-gray-800">
          <svg width="20" height="20" stroke-width="2" class="align-middle" stroke="currentColor" fill="none">
            <use xlink:href="/img/feather-sprite.svg#x-circle"/>
          </svg>
        </a>
      </div>
    </div>
    
    <div class="modal-body">
      <label class="text-base">
        {{ message }}
      </label>

      <div class="mt-6 flex items-center space-x-2">
        <template v-for="(button, index) in buttons">
          <SubmitButton 
            class="w-1/2"
            :label="button.label" 
            :onClick="button.action" 
            :color="button.color"
            :loading="loading"
            :loadingViaLocal="button.loadingViaLocal" />
        </template>
      </div>
    </div>
  </modal>
</template>

<script>
  import { EventBus } from '@/shared/event-bus'
  import SubmitButton from '@/shared/components/form/SubmitButton'

  export default {

    components : {
      SubmitButton
    },

    data() {
      return {
        width  : 450,
        title  : 'Please confirm',
        message: null,
        buttons: null,
        loading: false
      }
    },

    methods : {

      beforeOpen(event) {
        // this.width = event.params != null && event.params.width != null ? event.params.width : null
        this.loading = event.params != null && event.params.loading != null ? event.params.loading : null
        this.title   = event.params != null && event.params.title != null ? event.params.title : null
        this.message = event.params != null && event.params.message != null ? event.params.message : null
        this.buttons = event.params != null && event.params.buttons != null ? event.params.buttons : null
      },

      opened(event) {
        
      },     
    }
  }
</script>