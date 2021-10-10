<template>
  <modal 
    name="contact-us-modal" 
    :pivotY="0.2"
    height="auto"
    :width="'500px'"
    :adaptive="true"
    :scrollable="true"
    @before-open="beforeOpen"
    @opened="opened">
    
    <div class="bg-white relative">
      <div class="text-center">
        <img src="/img/illustrations/welcome.png" class="inline" style="height:280px" />
      </div>

      <a href="#" @click.prevent="close">
        <svg width="20" height="20" stroke-width="2" class="text-gray-400 hover:text-gray-700 absolute" style="top:10px;right:10px;" stroke="currentColor" fill="none">
          <use xlink:href="/img/feather-sprite.svg#x-circle"/>
        </svg>
      </a>

      <div class="p-6">
        <template v-if="sent">
          <div class="text-lg font-light text-gray-700">
            Thank you!
          </div>

          <div class="mt-2 text-base text-gray-800">
            We've received your message and we'll get in touch very soon. Thank you so much for reaching out to us.
          </div>

          <div class="mt-12">
            <SubmitButton :onClick="close" :loading="loading" label="Close" :isFullWidth="true" />
          </div>
        </template>

        <template v-else>
          <div class="text-lg font-light text-gray-700">
            We would love to hear from you!
          </div>

          <div class="mt-2 text-base text-gray-800">
            Please fill out the form below and we'll get back to you as soon as possible.
          </div>

          <div class="mt-2">
            <textarea v-model="message" ref="message" class="text-sm p-3 w-full outline-none border border-gray-500 rounded-sm h-32"></textarea>
            <div v-if="messageError !== null" class="text-red-600 mt-1 text-sm">{{ messageError }}</div>
          </div>

          <div class="mt-2">
            <input type="text" v-model="email" ref='email' class="text-sm p-3 w-full outline-none border border-gray-500 rounded-sm" :disabled="loading" placeholder="Your email" />
            <div v-if="emailError !== null" class="text-red-600 mt-1 text-sm">{{ emailError }}</div>
          </div>

          <div class="mt-12">
            <SubmitButton :onClick="submit" :loading="loading" label="Submit" :isFullWidth="true" />
          </div>
        </template>
      </div>
    </div>
  </modal>
</template>

<script>
  import { EventBus }   from '@/shared/event-bus'
  import SubmitButton   from '@/shared/components/form/SubmitButton'
  import CloseModalIcon from '@/shared/components/modals/CloseModalIcon'

  export default {

    components : {
      CloseModalIcon,
      SubmitButton
    },

    data() {
      return {
        sent        : false,
        message     : null,
        messageError: null,
        email       : null,
        emailError  : null,
        loading     : false
      }
    },

    methods : {
      
      beforeOpen(event) {
        this.sent         = false
        this.loading      = false
        this.message      = null
        this.messageError = null
        this.email        = null
        this.emailError   = null
      },

      opened(event) {
        this.$refs.message.focus()
      },

      close() {
        this.$modal.hide('contact-us-modal')
      },

      submit() {
        let valid         = true
        this.messageError = null

        if(this.message == null || this.message.length == 0) {
          this.messageError = "Please type a valid feedback message."
          valid = false;
        }

        if(this.email == null || !this.isEmailValid(this.email)) {
          this.emailError = "Please type a valid email."
          valid = false
        }

        if(valid) {
          this.loading = true
          this.apiPost({
            message: this.message,
            email  : this.email
          }, '/contact-us', () => {
            this.loading = false
            this.sent    = true
          })
        }
      }
    }
  }
</script>