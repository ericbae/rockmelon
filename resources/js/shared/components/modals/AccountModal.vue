<template>
  <modal 
    name="account-modal" 
    :pivotY="0.2"
    :height="'auto'"
    :width="'540px'"
    :adaptive="true"
    :scrollable="true"
    @before-open="beforeOpen"
    @opened="opened"
    @adaptive="true">
    
    <div class="modal-header">
      <div class="modal-title">
        My Account
      </div>

      <a href="#"
        v-on:click.prevent="$modal.hide('account-modal')"
        class="text-gray-500 hover:text-gray-600">
        <svg width="20" height="20" stroke-width="2" class="align-middle" stroke="currentColor" fill="none">
          <use xlink:href="/img/feather-sprite.svg#x-circle"/>
        </svg>
      </a>
    </div>

    <div class="modal-body">

      <template v-if="user.twitter_data != null">
        <div class="bg-yellow-200 border-yellow-500 mb-8 p-3 text-sm text-gray-800">
          You are accessing {{ getAppName }} via your Twitter account.
        </div>
      </template>

      <label class="label-standard">Email</label>
      
      <div class="form-line">
        <input type="text" v-model="email" ref='email' class="input-standard" :disabled="loading" />
        <div v-if="emailError !== null" class="input-error">{{ emailError }}</div>
      </div>

      <div class="label-line">
        <label class="label-standard">Username</label>
      </div>
      
      <div class="form-line">
        <input type="text" v-model="username" ref='username' class="input-standard" :disabled="loading" />
        <div v-if="usernameError !== null" class="input-error">{{ usernameError }}</div>
      </div>

      <template v-if="user.twitter_data == null">
        <div class="label-line flex items-center justify-between">
          <label class="label-standard mr-4">Password</label>

          <div class="">
            <input type="checkbox" v-model="changePassword" id="input-change-password" />
            <label for="input-change-password" class="text-gray-600 ml-2">Change password</label>
          </div>
        </div>

        <div class="form-line">
          <input type="password" v-model="password" ref="password" class="input-standard" :disabled="loading || !changePassword" />
          <div v-if="passwordError !== null" class="input-error">{{ passwordError }}</div>
        </div>
      </template>

      <div class="label-line">
        <label class="label-standard">Newsletter frequency</label>
      </div>

      <div class="form-line flex items-center">
        <input type="radio" name="input-newsletter" class="mr-2"  v-model="newsletterFrequency" value="daily" id="input-newsletter-daily" />
        <label for="input-newsletter-daily" class="text-sm mr-8">Daily</label>
        
        <input type="radio" name="input-newsletter" class="mr-2" v-model="newsletterFrequency" value="weekly" id="input-newsletter-weekly" />
        <label for="input-newsletter-weekly" class="text-sm mr-8">Weekly</label>
        
        <input type="radio" name="input-newsletter" class="mr-2" v-model="newsletterFrequency" value="fortnightly" id="input-newsletter-fortnghtly" />
        <label for="input-newsletter-fortnghtly" class="text-sm mr-8">Fortnightly</label>
        
        <input type="radio" name="input-newsletter" class="mr-2" v-model="newsletterFrequency" value="monthly" id="input-newsletter-monthly" />
        <label for="input-newsletter-monthly" class="text-sm mr-8">Monthly</label>

        <input type="radio" name="input-newsletter" class="mr-2" v-model="newsletterFrequency" value="never" id="input-newsletter-never" />
        <label for="input-newsletter-never" class="text-sm">Never</label>
      </div>

      <div class="label-line">
        <label class="label-standard">Cloudflare API Token</label>
      </div>

      <div class="form-line">
        <input type="text" v-model="cloudflareApiToken" class="input-standard" :disabled="loading" />
      </div>

      <div class="button-line">
        <SubmitButton v-bind:onClick="submit" v-bind:loading="loading" label="Save" v-bind:isFullWidth="true" />
      </div>
    </div>
  </modal>
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
        loading       : false,
        
        user          : window.currUser,
        
        email         : window.currUser.email,
        emailError    : null,
        
        username      : window.currUser.username,
        usernameError : null,
        
        changePassword: false,
        password      : process.env.NODE_ENV == 'development' ? 'password' : '',
        passwordError : null,

        newsletterFrequency : window.currUser.newsletter_frequency != null ? window.currUser.newsletter_frequency : 'weekly',

        cloudflareApiToken : null
      }
    },

    methods : {

      beforeOpen(event) {
        this.loading            = false
        this.email              = window.currUser.email
        this.emailError         = null
        this.username           = window.currUser.username
        this.usernameError      = null
        this.password           = process.env.NODE_ENV == 'development' ? 'password' : '',
        this.passwordError      = null,
        this.cloudflareApiToken = window.currUser.cloudflare_api_token
      },

      opened() {
        if(this.$refs.email != null) {
          this.$refs.email.focus()
        }
      },

      close() {
        this.$modal.hide('account-modal')
      },

      submit() {
        let valid          = true
        this.emailError    = null
        this.usernameError = null
        this.passwordError = null

        if(this.email == null || !this.isEmailValid(this.email)) {
          this.emailError = "Please type a valid email."
          valid = false;
        }

        if(this.username == null || this.username.length == 0) {
          this.usernameError = "Please type a valid username."
          valid = false;
        }

        if(this.changePassword && (this.password == null || this.password.length == 0)) {
          this.passwordError = "Please type a valid password."
          valid = false;
        }

        if(valid) {
          this.loading = true

          this.apiPost({
            userId             : window.currUser.user_id,
            email              : this.email.toLowerCase().trim(),
            username           : this.username,
            password           : this.changePassword ? this.password : null,
            newsletterFrequency: this.newsletterFrequency,
            cloudflareApiToken : this.cloudflareApiToken
          }, '/auth/account', (member) => {
            this.loading = false

            if(member.error != null) {
              this.emailError = member.error
            } else {
              currUser = member
              this.$modal.hide('account-modal')
              EventBus.$emit('notification-event', {
                'type'   : 'success', 
                'message': 'Account saved successfully.'
              })
            }
          })
        }
      },
    },
  }
</script>