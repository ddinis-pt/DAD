<template>
    <div class="min-h-screen grid content-between bg-gray-800">
        <Header></Header>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col space-y-8">
                <div class="flex flex-col gap-y">
                    <h1 class="text-2xl font-bold text-gray-800 text-white">Leaderboard</h1>
                    <p class="text-gray-800 text-white">This is the leaderboard. You can see the top players here.
                    </p>
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
                                        class="px-2 py-2 whitespace-nowrap text-sm text-gray-500 justify-center">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7.99996 8.66667C9.84091 8.66667 11.3333 7.17428 11.3333 5.33333C11.3333 3.49238 9.84091 2 7.99996 2C6.15901 2 4.66663 3.49238 4.66663 5.33333C4.66663 7.17428 6.15901 8.66667 7.99996 8.66667Z"
                                                stroke="black" stroke-linecap="round" stroke-linejoin="round" />
                                            <path
                                                d="M13.3333 14.0001C13.3333 12.5856 12.7714 11.229 11.7712 10.2288C10.771 9.22865 9.41445 8.66675 7.99996 8.66675C6.58547 8.66675 5.22892 9.22865 4.22872 10.2288C3.22853 11.229 2.66663 12.5856 2.66663 14.0001"
                                                stroke="black" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        {{ getBoardName(game.board_id) }}
                                    </td>
                                    <td v-else class="px-2 py-2 whitespace-nowrap text-sm text-gray-500">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.33337 12.6667C9.33337 11.6059 8.91195 10.5885 8.1618 9.83832C7.41166 9.08818 6.39424 8.66675 5.33337 8.66675C4.27251 8.66675 3.25509 9.08818 2.50495 9.83832C1.7548 10.5885 1.33337 11.6059 1.33337 12.6667"
                                                stroke="black" stroke-linecap="round" stroke-linejoin="round" />
                                            <path
                                                d="M5.33329 8.66658C6.80605 8.66658 7.99996 7.47268 7.99996 5.99992C7.99996 4.52716 6.80605 3.33325 5.33329 3.33325C3.86053 3.33325 2.66663 4.52716 2.66663 5.99992C2.66663 7.47268 3.86053 8.66658 5.33329 8.66658Z"
                                                stroke="black" stroke-linecap="round" stroke-linejoin="round" />
                                            <path
                                                d="M14.6666 12.6666C14.6666 11.6057 14.2452 10.5883 13.4951 9.83816C12.7449 9.08801 11.7275 8.66659 10.6666 8.66659C11.3739 8.66659 12.0521 8.38563 12.5522 7.88554C13.0523 7.38544 13.3333 6.70716 13.3333 5.99992C13.3333 5.29267 13.0523 4.6144 12.5522 4.1143C12.0521 3.6142 11.3739 3.33325 10.6666 3.33325"
                                                stroke="black" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        {{ getBoardName(game.board_id) }}
                                    </td>
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