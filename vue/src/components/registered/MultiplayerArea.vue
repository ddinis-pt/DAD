<script setup>
import Header from '@/components/ui/Header.vue'
import Chat from '@/components/ui/chat/Chat.vue'
import Lobby from '@/components/registered/Lobby.vue'
import Games from '@/components/registered/Games.vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
</script>


<template>

  <div class="min-h-screen content-between bg-gray-800">
    <Header></Header>
    <main class="flex flex-wrap justify-start space-x-4">
      <Chat class="bg-white w-72 h-72"></Chat>
      <!-- Lobby area content goes here -->
      <Lobby v-if="authStore.user" class="w-72 bg-white"></Lobby>
      <Games v-if="authStore.user" class="flex-1 min-w-96 bg-white"></Games>
    </main>
  </div>
</template>