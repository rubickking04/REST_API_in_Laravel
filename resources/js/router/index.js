import { createRouter, createWebHistory } from 'vue-router'
const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('../components/pages/home.vue'),
            meta: {
                requiresAuth: true,
            }
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('../components/pages/auth/login.vue'),
            meta: {
                guest: true
            }
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('../components/pages/auth/register.vue'),
            meta: {
                guest: true
            }
        },
        {
            path: '/:pathMatch(.*)*',
            component: () => import('../components/404NotFound.vue')
        }
    ]
})
router.beforeEach((to,from) => {
    if (to.meta.requiresAuth && !localStorage.getItem('token')) {
        return {
            name: 'login',
        }
    }
    if (to.meta.requiresAuth  == false && localStorage.getItem('token')) {
        return {
            name: 'home',
        }
    }
    if (to.meta.guest && localStorage.getItem('token') !== null) {
        return {
            name: 'home',
        }
    }
})
export default router
