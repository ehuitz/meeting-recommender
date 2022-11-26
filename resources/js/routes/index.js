import { createRouter, createWebHistory } from "vue-router";

import AuthenticatedLayout from "../layouts/Authenticated";
import GuestLayout from "../layouts/Guest";
import SyncsCreate from '../components/Syncs/Create'
import BusiesIndex from '../components/Busies/Index'


import Login from '../components/Login'

function auth(to, from, next) {
    if (JSON.parse(localStorage.getItem('loggedIn'))) {
        next()
    }

    next('/login')
}

const routes = [
    {
        path: '/',
        redirect: { name: 'login' },
        component: GuestLayout,
        children: [
            {
                path: '/login',
                name: 'login',
                component: Login
            },
        ]
    },
    {
        component: AuthenticatedLayout,
        beforeEnter: auth,
        children: [
            
            {
                path: '/busies/upload',
                name: 'syncs.create',
                component: SyncsCreate,
                meta: { title: 'Upload Busy Text File' }
            },
            {
                path: '/busies',
                name: 'busies.index',
                component: BusiesIndex,
                meta: { title: 'Busies' }
            },
        ]
    }
]

export default createRouter({
    history: createWebHistory(),
    routes
})
