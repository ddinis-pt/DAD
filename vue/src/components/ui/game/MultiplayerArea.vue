<script setup>
import Header from '@/components/ui/Header.vue'
import Chat from '@/components/ui/chat/Chat.vue'
import Lobby from '@/components/ui/game/Lobby.vue'
import Games from '@/components/ui/game/Games.vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
</script>
<template>
  <div class="min-h-screen content-between bg-gray-800">
    <Header></Header>
    <main class="flex flex-start justify-start space-x-4">
      <Chat class="w-72 bg-white"></Chat>
      <Lobby v-if="authStore.user" class="w-72 bg-white"></Lobby>
      <Games v-if="authStore.user" class="bg-white"></Games>
    </main>
  </div>
</template>