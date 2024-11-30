<script>
import { onMounted, useTemplateRef, provide } from 'vue'
import { RouterView } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

//const storeAuth = useAuthStore()

const logoutConfirmed = () => {
  storeAuth.logout()
}

</script>

<template>
    <RouterView />
</template>