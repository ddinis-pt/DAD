<script setup>
import { useAuthStore } from '@/stores/auth';
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter()
const authStore = useAuthStore()

const logout = async () => {
    if (authStore.user) {
        await authStore.logout()
    }
    router.push({ name: 'login' })
}

onMounted(() => {
    document.title = 'Memory Card Game - About'
})

</script>
<template>
    <div class="min-h-screen bg-sky-50 dark:bg-gray-800">
        <main class="mx-auto px-6 sm:px-6 lg:px-8 py-8 max-w-lg">
            <div class="flex items-center justify-center bg-gray-800 rounded-xl py-2">
                <img class="flex-none inline-block h-8" src="../assets/cards-light.png" alt="The Memory Game Logo" />
            </div>
            <div class="flex flex-col space-y-8">
                <div class="flex flex-col gap-y">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white text-center py-4">The Memory Game</h1>
                    <div class="flex justify-center dark:bg-white p-4 rounded-lg">
                        <svg width="159" height="151" viewBox="0 0 159 151" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="2" y="52.0205" width="32.9568" height="44.5096" rx="3" fill="#1F2937"
                                stroke="#1F2937" stroke-width="4" stroke-linecap="round" />
                            <rect x="42.6809" y="52.8679" width="32.9568" height="44.5096" rx="3" fill="#2563EB"
                                stroke="#1F2937" stroke-width="4" stroke-linecap="round" />
                            <rect x="2" y="52.0205" width="32.9568" height="44.5096" rx="3" fill="#EAB308"
                                stroke="#1F2937" stroke-width="4" stroke-linecap="round" />
                            <rect x="83.3621" y="52.0205" width="32.9568" height="44.5096" rx="3" fill="#1F2937"
                                stroke="#1F2937" stroke-width="4" stroke-linecap="round" />
                            <rect x="124.043" y="52.8679" width="32.9568" height="44.5096" rx="3" fill="#1F2937"
                                stroke="#1F2937" stroke-width="4" stroke-linecap="round" />
                            <rect x="83.3621" y="52.8679" width="32.9568" height="44.5096" rx="3" fill="#1F2937"
                                stroke="#1F2937" stroke-width="4" stroke-linecap="round" />
                            <rect x="42.6809" y="103.736" width="32.9568" height="44.5096" rx="3" fill="#1F2937"
                                stroke="#1F2937" stroke-width="4" stroke-linecap="round" />
                            <rect x="2" y="103.736" width="32.9568" height="44.5096" rx="3" fill="#1F2937"
                                stroke="#1F2937" stroke-width="4" stroke-linecap="round" />
                            <rect x="124.043" y="103.736" width="32.9568" height="44.5096" rx="3" fill="#1F2937"
                                stroke="#1F2937" stroke-width="4" stroke-linecap="round" />
                            <rect x="83.3621" y="103.736" width="32.9568" height="44.5096" rx="3" fill="#2563EB"
                                stroke="#1F2937" stroke-width="4" stroke-linecap="round" />
                            <rect x="2" y="2" width="32.9568" height="44.5096" rx="3" fill="#1F2937" stroke="#1F2937"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="42.6809" y="2" width="32.9568" height="44.5096" rx="3" fill="#1F2937"
                                stroke="#1F2937" stroke-width="4" stroke-linecap="round" />
                            <rect x="2" y="2" width="32.9568" height="44.5096" rx="3" fill="#1F2937" stroke="#1F2937"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="83.3621" y="2" width="32.9568" height="44.5096" rx="3" fill="#1F2937"
                                stroke="#1F2937" stroke-width="4" stroke-linecap="round" />
                            <rect x="124.043" y="2" width="32.9568" height="44.5096" rx="3" fill="#1F2937"
                                stroke="#1F2937" stroke-width="4" stroke-linecap="round" />
                            <rect x="83.3621" y="2" width="32.9568" height="44.5096" rx="3" fill="#FACC15"
                                stroke="#1F2937" stroke-width="4" stroke-linecap="round" />
                        </svg>
                    </div>
                    <p class="text-gray-800 dark:text-white pt-5">
                        Users are presented with a series of cards, that they must remember after a brief look. They must pick every pair of cards, with the least amount of turns and within the least amount of time possible.
                        This game is often used to train cognitive thinking, to enhance memory skills.
                    </p>
                    <p class="text-gray-800 dark:text-white py-4">This game comes in two modes: single-player and multi-player
Single-player - the user must find all pairs within the least amount of time, with the least amount of turns
Multi-player - two users will be against each other, in a race to find the most pairs. In case of a tie, the user with the least amount of time and the least amount of turns wins </p>
                </div>
                <button @click.prevent="logout" type="button"
                    class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                    Go Back
                </button>
            </div>
        </main>
    </div>
</template>