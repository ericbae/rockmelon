require('@/bootstrap');

import Vue 							from 'vue'
import Login 						from './Login.vue'
import ForgotPassword 	from './ForgotPassword.vue'
import Register 				from './Register.vue'
import ResetPassword		from './ResetPassword.vue'
import mixins 					from "@/shared/mixins"

Vue.mixin(mixins)

const app = new Vue({
	el        : '#app',
	components: { 
		'auth-login'          : Login,
		'auth-forgot-password': ForgotPassword,
		'auth-register'       : Register,
		'auth-reset-password' : ResetPassword,
	}
});