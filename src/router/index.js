import { createRouter, createWebHistory } from 'vue-router'
import PRIJAVI from '../components/PRIJAVI.vue'
import PRATITE from '../components/PRATITE.vue'
import OCJENJIVANJE from '../components/OCJENJIVANJE.vue'
import LoginForm from '../components/LoginForm.vue';
import MainPage from '../components/MainPage.vue';

const routes = [
  {
    path: '/prijavi',
    name: 'prijavi',
    component: PRIJAVI
  },
  {
    path: '/pratite',
    name: 'pratite',
    component: PRATITE
  },
  {
    path: '/ocjenjivanje',
    name: 'ocjenjivanje',
    component: OCJENJIVANJE
  },
  {
        path: '/',
        name: 'Login',
        component: LoginForm
    },
    {
        path: '/main',
        name: 'MainPage',
        component: MainPage,
        meta: {
            requiresAuth: true
        }
    },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        const user = localStorage.getItem('user');
        if (!user) {
            next('/'); // Ako nije prijavljen, preusmjeri na login
        } else {
            next(); // Ako je prijavljen, nastavi na MainPage
        }
    } else {
        next(); // Ako ruta ne zahtijeva autentifikaciju, dozvoliti pristup
    }
});

export default router
