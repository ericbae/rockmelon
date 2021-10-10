<template>
  <modal 
    name="comparison-modal" 
    :pivotY="0.2"
    :height="'auto'"
    :width="'560px'"
    :adaptive="true"
    :scrollable="true"
    @before-open="beforeOpen"
    @opened="opened"
    @adaptive="true">
    
    <div class="modal-header">
      <div class="title-box">
        <div class="modal-title">
          Update comparison
        </div>

        <CloseModalIcon modalName="comparison-modal" />
      </div>
    </div>

    <div class="modal-body" v-if="comparison != null">

      <label class="label-standard">Let's start with a name</label>

      <div class="form-line">
        <input type="text" v-model="comparison.name" ref='name' class="input-standard" :disabled="loading" />
        <div v-if="nameError !== null" class="input-error">{{ nameError }}</div>
      </div>

      <!-- <div class="label-line">
        <label class="label-standard">What are you comparing?</label>
      </div>

      <div class="form-line flex items-center">
        <select class="select-standard h-12" v-model="comparison.comparison_type">
          <option :value="null">Please select an option</option>
          <option value="twitter-follower-count">Number of followers on Twitter</option>
          <option value="hn-karma-points">HN karma points</option>
          <option value="github-repo-stars">GitHub repo stars</option>
        </select>
      </div>

      <div v-if="comparisonTypeError !== null" class="input-error">{{ comparisonTypeError }}</div> -->

      <div class="label-line">
        <label class="label-standard">Who can view this comparison?</label>
      </div>

      <div class="form-line flex items-center">
        <input type="radio" name="input-type" class="mr-2"  v-model="comparison.access_type" value="public" id="input-type-public" />
        <label for="input-type-public" class="text-base flex items-center">
          <div class="w-20">Public</div>
          <div class="ml-2 text-gray-600">By anyone</div>
        </label>
      </div>

      <div class="form-line flex items-center">
        <input type="radio" name="input-type" class="mr-2"  v-model="comparison.access_type" value="private" id="input-type-private" />
        <label for="input-type-private" class="text-base flex items-center">
          <div class="w-20">Private</div>
          <div class="ml-2 text-gray-600">By me and invited people only</div>
        </label>
      </div>

      <div class="label-line text-base">Give it an emoji icon</div>

      <div class="form-line flex items-center">
        <template v-if="comparison.icon != null">
          <div class="border p-2 rounded text-2xl">{{ comparison.icon }}</div>
        </template>

        <template v-else>
          <div class="border p-2 rounded">None selected</div>
        </template>

        <a href="#" @click.prevent="showEmojiPicker = true" class="ml-2 text-sm text-gray-600 hover:underline">Change</a>
      </div>

      <div class="form-line" v-if="showEmojiPicker">
        <picker set="apple" @select="emojiSelected" />
      </div>

      <div class="button-line">
        <SubmitButton :onClick="submit" :loading="loading" label="Next" :isFullWidth="true" />
      </div>

      <div class="form-line text-center">
        <a href="#" @click.prevent="deleteComparison" class="text-gray-600 hover:underline text-sm">Delete this</a>
      </div>
    </div>
  </modal>
</template>

<script>
  import { Picker }     from 'emoji-mart-vue'
  import { EventBus }   from '@/shared/event-bus'
  import SubmitButton   from '@/shared/components/form/SubmitButton'
  import CloseModalIcon from '@/shared/components/modals/CloseModalIcon'

  export default {

    components : {
      Picker,
      SubmitButton,
      CloseModalIcon
    },

    data() {
      return {
        loading            : false,
        comparison         : null,
        nameError          : null,
        comparisonTypeError: null,
        showEmojiPicker    : false
      }
    },

    mounted() {
      // EventBus.$on('get-categories', () => {
      //   this.getCategories()
      // })
    },

    methods : {

      beforeOpen(event) {
        this.loading             = false
        this.comparison          = event.params.comparison
        this.nameError           = null
        this.comparisonTypeError = null
      },

      opened() {
        this.$refs.name.focus()
      },

      close() {
        this.$modal.hide('comparison-modal')
      },

      emojiSelected(emoji) {
        this.comparison.icon = emoji.native
        this.showEmojiPicker = false
      },

      submit() {
        let valid                = true
        this.nameError           = null
        this.comparisonTypeError = null

        if(this.comparison.name == null || this.comparison.name.length == 0) {
          this.nameError = "Please type in a valid name."
          valid = false
        }

        if(this.comparison.comparison_type == null) {
          this.comparisonTypeError = "Please select what you would like to compare."
          valid = false
        }

        if(valid) {
          this.loading = true
          this.apiPost({
            comparison: this.comparison
          }, '/comparison/update', (comparison) => {
            this.loading = false
            this.$modal.hide('comparison-modal')
            EventBus.$emit('get-comparisons')
            EventBus.$emit('notification-event', {
              'type'   : 'success', 
              'message': 'Comparison saved successfully.'
            })
          })
        }
      },

      deleteComparison() {
        this.$modal.show('confirm-modal', {
          // width : 600,
          title  : "Please confirm your action",
          message: "Are you sure you want to delete this comparison and all of its data?",
          buttons:[{
            label          : 'Yes',
            color          : 'red',
            loadingViaLocal: true, // a bit of HACK, we want to show loading image when button is clicked, but hard to pass loading prop to a modal
            action         : () => {
              
              this.apiDelete({
                comparisonId: this.comparison.id
              }, '/comparison/delete', () => {
                this.$modal.hide('confirm-modal')
                this.$modal.hide('comparison-modal')
                EventBus.$emit('get-comparisons')
                EventBus.$emit('notification-event', {
                  'type'   : 'success',
                  'message': "Comparison deleted successfully"
                })
              })
            }
          }, {
            label : 'No',
            color : 'gray',
            action: () => {
              this.$modal.hide('confirm-modal')
            }
          }]
        })
      },
    }
  }
</script>