import { createRouter, createWebHistory } from 'vue-router'
import PRIJAVI from '../components/PRIJAVI.vue'
import PRATITE from '../components/PRATITE.vue'
import OCJENJIVANJE from '../components/OCJENJIVANJE.vue'

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
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
