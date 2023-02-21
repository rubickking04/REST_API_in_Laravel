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
router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const guest = to.matched.some(record => record.meta.guest);
    const isLoggedIn = localStorage.getItem('token');
    if (requiresAuth && !isLoggedIn) {
        next({ name: 'login' });
    } else if (guest && isLoggedIn) {
        next({ name: 'home' });
    } else {
        next();
    }
});
export default router
