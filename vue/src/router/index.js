import Login from '@/components/auth/Login.vue'
import DashboardComponent from '@/components/Dashboard.vue'
import LaravelTester from '@/components/LaravelTester.vue'
import WebSocketTester from '@/components/WebSocketTester.vue'
import Gamemode from '@/components/Gamemode.vue'
import About from '@/components/About.vue'
import Game3x4 from '@/components/Game3x4.vue'
import Game4x4 from '@/components/registered/Game4x4.vue'
import Game6x6 from '@/components/registered/Game6x6.vue'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: Login
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardComponent
    },
    {
      path: '/modes',
      name: 'modes',
      component: Gamemode
    },
    {
      path: '/about',
      name: 'about',
      component: About
    },
    {
      path: '/testers',
      children: [
        {
          path: 'laravel',
          component: LaravelTester
        },
        {
          path: 'websocket',
          component: WebSocketTester
        }
      ]
    },
    {
      path: '/modes',
      children: [
        {
          path: '3by4',
          name: '3by4',
          component: Game3x4
        },
        {
          path: '4by4',
          name: '4by4',
          component: Game4x4
        },
        {
          path: '6by6',
          name: '6by6',
          component: Game6x6
        }
      ]
    }
  ]
})

export default router
