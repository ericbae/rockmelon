<template>
  <div class="max-w-full lg:max-w-5xl mx-auto mt-12 flex flex-wrap">
    <div class="w-full md:w-3/5 pr-4">
      <div class="border-2 border-green-600 rounded-lg p-6">
        <div class="flex items-center">
          <img :src="getConfig.logo" class="inline w-10 mr-3" />
          <div class="text-2xl font-black inline-block text-green-700">{{ getAppName }}</div>
        </div>

        <div class="mt-8 text-lg">
          Hey! Thanks for trying out {{ getAppName }}.
        </div>

        <div class="mt-2 text-lg">
          {{ getAppName }} is a simple tool that combines many URLs into one link - we call this a <span class="emphasis">Rockmelon Link (RL)</span> 😎
        </div>

        <div class="mt-2 text-lg">
          When you click on this <span class="emphasis">RL</span>, {{ getAppName }} will open all of its URLs at once.
        </div>

        <div class="mt-2 text-lg">
          That's the gist, but <a href="/#features" target="_blank" class="link">there are many more features</a> you might be interested in.
        </div>

        <div class="mt-8 text-lg">
          Anyway! Let's try it! First, give your Rockmelon Link a name.
        </div>

        <div class="form-line">
          <input type="text" v-model="name" ref='name' class="input-standard" :disabled="loading" placeholder="My group link" />
          <div v-if="nameError !== null" class="input-error">{{ nameError }}</div>
        </div>

        <div class="mt-8 text-lg">
          Next, add some links - Too lazy? <a href="#" @click.prevent="addRandomLinks" class="link">Add 3 random links</a>
        </div>

        <template v-for="(link, index) in links">
          <div class="form-line relative">
            <input type="text" v-model="links[index]" class="input-standard" :disabled="loading" />
            <a href="#" @click.prevent="deleteLink(index)"
              class="bg-gray-500 text-white rounded px-2 py-1 absolute text-xs hover:bg-red-500" style="top:13px;right:13px;">Delete</a>
          </div>
        </template>

        <div class="form-line">
          <a href="#" @click.prevent="addLink" class="text-gray-500 underline">Add another</a>
        </div>

        <div class="button-line">
          <SubmitButton v-bind:onClick="submit" v-bind:loading="loading" label="I'm ready! Show me the money!" v-bind:isFullWidth="true" color="green" :isRounded="true" />
        </div>
      </div>

      <div class="mt-4 flex justify-center items-center">
        <img :src="getConfig.logo" class="inline w-4 mr-3" />
        <div class="text-base font-semibold text-green-700">
          <a :href="getAppUrl" class="link">Back to {{ getAppName }} Home</a>
        </div>
      </div>
    </div>

    <div class="w-full md:w-2/5 pl-4">
      <div class="bg-yellow-100 p-4 rounded-lg">
        Take a look at something that we've already created!
        Here's a <span class="emphasis">Rockmelon Link</span> we created, which
        shows today's projects from ProductHunt and you can open all the projects at once!

        <a href="/rl/LP8YpbNzsb" class="block mt-4 bg-gray-800 text-white p-3 rounded-lg flex items-center">
          <img src="https://cdn.worldvectorlogo.com/logos/product-hunt.svg" class="w-8 mr-2" />
          Today's ProductHunt Projects
        </a>
      </div>
    </div>

    <br/><br/><br/>
  </div>
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
        loading  : false,
        name     : "My Rockmelon Link",
        nameError: null,
        links    : ['']
      }
    },

    created() {

    },

    mounted() {
      this.$refs.name.select()
      this.$refs.name.focus()
    },

    methods : {
      addLink() {
        this.links.push('')
      },

      deleteLink(index) {
        this.links.splice(index, 1)
      },

      addRandomLinks() {
        this.links = [
          'http://www.staggeringbeauty.com/',
          'http://corndog.io/',
          'http://eelslap.com/'
        ]
      },

      submit() {
        let valid       = true
        this.nameError = null

        if(this.name == null || this.name.length == 0) {
          this.nameError = "Please type in a valid name."
          valid = false
        }

        if(valid) {
          this.loading = true

          this.apiPost({
            name    : this.name,
            links   : this.links,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
          }, '/try', (linkGroup) => {
            if(linkGroup != null) {
              window.location.href = '/rl/' + linkGroup.identifier
            }
          })
        }
      },
    },
  }
</script>