<template>
  <button
    v-on:click="onClickLocal"
    :class="{ 
    	'text-white px-6 py-4 font-bold focus:outline-none text-base' : true,
    	'rounded-lg' : isRounded,
    	'w-full' : isFullWidth,
    	'uppercase' : isUppercase,
    	'bg-blue-500 hover:bg-blue-600' : color == 'blue', 
    	'bg-red-500 hover:bg-red-600' : color == 'red',
    	'bg-green-600 hover:bg-green-700' : color == 'green',
    	'bg-gray-500 hover:bg-gray-600' : color == 'gray',
    	'bg-black' : color == 'black'
    }">
    <template v-if="loading || localLoading "><img src="/img/ajax-loader.gif" class="inline" /></template>
    <template v-else>{{ label }}</template>
  </button>
</template>

<script>
	export default {
		props : {
			onClick : {
				type    : Function,
				required: false
			},

			loading : {
				type    : Boolean,
				required: false
			},

			loadingViaLocal : {
				type    : Boolean,
				required: false
			},

			label : {
				type    : String,
				required: false,
				default : "Save"
			},

			color : {
				type    : String,
				required: false,
				default : "blue"
			},

			isFullWidth : {
				type    : Boolean,
				required: false,
				default : false
			},

			isRounded : {
				type    : Boolean,
				required: false,
				default : false
			},

			isUppercase : {
				type    : Boolean,
				required: false,
				default : false
			}
		},

		data() {
			return {
				localLoading : false
			}
		},

		methods : {
			onClickLocal() {
				if(this.loadingViaLocal) {
					this.localLoading = true
				}
				this.onClick()
			}
		}
	}
</script>