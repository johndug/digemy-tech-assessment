import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../store/auth';
import LoginView from '../views/LoginView.vue';
import DashboardView from '../views/DashboardView.vue';
import NotFoundView from '../views/NotFoundView.vue';

const routes = [
    { path: '/', redirect: '/dashboard' },
    { path: '/login', name: 'Login', component: LoginView },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: DashboardView,
        meta: { requiresAuth: true }
    },
    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFoundView },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    const auth = useAuthStore();

    // Check if user is authenticated
    const isAuthenticated = auth.token !== null && auth.user !== null;

    // Redirect unauthenticated users to login
    if (to.meta.requiresAuth && !isAuthenticated) {
      return next({ name: 'Login' });
    }

    // Prevent logged-in users from going back to /login
    if (to.name === 'Login' && isAuthenticated) {
      return next({ name: 'Dashboard' });
    }

    next();
});

export default router;
