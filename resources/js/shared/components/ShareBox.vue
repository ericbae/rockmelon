<template>
	<div class="">
		<a :href="'https://twitter.com/intent/tweet?text=' + shareableTitle + '&url=https://www.sideprojectors.com/project/' + project.id + '/' + project.slug" 
		  rel="noopener" 
		  target="_blank"
		  class="mt-2 block flex items-center justify-between p-3 rounded border border-blue-700 hover:bg-blue-100">
		  <img src="https://cdn2.iconfinder.com/data/icons/social-icons-33/128/Twitter-512.png" class="w-4" />
		  <div class="text-blue-700 text-sm">Tweet this project</div>
		</a>

		<a :href="'https://www.facebook.com/dialog/share?app_id=279824422133476&display=popup&href=https://www.sideprojectors.com/project/' + project.id + '/' + project.slug" 
		  rel="noopener" 
		  target="_blank"
		  class="mt-2 block flex items-center justify-between p-3 rounded border border-blue-700 hover:bg-blue-100">
		  <img src="https://cdn1.iconfinder.com/data/icons/logotypes/32/facebook-512.png" class="w-4" />
		  <div class="text-blue-700 text-sm">Share this project</div>
		</a>

		<a :href="'https://www.linkedin.com/shareArticle?mini=true&url=https://www.sideprojectors.com/project/' + project.id + '/' + project.slug + '&title=' + shareableTitle + '&summary=' + shareableDescription + '&source=brokerpad.com.au'" 
		  rel="noopener" 
		  target="_blank"
		  class="mt-2 block flex items-center justify-between p-3 rounded border border-blue-700 hover:bg-blue-100">
		  <img src="https://cdn0.iconfinder.com/data/icons/typicons-2/24/social-linkedin-512.png" class="w-6" />
		  <div class="text-blue-700 text-sm">Recommend this project</div>
		</a>
	</div>
</template>

<script>
  import striptags  from "striptags"
  import he 				from "he"

	export default {
		
		props : {
			project : {
				type    : Object,
				required: true
			}
		},

    computed : {
      shareableTitle() {
        return encodeURIComponent('Check out ' + this.project.name + ' - ' + this.project.pitch + ' via ' + this.getAppName)
      },

      shareableDescription() {
        return encodeURIComponent(he.decode(striptags(this.project.description)))
      }
    },
	}
</script>