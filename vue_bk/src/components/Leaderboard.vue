<template>
    <div class="min-h-screen grid content-between">
        <Header></Header>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col space-y-8">
                <div class="flex flex-col gap-y">
                    <h1 class="text-2xl font-bold text-gray-800 text-white">Leaderboard</h1>
                    <p class="text-gray-800 text-white">This is the leaderboard. You can see the top players here.</p>
                    <div class="overflow-y-auto max-h-96">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mode</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date - Time</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Time</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="game in games" :key="game.id">
                                    <td v-if="game.type == 'S'"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                        getBoardName(game.board_id) }}</td>
                                    <td v-else class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                        getBoardName(game.board_id) }} (Multiplayer)</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                        game.created_user_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ game.ended_at }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ game.total_time }}s
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
import axios from 'axios'
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import { onMounted, ref } from 'vue';

const games = ref([])

const router = useRouter()
const authStore = useAuthStore()

const goBack = async () => {
    router.push({ name: 'dashboard' })
}

const getBoardName = (boardId) => {
    if (boardId === 1) {
        return '3x3'
    } else if (boardId === 2) {
        return '4x4'
    } else if (boardId === 3) {
        return '5x5'
    } else {
        return 'Unknown'
    }
}

const getGames = async () => {
    try {
        const response = await axios.get('/games')
        games.value = response.data
    } catch (error) {
        console.error(error)
    }
}

onMounted(() => {
    getGames()
    games.value.sort((a, b) => a.total_time - b.total_time)
})
</script>