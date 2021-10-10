<template>
  <div class="">
    <Header />

    <div class="mx-auto max-w-full lg:max-w-xl px-8 lg:px-0 my-12">
      <div class="text-xl text-gray-600 ml-2">My Account</div>

      <div class="bg-white p-6 rounded-lg border border-gray-200 mt-6 shadow-lg" @keyup.enter="submit">

        <label class="label-standard">Email</label>
        
        <div class="form-line">
          <input type="email" v-model="email" ref='email' class="input-standard" :disabled="loading" />
          <div v-if="emailError !== null" class="input-error">{{ emailError }}</div>
        </div>

        <div class="label-line">
          <label class="label-standard">Username</label>
        </div>
        
        <div class="form-line">
          <input type="text" v-model="username" ref='username' class="input-standard" :disabled="loading" />
          <div v-if="usernameError !== null" class="input-error">{{ usernameError }}</div>
        </div>

        <div class="label-line flex items-center justify-between">
          <label class="label-standard mr-4">Password</label>

          <div class="flex items-center">
            <input type="checkbox" v-model="changePassword" id="input-change-password" />
            <label for="input-change-password" class="text-gray-600 ml-2">Change password</label>
          </div>
        </div>

        <div class="form-line">
          <input type="password" v-model="password" ref="password" class="input-standard" :disabled="loading || !changePassword" />
          <div v-if="passwordError !== null" class="input-error">{{ passwordError }}</div>
        </div>

        <div class="label-line">
          <label class="label-standard">Report frequency</label>
        </div>

        <div class="text-gray-600 text-sm mt-1">
          Report contains information about available, expired and expiring domains and also provides an overview of all your 
          domains.
        </div>

        <div class="mt-4 flex items-center">          
          <input type="radio" name="input-report" class="mr-2" v-model="newsletterFrequency" value="daily" id="input-report-daily" />
          <label for="input-report-daily" class="mr-8">Daily</label>

          <input type="radio" name="input-report" class="mr-2" v-model="newsletterFrequency" value="weekly" id="input-report-weekly" />
          <label for="input-report-weekly" class="mr-8">Weekly</label>
          
          <input type="radio" name="input-report" class="mr-2" v-model="newsletterFrequency" value="fortnightly" id="input-report-fortnghtly" />
          <label for="input-report-fortnghtly" class="mr-8">Fortnightly</label>
          
          <input type="radio" name="input-report" class="mr-2" v-model="newsletterFrequency" value="monthly" id="input-report-monthly" />
          <label for="input-report-monthly" class="mr-8">Monthly</label>

          <input type="radio" name="input-report" class="mr-2" v-model="newsletterFrequency" value="never" id="input-report-never" />
          <label for="input-report-never" class="">Never</label>
        </div>

        <div class="button-line">
          <SubmitButton :onClick="submit" :loading="loading" label="Save" :isFullWidth="true" />
        </div>
      </div>
    </div>

  </div>
</template>

<script>
  import { EventBus } from '@/shared/event-bus'
  import Header       from '@/shared/components/header/Home'
  import SubmitButton from '@/shared/components/form/SubmitButton'

  export default {

    components : {
      Header,
      SubmitButton
    },

    mounted() {
      document.body.className = 'bg-gray-100'
    },

    data() {
      return {
        loading            : false,
        
        user               : window.currUser,
        
        email              : window.currUser.email,
        emailError         : null,
        
        username           : window.currUser.username,
        usernameError      : null,
        
        changePassword     : false,
        password           : process.env.NODE_ENV == 'development' ? 'password' : '',
        passwordError      : null,

        newsletterFrequency: window.currUser.newsletter_frequency != null ? window.currUser.newsletter_frequency : 'weekly',
      }
    },

    methods : {

      beforeOpen(event) {
        this.loading         = false
        this.email           = window.currUser.email
        this.emailError      = null
        this.username        = window.currUser.username
        this.usernameError   = null
        this.password        = null
        this.passwordError   = null
        this.newsletterFrequency = window.currUser.newsletter_frequency != null ? window.currUser.newsletter_frequency : 'weekly'
      },

      opened() {
        if(this.$refs.email != null) {
          this.$refs.email.focus()
        }
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
          }, '/user/account', (user) => {
            this.loading = false

            if(user.error != null) {
              this.emailError = user.error
            } else {
              window.currUser = user
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