<template>
  <div class="bg-white border-b">
    <div class="hidden lg:block py-5 px-10 max-w-full lg:max-w-10xl mx-auto">
      <div class="flex flex-wrap items-center">

        <div class="w-1/2 flex items-center">

          <a href="/" class="no-underline mr-12 flex items-center">
            <img :src="getConfig.logo" class="inline w-10 mr-3" />
            <div class="text-2xl font-black inline-block text-green-700">{{ getAppName }}</div>
          </a>

          <a class="header-nav-item" href="/home">Home</a>
          <a class="header-nav-item ml-6" href="/api">API</a>

          <template v-if="getCurrUser != null">
            <a class="header-nav-item ml-6" href="#" @click.prevent="$modal.show('feedback-modal')">Feedback</a>
          </template>
        </div>


        <div class="w-1/2 flex items-center justify-between">
          <div class=""></div>
          <div class="flex items-center">
          
            <template v-if="getCurrUser != null">
              <a href="/link-group/create"
                class="no-underline text-sm text-white bg-green-600 hover:bg-green-700 rounded-lg py-3 px-6 mr-8">+ &nbsp; Create {{ getAppName }} Link
              </a>

              <template v-if="getCurrUser.profile_image != null">
                <img :src="getCurrUser.profile_image" style="width:30px;" class="rounded-full inline" />
              </template>

              <template v-else>
                <img src="https://cdn2.iconfinder.com/data/icons/ios-7-icons/50/user_male4-512.png" 
                  style="height:30px;" class="inline rounded-full" />
              </template>

              <a href="#"
                @click.prevent="$modal.show('account-modal')"
                class="no-underline text-gray-800 ml-2 flex items-center"
                v-tooltip.bottom="{ html : 'user-options', class : 'user-option-class' }">
                {{ getCurrUser.username }}

                <img src="https://img.icons8.com/material/24/000000/expand-arrow--v1.png" class="ml-1 w-4">
              </a>

              <div id="user-options" class="">
                <a href="/auth/logout" class="tooltip-link">Logout</a>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { EventBus }   from '@/shared/event-bus'

  export default {

    components : {
    },

    props : {
      newBlogPosts : {
        type    : Array,
        required: false
      }
    },

    data() {
      return {
        query        : this.$route != null ? this.$route.params.query : null,
        showLoader   : true,
        toBeConfirmed: window.toBeConfirmed,
        confirmed    : window.confirmed,
      }
    },

    created() {      
      let that = this
      window.setTimeout(function() {
        that.getNumInProgress()
        that.getNumNewNotifications()
      }, 1500)
    },

    mounted() {
      if(this.toBeConfirmed == 1) {
        this.$modal.show('confirm-registration-modal')
      }

      if(this.confirmed == 1) {
        this.$modal.show('confirmed-modal')
      }
    },

    computed: {
      currUser() {
        return window.currUser
      },

      currUrl() {
        return window.location.href
      },

      currPathname() {
        return window.location.pathname
      },

      showNumBlogPosts() {
        let blogModalCookie = this.$cookies.get('blog-modal-cookie')
        
        if(blogModalCookie == null) {
          return true
        }

        else if(this.newBlogPosts != null) {
          for(let i=0; i < this.newBlogPosts.length; i++) {
            let publishedAt = moment(this.newBlogPosts[i].published_at, "YYYY-MM-DD HH:mm:ss").unix()

            if(publishedAt > blogModalCookie) {
              return true
            }
          }
        }

        return false
      }
    },

    methods : {
      // showNewBlogPosts() {
      //   this.$modal.show('blog-post-modal')
      // },

      search() {
        if(this.$route != null) {
          EventBus.$emit('search-param-changed', {
            key  : 'query',
            value: this.query
          })
        }

        else {
          window.location.href = this.getAppUrl + (this.getEnv == 'local' ? '/#/' : '/') + this.query + '/all/all/all/all/created_at/desc/20/0'
        }
      },

      getNumInProgress() {
        // this.apiGet({}, '/user/num-in-progress', (numInProgress) => {
        //   this.numInProgress = numInProgress
        // })
      },

      getNumNewNotifications() {
        // this.apiGet({}, '/notification/num-new', (numNewNotifications) => {
        //   this.numNewNotifications = numNewNotifications
        // })
      },
    }
  }
</script>