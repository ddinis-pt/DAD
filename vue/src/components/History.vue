<template>
    <div class="min-h-screen grid content-between bg-gray-800">
        <Header></Header>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col space-y-8">
                <div class="flex flex-col gap-y">
                    <h1 class="text-2xl font-bold text-white text-center">History</h1>
                    <div v-if="user">
                        <p class="text-white">This is the history of your games. You can see the games you
                            have played here.</p>
                        <div class="overflow-y-auto max-h-96">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Mode</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date - Time</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Score</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="game in games" :key="game.id">
                                        <td v-if="game.type == 'S'"
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                                getBoardName(game.board_id) }}</td>
                                        <td v-else class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                            getBoardName(game.board_id) }} (Multiplayer)</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ game.ended_at
                                            }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ game.total_time
                                            }}s {{ getTurns(game.custom) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div v-else>
                        <span class="text-white">You need to be </span>
                        <RouterLink :to="{name: 'login'}" class="text-blue-600 hover:underline">logged in </RouterLink>
                        <span class="text-white">to see your history.</span>
                    </div>
                </div>
                <button @click.prevent="goBack" type="button"
                    class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                    Go Back
                </button>
            </div>
        </main>
        <Footer></Footer>
    </div>
</template>
<script setup>
import Header from '@/components/ui/Header.vue'
import Footer from '@/components/ui/Footer.vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import axios from 'axios'
import { onMounted, ref } from 'vue'

const router = useRouter()
const authStore = useAuthStore()

const games = ref([])

const user = authStore.user

const goBack = async () => {
    router.push({ name: 'dashboard' })
}

const getBoardName = (id) => {
    switch (id) {
        case 1:
            return '3x3'
        case 2:
            return '4x4'
        case 3:
            return '5x5'
        default:
            return 'Unknown'
    }
}

const getHistory = async () => {
    if (!user) {
        return
    }
    try {
        const response = await axios.get('/games/user/' + user.id)
        games.value = response.data
    } catch (error) {
        console.error(error)
    }
}

const getTurns = (custom) => {
    if (!custom) {
        return '';
    }

    const obj = JSON.parse(custom)
    return ' - ' + obj.nJogadas + ' turns'
}

onMounted(() => {
    getHistory()
})
</script>