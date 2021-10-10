<template>
	<a href="#" v-on:click.prevent="$modal.show('confirm-registration-modal')" 
		class="fixed w-full flex justify-center" style="bottom:0px;">
		<div class="w-1/3 text-center bg-red-500 p-3 text-white text-xs font-semibold flex items-center justify-center" 
			style="border-radius: 5px 5px 0 0;">
			<img src="https://img.icons8.com/cotton/50/000000/warning-shield.png" class="inline w-5 mr-1" />
			Please confirm your email. Some features will not work otherwise.
		</div>
	</a>
</template>