<template>
  <div class="my-12">

    <div class="mx-auto max-w-full lg:max-w-md text-base">

      <a href="/" class="text-black flex items-center">
        <img src="https://img.icons8.com/color/96/000000/bodyguard-male.png" class="w-8 mr-3" />
        <div class="text-xl" style="font-family: Fredoka One;">tash</div>
      </a>

      <div class="p-8 mt-6 border-2 border-black" @keyup.enter="submit">

        <div class="font-semibold">
          Forgot your password?
        </div>

        <template v-if="sent">
          <div class="label-line">
            Please check your inbox. We have sent an email with a link to reset your password.
          </div>
        </template>

        <template v-else>
          <div class="label-line">
            No problem. Please type in your email below and we will send you a link to reset your password.
          </div>

          <div class="mt-6">
            <input type="text" v-model="email" ref='email' class="input-standard" :disabled="loading" />
            <div v-if="emailError !== null" class="input-error">{{ emailError }}</div>
          </div>

          <div class="mt-4">
            <SubmitButton :isFullWidth="true" :onClick="submit" :loading="loading" label="Submit" />
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
      Footer,
      SubmitButton,
    },

    data() {
      return {
        sent      : false,
        loading   : false,
        email     : null,
        emailError: null,
      }
    },

    mounted() {
      this.$refs.email.focus()
    },

    methods : {
      submit() {
        let valid       = true
        this.emailError = null

        if(this.email == null || !this.isEmailValid(this.email)) {
          this.emailError = "Please type a valid email."
          valid = false;
        }

        if(valid) {
          this.loading = true

          this.apiPost({
            email: this.email.toLowerCase().trim(),
          }, '/auth/forgot-password', (data) => {
            this.loading = false
            if(data.error != null) {
              this.emailError = data.error
            } else {
              this.sent = true
            }
          })
        }
      },
    },
  }
</script>