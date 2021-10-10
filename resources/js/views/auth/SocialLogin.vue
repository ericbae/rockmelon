<template>
	<div class="">
	  <div class="mt-3 flex flex-wrap -mx-2">
	  	<div class="w-full px-2">
		    <a :href="getAppUrl + '/twitter/connect/g?siteId=-1'" 
		    	class="block bg-white border border-blue-500 p-3 rounded text-blue-500 flex items-center hover:bg-blue-100">
		    	<img src="https://img.icons8.com/color/96/000000/twitter-circled.png" class="inline w-6" />
		      <span class="text-sm ml-4">Twitter</span>
		    </a>
		  </div>

	  	<!-- <div class="w-1/2 px-2" v-if="getEnv == 'local'">
		    <a :href="getAppUrl + '/linkedin/connect/a'" 
		    	class="block bg-white border border-blue-700 p-3 rounded text-blue-700 flex items-center hover:bg-indigo-100">
		    	<img src="https://img.icons8.com/color/96/000000/linkedin-circled.png" class="inline w-6" />
		      <span class="text-sm ml-4">LinkedIn</span>
		    </a>
		  </div> -->

		  <!-- <div class="w-1/2 px-2">
		    <a :href="getAppUrl + '/facebook/connect/s?siteId=' + site.id" 
		    	class="block bg-blue-100 border border-blue-500 p-3 rounded text-blue-800 flex items-center hover:bg-blue-200">
		    	<img src="https://img.icons8.com/color/96/000000/facebook-circled.png" class="inline w-6" />
		      <span class="text-sm ml-4">Facebook</span>
		    </a>
		  </div> -->
	  </div>

	  <!-- <div class="mt-3">
	    <a href="/github/connect" class="block bg-gray-700 py-3 px-6 w-full rounded text-white flex items-center hover:bg-gray-800">
	      <img src="https://cdn0.iconfinder.com/data/icons/free-social-media-set/24/github-256.png" class="inline w-8" />
	      <span class="text-sm font-semibold ml-4">GitHub</span>
	    </a>
	  </div>

	  <div class="mt-3">
	    <a href="/producthunt/connect/auth" class="block bg-orange-600 py-3 px-6 w-full rounded text-white flex items-center hover:bg-orange-700">
	      <img src="https://cdn0.iconfinder.com/data/icons/social-circle-3/72/ProductHunt-256.png" class="inline w-8" />
	      <span class="text-sm font-semibold ml-4">ProductHunt</span>
	    </a>
	  </div> -->
	</div>
</template>

<script>
	export default {
		data() {
			return {
				site : window.currSite
			}
		}
	}
</script>