import {createWebHistory, createRouter} from "vue-router";

import Home from '../pages/Home';
import Register from '../pages/Register';
import Dashboard from '../pages/Dashboard';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

router.beforeEach((to, from, next) => {

    if (window.Laravel.isLoggedin) {
        return next('dashboard');
    }
    next();
})

export default router;
