<template>
    <div class="min-h-screen grid">
        <Header></Header>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col space-y-8">
                <div class="flex flex-col gap-y">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">About</h1>
                    <p class="text-gray-800 dark:text-white">This is a simple game that you can play with your friends.
                        You can create a room and invite your friends to join. You can also join a room that your friend
                        has created. The game is simple, you will be given a word and you have to draw it. Your friends
                        will have to guess the word based on your drawing. The game is timed, so you have to be quick.
                        The person who guesses the word correctly will get a point. The person with the most points at
                        the end of the game wins.</p>
                </div>
                <button @click.prevent="logout" type="button"
                    class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                    Go Back
                </button>
            </div>
        </main>
    </div>
</template>
<script setup>
import Header from '@/components/ui/Header.vue'
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const router = useRouter()
const authStore = useAuthStore()

const logout = async () => {
    if(authStore.user) {
        await authStore.logout()
    }
    router.push({name: 'login'})
}

</script>