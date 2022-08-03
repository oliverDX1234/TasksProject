import {createWebHistory, createRouter} from "vue-router";
import state from "../store";

import Register from '../pages/Register';
import Tasks from "../Pages/Tasks/Tasks"
import AddTask from "../Pages/Tasks/AddTask";

export const routes = [
    {
        name: 'home',
        path: '/',
        component: () => import("../Pages/Home"),
        meta: {
            requiresAuth: false,
        },
    },
    {
        name: 'register',
        path: '/register',
        component: Register,
        meta: {
            requiresAuth: false,
        },
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: () => import("../Pages/DashBoard"),
        meta: {
            requiresAuth: true,
        },
    },
    {
        name: 'tasks',
        path: '/tasks',
        component: Tasks,
        meta: {
            requiresAuth: true,
        },
    },

    {
        name: 'add-task',
        path: '/add-task',
        component: AddTask,
        meta: {
            requiresAuth: true,
        },
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => {
        return record.meta && record.meta.requiresAuth
    })) {

        if (state.getters['authentication/isAuthenticated']) {

            return next();
        } else {
            return next('/');
        }
    }

    next();
})

export default router;
