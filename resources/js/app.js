require('./bootstrap'); 
window.Vue = require('vue');

import Vue from 'vue'

// Iviewui setup
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
Vue.use(ViewUI);

import router from './router.js'
import vuex from './store.js'
import common from './common.js'
Vue.mixin(common);

// We can Access These Routes Using <routeName></routeName>
Vue.component('App', require('./components/App.vue').default);
Vue.component('Header', require('./components/Header.vue').default);
Vue.component('Footer', require('./components/Footer.vue').default);

Vue.component('login', require('./components/admin/login').default);


const app = new Vue({
el: '#app',
router,
vuex
});