
import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import dashboard from './components/admin/dashboard'
import tags from './components/admin/tags'
import category from './components/admin/category'

const routes = [
    {
        path: '/',
        component: dashboard,
        name: 'dashboard'
    },
    {
        path: '/category',
        component: category,
        name: 'category'
    },
    {
        path: '/manage-tag',
        component: tags,
        name: 'tags'
    },
   
]

export default new Router({
    mode: 'history',
    routes
})