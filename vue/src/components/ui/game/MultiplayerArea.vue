<script setup>
import Header from '@/components/ui/Header.vue'
import Chat from '@/components/ui/chat/Chat.vue'
import Lobby from '@/components/ui/game/Lobby.vue'
import Games from '@/components/ui/game/Games.vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
</script>
<template>
  <div class="min-h-screen bg-sky-50 dark:bg-gray-800">
    <Header></Header>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 content-top min-w-full items-center">
      <div class="flex gap-4 justify-center">
        <Chat class="w-72 bg-white dark:bg-slate-900"></Chat>
        <Lobby v-if="authStore.user" class="w-72 bg-white dark:bg-slate-900"></Lobby>
        <Games v-if="authStore.user"></Games>
      </div>
    </main>
  </div>
</template>