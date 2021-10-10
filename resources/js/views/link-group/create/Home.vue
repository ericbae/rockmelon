<template>
  <div class="">
    <Header />

    <div class="max-w-full lg:max-w-xl mx-auto mt-12 border-2 border-green-600 rounded-lg p-6">
      <div class="flex items-center">
        <div class="text-2xl font-black inline-block text-green-700">Create a {{ getAppName }} Link</div>
      </div>

      <div class="mt-8 text-lg">
        First, give your Rockmelon Link a name.
      </div>

      <div class="form-line">
        <input type="text" v-model="name" ref='name' class="input-standard" :disabled="loading" placeholder="My group link" />
        <div v-if="nameError !== null" class="input-error">{{ nameError }}</div>
      </div>

      <div class="mt-8 text-lg">
        Next, add some links
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
        <SubmitButton v-bind:onClick="submit" v-bind:loading="loading" label="Save" v-bind:isFullWidth="true" color="green" :isRounded="true" />
      </div>
    </div>
    <br/><br/><br/>
  </div>
</template>

<script>
  import Header       from '@/shared/components/header/Home'
  import { EventBus } from '@/shared/event-bus'
  import SubmitButton from '@/shared/components/form/SubmitButton'

  export default {

    components : {
      Header,
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