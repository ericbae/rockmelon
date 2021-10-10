<template>
  <modal 
    name="image-modal" 
    :pivotY="0.2"
    :height="'auto'"
    :width="width"
    :adaptive="true"
    :scrollable="true"
    @opened="opened"
    @before-open="beforeOpen">

    <div class="modal-header">
      <div class="modal-title">Add image</div>

      <a href="#"
        v-on:click.prevent="$modal.hide('image-modal')"
        class="text-gray-700 hover:text-gray-600">
        <svg width="18" height="18" stroke-width="2" stroke="currentColor" fill="none">
          <use xlink:href="/img/feather-sprite.svg#x-circle"/>
        </svg>
      </a>
    </div>
    
    <div class="p-6">

      <div class="mb-6 text-center bg-gray-200 p-4 rounded w-32" v-if="image != null && image.length > 0">
        <img :src="image" class="inline" />
      </div>

      <div class="">
        <label class="label-standard">
          Via URL
        </label>
      </div>

      <div class="form-line">
        <input type="text" class="input-standard" v-model="image" ref="url" />
      </div>

      <div class="label-line">
        <label class="label-standard">Or upload</label>
      </div>

      <div class="form-line">
        <input type="file" v-on:change="uploadImage($event)" />
      </div>

      <div class="button-line">
        <SubmitButton v-bind:onClick="submit" label="Submit" v-bind:loading="loading" />
      </div>
    </div>
  </modal>
</template>

<script>
  import { EventBus } from '@/shared/event-bus'
  import SubmitButton from '@/shared/components/form/SubmitButton'

  export default {

    components : {
      SubmitButton,
    },

    data() {
      return {
        image    : null,
        width    : 500,
        loading  : false,
        addImage : null,
        projectId: null,
      }
    },

    methods : {

      beforeOpen(event) {
        this.loading  = false
        this.addImage = event.params.addImage
        this.userId   = event.params.userId != null ? event.params.userId : null
        this.image    = null
      },

      opened(event) {
        this.$refs.url.focus()
      },

      uploadImage($event) {
        this.loading = true
        let file       = $event.target.files[0] || $event.dataTransfer.files[0]
        let formData   = new FormData()
        formData.append('file', file)
        formData.append('maxSize', '1000000')
        formData.append('maxSizeLabel', '1MB')
        formData.append('allowedTypes', ['png', 'gif', 'jpg'])

        axios.post('/project/image', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          }
        }).then(response => {
          this.loading = false
          this.image   = response.data.thumbnail
        }).catch(e => {
          this.loading = false
        })
      },

      submit() {
        if(this.image != null && this.image.length > 0) {
          this.addImage(this.image)
        }
          
        this.$modal.hide('image-modal')
      }
    }
  }
</script>