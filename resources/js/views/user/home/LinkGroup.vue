<template>
  <a :href="getLinkGroupUrl(linkGroup)" 
    class="block w-full lg:w-1/3 px-4 pb-8">
    <div class="bg-white hover:bg-green-50 p-4 border border-green-500 rounded-lg">
      
      <div class="text-lg font-semibold text-green-700">
        {{ linkGroup.name }}
      </div>

      <div class="mt-2 text-gray-600">
        contains 
        {{ linkGroup.num_links }}
        {{ linkGroup.num_links == 1 ? 'link' : 'links' }}
      </div>

      <!-- <div class="">
        <a :href="'https://twitter.com/' + account.account.screen_name" @click.stop target="_blank" class="underline">@{{ account.account.screen_name }}</a>
      </div>

      <div class="mt-2 text-gray-600">
        This account has <span class="bg-gray-200 px-1 text-black">{{ formatNumber(account.account.followers_count) }}</span> followers,
        and follows <span class="bg-gray-200 px-1 text-black">{{ formatNumber(account.account.friends_count) }}</span> Twitter users.
      </div>

      <div class="mt-3">
        <span class="text-gray-500">Keywords</span> 
        <template v-if="account.keywords != null && account.keywords.length > 0">
          <span class="ml-1 px-1 bg-gray-200">{{ account.keywords[0] }}</span>

          <template v-if="account.keywords.length > 1">
            + {{ account.keywords.length - 1}} more
          </template>
        </template>

        <template v-else>
          <span class="ml-1">No keywords found</span>
        </template>
      </div> -->
    </div>

    <!-- <div class="mt-4 pt-4 border-t flex flex-wrap -mx-2">
      <div class="w-1/2 px-2">
        <a :href="getLinkGroupUrl(linkGroup)" @click.stop
          class="flex items-center border border-green-500 bg-green-100 text-green-500 justify-center py-2 rounded-sm text-xs font-semibold hover:bg-green-500 hover:text-white" target="_blank">
          <svg width="12" height="12" stroke-width="2" class="mr-2" stroke="currentColor" fill="none">
            <use xlink:href="/img/feather-sprite.svg#link"/>
          </svg>
          View
        </a>
      </div>

      <div class="w-1/2 px-2">
        <a href="#" @click.prevent="$modal.show('comparison-modal', { comparison : comparison })"
          class="flex items-center border border-blue-500 bg-blue-100 text-blue-500 justify-center py-2 rounded-sm text-xs font-semibold hover:bg-blue-500 hover:text-white">
          <svg width="12" height="12" stroke-width="2" class="mr-2" stroke="currentColor" fill="none">
            <use xlink:href="/img/feather-sprite.svg#settings"/>
          </svg>
          Edit
        </a>
      </div>
    </div> -->
  </a>
</template>

<script>
  import { EventBus } from '@/shared/event-bus'

	export default {
		props : {
			linkGroup : {
				type    : Object,
				required: true
			}
		},

    data() {
      return {
        checked : false
      }
    },

    methods : {

    }
	}
</script>