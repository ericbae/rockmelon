<template>
  <div class="">
    <!-- <div class="text-lg text-gray-600">
      🤙 Share this with others
    </div> -->

    <div class="mt-2">
      <a :href="'https://www.linkedin.com/shareArticle?mini=true&url=' + getLinkGroupUrl(linkGroup) + '&title=' + linkGroup.name + 
        '&summary=' + getSummary() + '&source=' + getLinkGroupUrl(linkGroup)" rel="noopener" target="_blank">
        <img src="https://cdn2.iconfinder.com/data/icons/social-icon-3/512/social_style_3_in-512.png" class="inline w-8" />
      </a>

      <a :href="'https://www.facebook.com/dialog/share?app_id=645057395962262&display=popup&href=' + getLinkGroupUrl(linkGroup)" rel="noopener" class="mx-1" target="_blank">
        <img src="https://cdn2.iconfinder.com/data/icons/social-icon-3/512/social_style_3_facebook-512.png" class="inline w-8" />
      </a>

      <a :href="'https://twitter.com/intent/tweet?text=' + getSummary() + '&url=' + getLinkGroupUrl(linkGroup)" rel="noopener" target="_blank">
        <img src="https://cdn2.iconfinder.com/data/icons/social-icon-3/512/social_style_3_twiter-512.png" class="inline w-8" />
      </a>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        linkGroup : window.currLinkGroup
      }
    },

    methods : {
      getSummary() {
        let text = this.getAppName + " links - " + this.linkGroup.name
        return text
      },
    }
  }
</script>