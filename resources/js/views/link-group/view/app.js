require('@/bootstrap');

import Vue 					from 'vue'
import Tooltip 			from 'vue-directive-tooltip'
import mixins 			from "@/shared/mixins"
import VModal     	from 'vue-js-modal'
import VueCookies 	from 'vue-cookies'
import VueClipboard from 'vue-clipboard2'

Vue.use(Tooltip)
Vue.use(VModal, { dialog : true})
Vue.mixin(mixins)
Vue.use(VueCookies)
Vue.use(VueClipboard)

Vue.component('link-group-view', require('./Home.vue').default)

const app = new Vue({
	el: '#app',
});