<template>
  <div class="">
    <ContactUsModal />
  </div>
</template>

<script>
  import ContactUsModal from './ContactUsModal'

  export default {

    components : {
      ContactUsModal
    },

    mounted() {
      let that = this
      setTimeout(function() {
        var elements = document.getElementsByClassName("contact-us-link")

        for (var i = 0; i < elements.length; i++) {

          elements[i].addEventListener('click', function(e) {
            e.preventDefault()
            that.showContactUsModal()
          }, false);
        }
      }, 2000)
    },

    methods : {
      showContactUsModal() {
        this.$modal.show('contact-us-modal')
      },
    },
  }
</script>