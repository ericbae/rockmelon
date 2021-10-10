<template>
	<div class="text-center">
    <img :src="user.profile_image" class="border inline rounded-full w-16" />
    
    <div class="mt-3 text-center" v-if="getCurrUser.id == user.id">
      <a href="#" v-on:click.prevent="editProfileImage" 
        class="text-gray-500 text-xs hover:text-gray-800 hover:underline">Edit</a>
    </div>

    <ProfileImageModal />
  </div>
</template>

<script>

	import ProfileImageModal from '@/shared/components/modals/ProfileImageModal'

	export default {
		props : {
			user : {
				type    : Object,
				required: true
			}
		},

		components : {
			ProfileImageModal
		},

		methods : {
      editProfileImage() {
        this.$modal.show('image-modal', {
          addImage: this.addImage,
          userId  : this.user.id != null ? this.user.id : null,
        })
      },

      addImage(image) {
        this.apiPost({
          userId: this.user.id,
          image : image
        }, '/user/profile-image', () => {
          this.user.profile_image = image
        })
      },
		}
	}
</script>