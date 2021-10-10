<template>
  <div class="my-12">

    <div class="mx-auto max-w-full lg:max-w-md text-base">

      <div class="flex items-center">
        <img :src="getConfig.logo" class="inline w-10 mr-3" />
        <div class="text-2xl font-black inline-block text-green-700">{{ getAppName }}</div>
      </div>

      <div class="p-8 mt-6 border-2 border-green-600 rounded-lg" @keyup.enter="submit">

        <div class="font-semibold">
          Sign up for your {{ getAppName }} account
        </div>

        <div class="label-line">Email</div>

        <div class="form-line">
          <input type="text" v-model="email" ref='email' class="input-standard" :disabled="loading" />
          <div v-if="emailError !== null" class="input-error">{{ emailError }}</div>
        </div>

        <div class="label-line">Password</div>

        <div class="form-line">
          <input type="password" v-model="password" ref="password" class="input-standard" :disabled="loading" />
          <div v-if="passwordError !== null" class="input-error">{{ passwordError }}</div>
        </div>

        <div class="label-line">Please type your password again</div>

        <div class="form-line">
          <input type="password" v-model="passwordAgain" ref="passwordAgain" class="input-standard" :disabled="loading" />
          <div v-if="passwordAgainError !== null" class="input-error">{{ passwordAgainError }}</div>
        </div>

        <div class="button-line">
          <SubmitButton :onClick="submit" :loading="loading" label="Sign Up!" :isFullWidth="true" color="green" :isRounded="true" />
        </div>

        <!-- <div class="mt-4 text-center text-gray-700 text-sm">
          By signing up, you agree to {{ getAppName }}'s 
          <a :href="getAppUrl + '/terms-of-service'" class="text-gray-700 hover:text-gray-800 underline" target="_blank">Terms of Service</a>.
        </div> -->
      </div>
    </div>

    <div class="mt-6 ml-4">
      <Footer />
    </div>
  </div>
</template>

<script>
  import SubmitButton from '@/shared/components/form/SubmitButton'
  import Footer       from './Footer'
  import SocialLogin  from './SocialLogin'

  export default {

    components : {
      SubmitButton,
      Footer,
      SocialLogin
    },

    data() {
      return {
        loading           : false,
        email             : null,
        emailError        : null,
        password          : null,
        passwordError     : null,
        passwordAgain     : null,
        passwordAgainError: null,
      }
    },

    mounted() {
      this.$refs.email.focus()
    },

    methods : {

      submit() {
        let valid               = true
        this.emailError         = null
        this.passwordError      = null
        this.passwordAgainError = null

        if(this.email == null || !this.isEmailValid(this.email)) {
          this.emailError = "Please type a valid email."
          valid = false;
        }

        if(this.password == null || this.password.length == 0) {
          this.passwordError = "Please type a valid password."
          valid = false;
        }

        if(this.password != null && this.password != this.passwordAgain) {
          this.passwordAgainError = "This does not match the password above."
          valid = false;
        }

        if(valid) {
          this.loading = true

          this.apiPost({
            email   : this.email.toLowerCase().trim(),
            password: this.password,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
          }, '/auth/register', (data) => {
            this.loading = false

            if(data.error != null) {
              this.emailError = data.error
            } 

            else {
              window.location.href = '/auth/registered'
            }
          })
        }
      },
    },
  }
</script>