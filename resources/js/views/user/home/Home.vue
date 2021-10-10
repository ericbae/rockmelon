<template>
  <div class="">
    <Header />

    <div class="max-w-full lg:max-w-7xl mx-auto">
      
      <div class="mt-12" v-if="linkGroups != null && linkGroups.length > 0">
        <div class="text-xl">
          You have {{ linkGroups.length }} {{ linkGroups.length == 1 ? getAppName + ' link' : getAppName + ' links' }}.
        </div>
      </div>

      <template v-if="linkGroups == null">
      </template>

      <template v-else>
        <template v-if="linkGroups.length == 0">
          <a href="#" @click="addComparison" class="block mx-auto mt-24 p-10 text-center">
            <img src="https://img.icons8.com/ios/100/000000/ostrich-head-in-sand.png" class="inline w-12" />
            <br/><br/>
            <div class="">
              No {{ getAppName }} links have been created yet.<br/>
              <a class="underline" href="/link-group/create">Let's create one to get started!</a>
            </div>
          </a>
        </template>

        <template v-else>
          <div class="flex flex-wrap -mx-4 mt-10">
            <template v-for="(linkGroup, index) in linkGroups">
              <LinkGroup :linkGroup="linkGroup" />
            </template>

            <!-- <a href="/link-group/create" class="block w-full lg:w-1/3 px-4 pb-8">
              <div class="border border-dashed border-black mx-auto p-12 text-center">
                <img src="https://img.icons8.com/ios/100/000000/dolphin.png" class="inline w-10" />
                <div style="margin-top:16px;">
                  <a class="underline" href="#">Add another linkGroup!</a>
                </div>
              </div>
            </a> -->
          </div>
        </template>
      </template>
    </div>
  </div>
</template>

<script>
  import { EventBus }     from '@/shared/event-bus'
  import Header           from '@/shared/components/header/Home'
  import LinkGroup        from './LinkGroup'

  export default {

    components : {
      Header,
      LinkGroup,
    },

    data () {
      return {
        linkGroups: null,
        loading    : false,
        params     : {},
      }
    },

    created() {
      this.getData()
    },

    mounted() {
      // document.body.className = 'bg-gray-200'

      // this.postActivity({
      //   description: 'home',
      //   causerType : 'App\\Models\\User',
      //   causerId   : this.getCurrUser.id
      // })
      EventBus.$on('get-link-groups', () => {
        this.getData()
      })
    },

    methods : {
      getData() {
        this.loading = true
        this.apiGet(this.params, '/link-group/data', (linkGroups) => {
          this.loading  = false
          this.linkGroups = linkGroups
          // this.linkGroups = []
        })
      },
    }
  }
</script>