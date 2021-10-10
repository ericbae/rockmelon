<template>
  <div class="my-12">

    <div class="mx-auto max-w-full lg:max-w-md text-base">

      <div class="flex items-center">
        <img :src="getConfig.logo" class="inline w-10 mr-3" />
        <div class="text-2xl font-black inline-block text-green-700">{{ getAppName }}</div>
      </div>

      <div class="p-8 mt-6 border-2 border-green-600 rounded-lg" @keyup.enter="submit">

        <div class="font-semibold">
          Sign in to your {{ getAppName }} account
        </div>

        <div class="label-line">Email</div>
        
        <div class="form-line">
          <input type="text" v-model="email" ref='email' class="input-standard" :disabled="loading" placeholder="Email" />
          <div v-if="emailError !== null" class="input-error">{{ emailError }}</div>
        </div>

        <div class="label-line">Password</div>

        <div class="form-line">
          <input type="password" v-model="password" ref="password" class="input-standard" :disabled="loading" placeholder="Password" />
          <div v-if="passwordError !== null" class="input-error">{{ passwordError }}</div>
        </div>

        <div class="button-line">
          <SubmitButton :onClick="submit" :loading="loading" label="Sign Me In!" :isFullWidth="true" color="green" :isRounded="true" />
        </div>

        <div class="mt-4 text-center">
          <a href="/auth/forgot-password" class="underline">Forgot your password?</a>
        </div>
      </div>
    </div>

    <div class="mt-6">
      <Footer />
    </div>
  </div>
</template>

<script>
  import SubmitButton from '@/shared/components/form/SubmitButton'
  import Footer       from './Footer'

  export default {

    components : {
      SubmitButton,
      Footer,
    },

    data() {
      return {
        loading      : false,
        
        showMessage  : window.showMessage,
        
        email        : this.getEnv == 'development' ? 'ericbae@gmail.com' : '',
        emailError   : null,
        
        password     : this.getEnv == 'development' ? 'password' : '',
        passwordError: null,
      }
    },

    mounted() {
      this.$refs.email.focus()
    },

    computed : {
      hasRedirectUrl() {
        return window.hasRedirectUrl;
      }
    },

    methods : {

      submit() {
        let valid          = true
        this.emailError    = null
        this.passwordError = null

        if(this.email == null || !this.isEmailValid(this.email)) {
          this.emailError = "Please type a valid email."
          valid = false;
        }

        if(this.password == null || this.password.length == 0) {
          this.passwordError = "Please type a valid password."
          valid = false;
        }

        if(valid) {
          this.loading = true

          this.apiPost({
            email   : this.email.toLowerCase().trim(),
            password: this.password            
          }, '/auth/login', (data) => {
            if(data.error != null) {
              this.loading       = false
              this.passwordError = data.error
            } else {

              if(data.url != null) {
                if(data.url.indexOf('undefined') > -1) {
                  window.location.href = "/"
                } else {
                  window.location.href = data.url
                }                
              } 

              else {
                window.location.href = "/"
              }
            }
          })
        }
      },
    },
  }
</script>