import { createRouter, createWebHistory } from 'vue-router';
import LoginComponent from '@/components/LoginComponent.vue';
import DashboardComponent from '@/components/DashboardComponent.vue';
import store from '@/store';

const routes = [
    { path: '/login', component: LoginComponent },
    { path: '/dashboard', component: DashboardComponent, meta: { requiresAuth: true }}
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!store.getters['login/isAuthenticated']) {
            next({ path: '/login' });
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;
