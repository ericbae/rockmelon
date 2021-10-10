require('@/bootstrap')

import Vue 									from 'vue'
// import VueRouter 						from 'vue-router'
import Tooltip 							from 'vue-directive-tooltip'
import mixins 							from "@/shared/mixins"
// import vSelect 							from 'vue-select'
// import Routes 							from './routes'
import VModal     					from 'vue-js-modal'
// import VueCookies 					from 'vue-cookies'
// import 'vue-select/dist/vue-select.css'

// Vue.use(VueRouter)
Vue.use(Tooltip)
Vue.use(VModal, { dialog : true})
Vue.mixin(mixins)
// Vue.component('v-select', vSelect)
// Vue.use(VueCookies)

// const router = new VueRouter({
//   // mode  : window.env == 'production' ? 'history' : 'hash',
//   mode  : 'hash',
//   routes: Routes,
// 	scrollBehavior (to, from, savedPosition) {
// 		return { x: 0, y: 0 }
// 	}
// })

Vue.component('user-account', require('./Home.vue').default)

const app = new Vue({
	el    : '#app',
	// router: router
})