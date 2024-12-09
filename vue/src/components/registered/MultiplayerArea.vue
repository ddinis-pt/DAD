<script setup>
import Chat from '@/components/ui/chat/Chat.vue'
import { useAuthStore} from '@/stores/auth'

const storeAuth = useAuthStore()
</script>

<template>
  <div class="flex flex-wrap justify-start space-x-4 m-8">
    <Chat class="w-72"></Chat>
    <Chat class="w-72"></Chat>
  </div>
</template>