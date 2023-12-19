import { createRouter, createWebHistory } from 'vue-router';
import LoginComponent from '@/components/LoginComponent.vue';
import LogoutComponent from '@/components/LogoutComponent.vue';
import DashboardComponent from '@/components/DashboardComponent.vue';
import ForgotPasswordComponent from '@/components/ForgotPasswordComponent.vue';
import EmailVerificationComponent from '@/components/EmailVerificationComponent.vue';
import WebSocketStream from '@/components/WebSocketComponent.vue';
import store from '@/store';

const routes = [
    { path: '/login', component: LoginComponent },
    { path: '/dashboard', component: DashboardComponent, meta: { requiresAuth: true }},
    { path: '/logout', component: LogoutComponent, meta: { requiresAuth: true }},
    { path: '/forgot-password', component: ForgotPasswordComponent},
    { path: '/email-verification', component: EmailVerificationComponent, meta: { requiresAuth: true }},
    { path: '/market-watch', component: WebSocketStream, meta: { requiresAuth: true }},
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
