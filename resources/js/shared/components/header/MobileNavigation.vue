<template>
  <div v-if="show" class="fixed top-0 left-0 w-1/2 bg-white border-r shadow-lg overflow-y-auto" style="height:calc(100vh - 0px);">

    <a href="#" 
      v-on:click.prevent="closeMobileNavigation" 
      class="pl-6 flex items-center text-gray-500" 
      style="height:62px;">
      <svg width="20" height="20" stroke-width="2" class="inline" stroke="currentColor" fill="none">
        <use xlink:href="/img/feather-sprite.svg#x-circle"/>
      </svg>

      <span class="text-sm uppercase ml-2">Close</span>
    </a>

    <template v-if="site == null">
      <div class="px-6">
        <a href="/link-group/create"
          class="text-center block no-underline rounded font-semibold text-white bg-purple-600 hover:bg-purple-700 text-xs py-3">+ &nbsp; Create a new link group
        </a>

        <div class="mt-8 ml-1">
          <a class="nav-item" href="/">
            <svg width="14" height="14" stroke-width="2" class="nav-item-icon" stroke="currentColor" fill="none">
              <use xlink:href="/img/feather-sprite.svg#home"/>
            </svg>

            <span class="nav-item-text">Home</span>
          </a>

          <!-- <a class="nav-item" href="/gg/home">
            <svg width="14" height="14" stroke-width="2" class="nav-item-icon" stroke="currentColor" fill="none">
              <use xlink:href="/img/feather-sprite.svg#clipboard"/>
            </svg>

            <span class="nav-item-text">Ad Manager</span>
          </a>

          <a class="nav-item" href="/billing/home">
            <svg width="14" height="14" stroke-width="2" class="nav-item-icon" stroke="currentColor" fill="none">
              <use xlink:href="/img/feather-sprite.svg#credit-card"/>
            </svg>

            <span class="nav-item-text">Billing</span>
          </a>

          <a class="nav-item" href="#" v-on:click.prevent>
            <svg width="14" height="14" stroke-width="2" class="nav-item-icon" stroke="currentColor" fill="none">
              <use xlink:href="/img/feather-sprite.svg#mail"/>
            </svg>

            <span class="nav-item-text">Contact us</span>
          </a>

          <a class="nav-item" href="https://twitter.com/newsyco" target="_blank">
            <svg width="14" height="14" stroke-width="2" class="nav-item-icon" stroke="currentColor" fill="none">
              <use xlink:href="/img/feather-sprite.svg#twitter"/>
            </svg>

            <span class="nav-item-text">Follow @newsy</span>
          </a> -->
        </div>
      </div>
    </template>

    <template v-else>
      <div class="px-6">
        <SiteNavigation v-bind:site="site" />
      </div>
    </template>
  </div>
</template>

<script>
  import { EventBus }   from '@/shared/event-bus'
  
  export default {

    data() {
      return {
        show: false,
        site: window.currSite != null ? window.currSite : null
      }
    },

    mounted() {
      EventBus.$on('open-mobile-navigation', () => {
        this.show = true
      })
    },

    beforeDestroy() {
      EventBus.$off('open-mobile-navigation')
    },

    computed: {
      currUser() {
        return window.currUser
      },

      currUrl() {
        return window.location.href
      },

      currPathname() {
        return window.location.pathname
      }
    },

    methods : {
      closeMobileNavigation() {
        this.show = false
      }
    },
  }
</script>