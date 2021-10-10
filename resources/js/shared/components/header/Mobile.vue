<template>
  <div class="block lg:hidden bg-white px-6 py-4 flex flex-wrap">
    
    <div class="w-1/3">
      <a href="/" class="no-underline text-gray-800 block mt-1"
        v-on:click.prevent="openMobileNavigation">
        <svg width="24" height="24" stroke-width="2" class="inline" stroke="currentColor" fill="none">
          <use xlink:href="/img/feather-sprite.svg#menu"/>
        </svg>
      </a>
    </div>

    <div class="w-1/3 text-center">
      <a href="/" class="no-underline block">
        <img :src="getConfig.logo" class="inline w-8" />
        <!-- <div class="text-base inline-block text-gray-800 font-black">{{ getAppName }}</div> -->
      </a>
    </div>

    <div class="w-1/3 text-right">
      <template v-if="getCurrUser == null">
        <div style="margin-top:7px;">
          <a href="/register" class="text-white text-sm no-underline hover:underline mr-4">Register</a>
          <a href="/login" class="text-white text-sm no-underline hover:underline">Login</a>
        </div>
      </template>

      <template v-else>

        <template v-if="getCurrUser != null && getCurrUser.confirmed == false">
          <a href="#" 
            class="text-red-400 mr-4"
            style="margin-top:2px;" 
            v-on:click="$modal.show('confirm-registration-modal')">
            <svg width="20" height="20" stroke-width="2" class="inline" stroke="currentColor" fill="none">
              <use xlink:href="/img/feather-sprite.svg#bell"/>
            </svg>    
          </a>
        </template>

        <a href="/account" class="no-underline"
          v-tooltip.bottom="{ html : 'user-options-mobile', class : 'user-option-class' }">
          <template v-if="getCurrUser.profile_image != null">
            <img :src="getCurrUser.profile_image" style="width:30px;" class="rounded-full inline" />
          </template>

          <template v-else>
            <img src="https://cdn2.iconfinder.com/data/icons/pinterest-ui/48/Jee-61-512.png" 
              style="height:30px;" 
              class="inline bg-white rounded-full" />
          </template>
        </a>

        <div id="user-options-mobile" class="">
          <a href="/account" class="block text-sm text-white no-underline hover:underline">My account</a>
          <a href="/auth/logout" class="block text-sm text-white no-underline hover:underline">Logout</a>        
        </div>
      </template>
    </div>
  </div>
</template>

<script>
  import { EventBus }   from '@/shared/event-bus'

  export default {
    methods : {
      openMobileNavigation() {
        EventBus.$emit('open-mobile-navigation') 
      }
    }
  }
</script>