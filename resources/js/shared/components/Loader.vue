<template>
  <div class="fixed top-0 w-full z-20" v-if="showLoader">
    <div class="mt-48 mx-auto w-48 text-center">
      <div class="bg-gray-300 py-4 border border-gray-600 rounded shadow opacity-75">
        <img src="/img/ajax-loader.gif" class="inline" />
        <div class="mt-2">
          Loading...
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { EventBus } from '@/shared/event-bus'

  export default {
    data() {
      return {
        showLoader : true
      }
    },

    mounted() {
      EventBus.$on('show-loader', (showOrHide) => {
        this.showLoader = showOrHide
      })
    },

    beforeDestroy() {
      EventBus.$off('show-loader')
    },
  }
</script>