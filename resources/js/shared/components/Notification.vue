<template>
  <transition name="fade">
    <div v-if="show" 
      :class="type"
      style="position:fixed;top:10px;margin-left:-150px;left:50%;z-index:10000;width:300px;text-align:center;">

      <svg width="18" height="18" stroke-width="2" class="align-middle inline" stroke="currentColor" fill="none">
        <use v-if="type == 'success'" xlink:href="/img/feather-sprite.svg#check-circle"/>
        <use v-else-if="type == 'error'" xlink:href="/img/feather-sprite.svg#alert-circle"/>
      </svg>
      <span class="ml-2">{{ message }}</span>
    </div>
  </transition>
</template>

<script>
  import { EventBus } from '@/shared/event-bus'

  export default {

    data() {
      return {
        show   : false,
        message: '',
        type   : 'success'
      }
    },

    mounted () {
      EventBus.$on('notification-event', notification => {
        this.show    = true
        this.message = notification.message;
        this.type    = notification.type

        let that = this
        window.setTimeout(function() {
          that.show = false
        }, 3000)

      });
    },

    beforeDestroy() {
      EventBus.$off('notification-event')
    }
  }
</script>

<style scoped>
  .fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
  }
  .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
  }

  .success {
    @apply bg-green-600 text-white px-3 py-3 rounded text-sm shadow-md
  }

  .error {
    @apply bg-red-500 text-white px-3 py-3 rounded text-sm shadow-md
  }
</style>