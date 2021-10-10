require('@/bootstrap');

import Vue 				from 'vue'
import gMixins 		from "@/shared/mixins"
import VModal     from 'vue-js-modal'

Vue.use(VModal, { dialog : true})
Vue.mixin(gMixins)

Vue.component('contact-us', require('./Home.vue').default);

const app = new Vue({
	el   : '#contact-us-app',
	props: ['value'],
});