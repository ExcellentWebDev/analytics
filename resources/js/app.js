// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App.vue'
import store from './store.js'
import router from './router.js'

import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import VueToastr2 from 'vue-toastr-2'
import 'vue-toastr-2/dist/vue-toastr-2.min.css'
import "../css/main.css"
import "../css/line-awesome.min.css"
import Vue2Filters from 'vue2-filters'

Vue.config.productionTip = false
window.toastr = require('toastr')
var toastrConfig = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": true,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};
Vue.use(BootstrapVue)
Vue.use(VueToastr2, toastrConfig)
Vue.use(Vue2Filters)

router.beforeEach((to, from, next) => {
  if (to.name !== "Login") {
    if (localStorage.getItem('email') !== null) {
      next();
    } else {
      next({name: 'Login'})
    }
  } else {
    next();
  }
})

/* eslint-disable no-new */
new Vue({
  el: '#app',
  store,
  router,
  components: { App },
  template: '<App/>'
})
