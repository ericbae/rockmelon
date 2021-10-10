<template>
  <div class="my-12">

    <div class="mx-auto max-w-full lg:max-w-md text-base">

      <a href="/" class="text-black flex items-center">
        <img src="https://img.icons8.com/color/96/000000/bodyguard-male.png" class="w-8 mr-3" />
        <div class="text-xl" style="font-family: Fredoka One;">tash</div>
      </a>

      <div class="p-8 mt-6 border-2 border-black" @keyup.enter="submit">

        <template v-if="sent">
          <div class="font-semibold">
            Password updated successfully
          </div>

          <div class="label-line">
            Your password has been updated successfully. You can now 
            <a href="/auth/login" class="text-blue-600 hover:underline">login</a> again.
          </div>
        </template>

        <template v-else>
          <div class="font-semibold">
            Reset your {{ getAppName }} password
          </div>
          
          <div class="label-line">
            Please type in your new password below.
          </div>

          <div class="form-line">
            <input type="password" v-model="password" ref='password' class="input-standard" :disabled="loading" />
            <div v-if="passwordError !== null" class="input-error">{{ passwordError }}</div>
          </div>

          <div class="mt-4">
            <SubmitButton v-bind:isFullWidth="true" v-bind:onClick="submit" v-bind:loading="loading" label="Submit" />
          </div>
        </template>
      </div>
    </div>

    <div class="my-6 flex justify-center">
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
      Footer
    },

    props : [
      'userId',
      'resetKey'
    ],

    data() {
      return {
        sent      : false,
        loading   : false,
        
        password     : process.env.NODE_ENV == 'development' ? 'password' : '',
        passwordError: null,
      }
    },

    mounted() {
      this.$refs.password.focus()
    },

    methods : {
      submit() {
        let valid       = true
        this.passwordError = null

        if(this.password == null || this.password.length == 0) {
          this.passwordError = "Please type a valid password."
          valid = false;
        }

        if(valid) {
          this.loading = true

          this.apiPost({
            userId  : this.userId,
            key     : this.resetKey,
            password: this.password.toLowerCase().trim(),
          }, '/auth/reset-password', (data) => {
            this.loading = false
            this.sent    = data.result == 1
          })
        }
      },
    },
  }
</script>