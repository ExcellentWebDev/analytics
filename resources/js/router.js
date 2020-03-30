import Vue from 'vue'
import Router from 'vue-router'
import Login from './components/Login.vue'
import Search from './components/Search.vue'
import Users from './components/Users.vue'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Login',
      component: Login
    },
    {
      path: '/login',
      redirect: { name: 'Login' }
    },
    {
      path: '/search',
      name: "Search",
      component: Search
    },
    {
      path: '/license',
      name: "License",
      component: Users
    },
  ]
})
