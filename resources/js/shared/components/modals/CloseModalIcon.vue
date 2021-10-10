<template>
  <a href="#"
    v-on:click.prevent="$modal.hide(modalName)"
    class="text-gray-700 hover:text-gray-600">
    <svg width="18" height="18" stroke-width="2" stroke="currentColor" fill="none">
      <use xlink:href="/img/feather-sprite.svg#x-circle"/>
    </svg>
  </a>
</template>

<script>
	export default {
		props : {
			modalName : {
				type    : String,
				required: true
			}
		}
	}
</script>