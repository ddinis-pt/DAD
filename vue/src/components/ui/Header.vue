<script setup>
import router from '@/router';
import { useAuthStore } from '@/stores/auth'
const authStore = useAuthStore()

const isUser = authStore.user

const back = () => {
    router.push({ name: 'dashboard' })
}
</script>
<template>
    <!-- ========== HEADER ========== -->
    <header class="flex flex-col z-50 w-full text-sm max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
        <nav class="mt-4 relative w-full rounded-[2rem] py-4 px-8 
              items-center flex justify-center">
            <div>
                <img @click.prevent="back" class="flex-none inline-block h-16" src="../../assets/cards-light.png"
                    alt="App Logo" />
            </div>
            <!-- End Logo -->

        </nav>
    </header>
    <!-- ========== END HEADER ========== -->
</template>