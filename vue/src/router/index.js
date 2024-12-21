import Login from '@/components/auth/Login.vue'
import DashboardComponent from '@/components/Dashboard.vue'
import LaravelTester from '@/components/LaravelTester.vue'
import WebSocketTester from '@/components/WebSocketTester.vue'
import Gamemode from '@/components/Gamemode.vue'
import About from '@/components/About.vue'
import Game3x4 from '@/components/Game3x4.vue'
import Game4x4 from '@/components/registered/Game4x4.vue'
import Game6x6 from '@/components/registered/Game6x6.vue'
import History from '@/components/History.vue'
import BuyCoins from '@/components/registered/BuyCoins.vue'
import LeaderBoard from '@/components/Leaderboard.vue'
import Settings from '@/components/registered/Settings.vue'
import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import MultiplayerArea from '@/components/ui/game/MultiplayerArea.vue'
import Register from '@/components/auth/Register.vue'
import DeleteAccount from '@/components/registered/DeleteAccount.vue'
import Users from '@/components/admin/Users.vue'
import EditUser from '@/components/admin/EditUser.vue'
import CreateAdminAccount from '@/components/admin/CreateAdminAccount.vue'
import Transactions from '@/components/admin/Information.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: Login
    },
    {
      path: '/register',
      name: 'register',
      component: Register
    },
    {
      path: '/deleteAccount',
      name: 'deleteAccount',
      component: DeleteAccount
    },
    {
      path: '/multiplayer',
      name: 'multiplayer',
      component: MultiplayerArea
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
      path: '/history',
      name: 'history',
      component: History
    },
    {
      path: '/buy-coins',
      name: 'buy-coins',
      component: BuyCoins
    },
    {
      path: '/leaderboard',
      name: 'leaderboard',
      component: LeaderBoard
    },
    {
      path: '/settings',
      name: 'settings',
      component: Settings
    },
    {
      path: '/users',
      name: 'users',
      component: Users,
    },
    {
      path: '/users/edit/:id',
      name: 'edit-user',
      component: EditUser 
    },
    {
      path: '/users/create',
      name: 'create-user',
      component: CreateAdminAccount
    },
    {
      path: '/information',
      name: 'information',
      component: Transactions
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

let handlingFirstRoute = true

router.beforeEach(async (to, from, next) => {
  const storeAuth = useAuthStore()
  if (handlingFirstRoute) {
    handlingFirstRoute = false
    await storeAuth.restoreToken()
  }
  next()
})

export default router
