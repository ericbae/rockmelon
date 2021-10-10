<template>
  <div class="">
    <div class="text-sm">
      <span v-if="label != null" class="mr-4">{{ label }}</span>
      <span v-if="maxSize" class="text-xs text-grey-darker">Max. size : {{ maxSizeLabel }}</span>
      <span v-if="allowedTypes != null && allowedTypes.length > 0" class="ml-4 text-xs text-grey-darker">Allowed types : {{ allowedTypes.join(", ") }}</span>
      </span>
    </div>

    <div class="mt-3 relative">
      <template v-if="localImage != null">
        <img :src="localImage" style="max-width:150px;" class="border-8" />
      </template>

      <template v-else>
        <label 
          :for="'input-upload-' + propertyKey" 
          style="width:150px;height:150px;" 
          class="block border-8 text-center cursor-pointer">
          <div class="text-grey-dark text-xs" style="margin-top:50px;">
            No image uploaded. <br/> Click to upload.
          </div>
        </label>
      </template>

      <div v-if="loading" style="position:absolute;top:50px;margin-left:60px;">
        <img src="/img/ajax-loader.gif" />
      </div>
    </div>

    <div v-if="localImage != null" class="mt-2">
      <a href="#" v-on:click.prevent="deleteFile()" 
        class="no-underline text-xs uppercase font-semibold text-grey-darker hover:underline hover:text-black">Delete</a>

      &sdot;

      <label 
        :for="'input-upload-' + propertyKey" 
        class="no-underline text-xs uppercase font-semibold text-grey-darker hover:underline hover:text-black">Replace</label>
    </div>

    <input 
      class="hidden"
      :id="'input-upload-' + propertyKey" 
      :ref="'inputFile' + propertyKey"
      type="file" v-on:change="uploadFile($event)" />
  </div>
</template>

<script>
  import axios from "axios"

  export default {

    props : {

      label : {
        type : String,
        required : false
      },

      image : {
        type : String,
        required : false
      },

      propertyKey : {
        // type : String,
        required : false
      },

      maxSize : {
        type : String,
        required : false
      },

      maxSizeLabel : {
        type : String,
        required : false
      },

      allowedTypes : {
        type    : Array,
        required: false
      },

      getUploadedImage : {
        type    : Function,
        required: false
      },
    },

    data() {
      return {
        localImage: this.image,
        loading   : false
      }
    },

    methods : {
      uploadFile($event) {
        let file     = $event.target.files[0] || $event.dataTransfer.files[0]
        let formData = new FormData()
        formData.append('file', file)
        formData.append('maxSize', this.maxSize)
        formData.append('maxSizeLabel', this.maxSizeLabel)
        formData.append('allowedTypes', this.allowedTypes)
        formData.append('glossaryId', this.glossaryId)
        
        if(this.termId != null) {
          formData.append('termId', this.termId)  
        }
        
        this.loading = true

        axios.post(process.env.BACKEND_HOST + '/api/common/upload-file', formData, {
          headers: {
            'Content-Type'  : 'multipart/form-data',
            'Authorization' : 'Bearer ' + localStorage.getItem('gb-jwt')
          }
        }).then(response => {
          this.loading    = false
          this.localImage = response.data
          this.$refs['inputFile' + this.propertyKey].value = null
          this.getUploadedImage(response.data, this.propertyKey)
        }).catch(e => {
          this.loading = false
          // console.log(e)
        })
      },

      deleteFile() {
        this.$refs.simplert.openSimplert({
          title               : "Are you sure you want to delete this?",
          // message          : 'Are you sure you want to delete this?',
          type                : 'info',
          useConfirmBtn       : true,
          customConfirmBtnText: 'Yes',
          customCloseBtnText  : 'Cancel',
          onConfirm           : () => {
            this.loading = true

            axios.delete(process.env.BACKEND_HOST + '/api/common/delete-file', {
              params : {
                fileUrl : this.localImage
              }, headers: {
                'Authorization' : 'Bearer ' + localStorage.getItem('gb-jwt')
              }
            }).then(response => {
              this.loading    = false
              this.localImage = null
              this.$refs['inputFile' + this.propertyKey].value = null
              this.getUploadedImage(null, this.propertyKey)
            }).catch(e => {
              this.loading = false
            })
          }
        })
      },
    }
  }
</script>