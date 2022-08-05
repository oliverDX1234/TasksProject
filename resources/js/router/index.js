import {createWebHistory, createRouter} from "vue-router";
import state from "../store";

import Register from '../pages/Register';
import Tasks from "../Pages/Tasks/Tasks"
import Task from "../Pages/Tasks/Task";

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
        name: 'tasks',
        path: '/tasks',
        component: Tasks,
        meta: {
            requiresAuth: true,
        },
    },

    {
        name: 'edit-task',
        path: '/edit-task/:id',
        component: Task,
        meta: {
            requiresAuth: true,
        },
    },

    {
        name: 'add-task',
        path: '/add-task',
        component: Task,
        meta: {
            requiresAuth: true,
        },
    },

    {
        path: "/:catchAll(.*)",
        component: () => import("../pages/404")
    }

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
    } else if (to.matched.some((record) => {
        return record.meta && !record.meta.requiresAuth && state.getters['authentication/isAuthenticated']
    })) {
        if (to.path === "/") {
            return next("/tasks");
        }
    }

    next();
})

export default router;
