<template>
  <div class="">

    <div class="mx-auto max-w-full lg:max-w-md mt-12">

      <a href="/" class="text-black flex items-center">
        <img src="https://img.icons8.com/color/96/000000/bodyguard-male.png" class="w-12 mr-3" />
        <div class="text-2xl" style="font-family: Fredoka One;">tash</div>
      </a>

      <div class="p-8 mt-6 border-4 rounded-lg border-black" @keyup.enter="submit">

        <div class="text-xl font-black">
          Please save your email
        </div>

        <div class="" @keyup.enter="submit">

          <div class="label-line">Please type your email address below.</div>

          <div class="form-line">
            <input type="text" v-model="email" ref='email' class="input-standard" :disabled="loading" placeholder="Email" />
            <div v-if="emailError !== null" class="input-error">{{ emailError }}</div>
          </div>

          <div class="mt-4">
            <SubmitButton v-bind:onClick="submit" v-bind:loading="loading" label="Save" v-bind:isFullWidth="true" color="black" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Header       from '@/shared/components/header/Home'
  import SubmitButton from '@/shared/components/form/SubmitButton'

  export default {

    components : {
      Header,
      SubmitButton,
    },

    data() {
      return {
        key       : window.currKey,
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
            email: this.email,
            key  : this.key
          }, '/auth/insert-email', (data) => {
            this.loading = false
            
            if(data.error != null) {
              this.emailError = data.error
            } 

            else {
              // this.apiGet({ email : email }, '/check-if-spam', () => {})
              window.location.href = '/'
            }
          })
        }
      },
    },
  }
</script>