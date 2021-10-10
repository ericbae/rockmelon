<template>
  <div class="">
    <Header />

    <div class="flex flex-wrap max-w-full lg:max-w-7xl mx-auto mt-12">
      <div class="w-full md:w-1/2 md:pr-4">

        <div class="border-2 border-green-600 rounded-lg p-6">

          <div class="flex items-center">
            <img :src="getConfig.logo" class="inline w-4 mr-3" />
            <div class="text-base font-semibold text-green-700">
              This {{ getAppName }} Link is powered by 
              <a :href="getAppUrl" target="_blank" class="link">{{ getAppName }}</a>
            </div>
          </div>

          <div class="mt-4 text-2xl font-black">
            {{ linkGroup.name }}
          </div>

          <div class="mt-4">
            Share this with your friends!
          </div>

          <div class="form-line relative">
            <input class="input-standard" :disabled="true" type="text" :value="getAppUrl + '/rl/' + linkGroup.identifier" />
            <a href="#" @click.prevent="copyText(getAppUrl + '/rl/' + linkGroup.identifier)"
              class="bg-gray-500 text-white rounded px-2 py-1 absolute text-xs hover:bg-green-500" style="top:13px;right:13px;">Copy</a>
          </div>

          <div class="mt-4 text-2xl font-black">
            <ShareBox :linkGroup="linkGroup" />
          </div>

          <div class="mt-8 text-lg">
            Contains {{ formatNumber(linkGroup.links.length) }} links
          </div>

          <template v-for="(link, index) in linkGroup.links">
            <a :href="link.url" class="block bg-green-100 rounded-lg p-3 text-green-800 mt-2" target="_blank">{{ link.url }}</a>
          </template>

          <div class="button-line">
            <SubmitButton v-bind:onClick="openAllLinks" v-bind:loading="loading" label="Open All Links Please" v-bind:isFullWidth="true" color="green" :isRounded="true" />
          </div> 
        </div>
      </div>

      <div class="w-full mt-8 md:mt-0 md:w-1/4 md:pl-4">
        <div class="bg-gray-200 p-4 rounded-lg">
          <div class="font-semibold text-gray-700">🤔 Links not opening?</div>

          <div class="mt-4">
            Please make sure you choose 
            <span class="emphasis">Always allow pop-ups and redirects from {{ getAppUrl }}</span>
            from your browser.
          </div>

          <div class="mt-4">
            On Chrome browser
          </div>

          <div class="mt-2">
            <img src="/img/chrome.png" class="rounded" />
          </div>

          <div class="mt-4">
            On Firefox
          </div>

          <div class="mt-2">
            <img src="/img/ff.png" class="rounded" />
          </div>
        </div>
      </div>

      <div class="w-full mt-8 md:mt-0 md:w-1/4 md:pl-4" v-if="linkGroup.user_id == null">
        <div class="bg-yellow-100 p-4 rounded-lg">
          <div class="font-semibold text-gray-700">👋 Sign up to continue!</div>

          <div class="mt-4">
            Hello there! Looks like you're trying out {{ getAppName }}, which is awesome! 🎉 If you want to 
            save this as your own and continue, why not create an account?
          </div> 

          <div class="mt-4">
            More powerful features await! Including creating 
            <a :href="getAppUrl + '/#features'" class="link">Rockmelon Links via API</a>, 
            <a :href="getAppUrl + '/#features'" class="link">private RLs</a>, 
            <a :href="getAppUrl + '/#features'" class="link">validating URLs</a>, 
            <a :href="getAppUrl + '/#features'" class="link">{{ getAppName }} Collections</a> and more!
          </div>
        </div>
      </div>

    </div>
    <br/><br/><br/>
  </div>
</template>

<script>
  import Header       from '@/shared/components/header/Home'
  import { EventBus } from '@/shared/event-bus'
  import SubmitButton from '@/shared/components/form/SubmitButton'
  import ShareBox     from './ShareBox'

  export default {

    components : {
      Header,
      SubmitButton,
      ShareBox
    },

    data() {
      return {
        loading  : false,
        linkGroup: window.currLinkGroup
      }
    },

    created() {

    },

    mounted() {

    },

    methods : {
      openAllLinks() {
        for(let i=0; i < this.linkGroup.links.length; i++) {
          window.open(this.linkGroup.links[i].url)
        }
      },
    },
  }
</script>