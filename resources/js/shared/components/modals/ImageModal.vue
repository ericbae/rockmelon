<template>
  <modal 
    name="image-modal" 
    :pivotY="0.2"
    :height="'auto'"
    :width="'700px'"
    :adaptive="true"
    :scrollable="true"
    @opened="opened"
    @before-open="beforeOpen">

    <!-- <div class="modal-header">
      <div class="modal-title"></div>

      <a href="#"
        v-on:click.prevent="$modal.hide('image-modal')">
        <svg width="18" height="18" stroke-width="2" stroke="currentColor" fill="none">
          <use xlink:href="/img/feather-sprite.svg#x-circle"/>
        </svg>
      </a>
    </div> -->
    
    <div class="p-6">

      <img :src="imageUrl" />

      <div class="button-line">
        <SubmitButton v-bind:onClick="submit" label="Close" color="gray" v-bind:loading="loading" />
      </div>
    </div>
  </modal>
</template>

<script>
  import { EventBus } from '@/shared/event-bus'
  import SubmitButton from '@/shared/components/form/SubmitButton'

  export default {

    components : {
      SubmitButton,
    },

    data() {
      return {
        imageUrl: null,
        loading : false,
      }
    },

    methods : {

      beforeOpen(event) {
        this.loading  = false
        this.imageUrl = event.params.imageUrl
      },

      opened(event) {
        // this.$refs.code.focus()
      },

      submit() {
        this.$modal.hide('image-modal')
      }
    }
  }
</script>

<style>
  .vs__search {
    margin:8px 0 !important;
  }
</style>