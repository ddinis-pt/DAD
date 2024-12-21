<script setup>
import Header from '@/components/ui/Header.vue'
import Footer from '@/components/ui/Footer.vue';
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router';
import axios from 'axios';
import { onMounted } from 'vue';

import { format } from 'date-fns';

const router = useRouter()

const authStore = useAuthStore()
const guest = authStore.user ? false : true

const checkMoney = async (mode) => {
    if (!guest) {
        if (authStore.userCoins > 1) {
            // spend one coin and redirect to the game
            await axios.put('/spend/1')
            // register transaction
            await axios.post('/registerTransaction', {
                transaction_datetime: format(new Date(), 'yyyy-MM-dd HH:mm:ss'),
                user_id: authStore.user.id,
                type: 'B',
                brain_coins: -1,
            })
            authStore.refreshUserData()
            switch (mode) {
                case 1: router.push({ name: '3by4' }); break;
                case 2: router.push({ name: '4by4' }); break;
                case 3: router.push({ name: '6by6' }); break;
            }
        }
    }
}
onMounted(() => {
    window.HSStaticMethods.autoInit();
})

</script>
<template>
    <div class="min-h-screen grid content-between bg-sky-50 dark:bg-gray-800">
        <Header></Header>
        <div class="grid justify-center text-center justify-items-center" >
            <h2 class="block text-2xl font-bold text-gray-800 dark:text-white py-8">Choose your board size:</h2>
            <RouterLink :to="{ name: '3by4' }">
                <div class="py-6">
                    <div class="dark:bg-white dark:p-2 dark:rounded-xl" >
                        <svg width="111" height="86" viewBox="0 0 111 86" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="13" y="13" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="13" y="35" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="13" y="57" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="36" y="13" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="36" y="35" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="36" y="57" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="59" y="13" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="59" y="35" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="59" y="57" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="82" y="13" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="82" y="35" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="82" y="57" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="2" y="2" width="107" height="82" rx="5" stroke="#1E293B" stroke-width="4" />
                        </svg>
                    </div>
                    <h3 class="text-1xl font-semibold text-gray-800 dark:text-white py-2">3x4</h3>
                </div>
            </RouterLink>

            <div v-show="guest" class="justify-items-center">
                <h2 class="text-xl font-semibold text-gray-300 py-2">Only available for registered players:</h2>
                <div class="py-6">
                    <div class="dark:bg-white dark:p-2 dark:rounded-xl">
                        <svg width="110" height="110" viewBox="0 0 110 110" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="13" y="13" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="13" y="35" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="13" y="57" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="36" y="13" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="36" y="35" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="36" y="57" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="59" y="13" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="59" y="35" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="59" y="57" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="82" y="13" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="82" y="35" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="82" y="57" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="13" y="79" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="36" y="79" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="59" y="79" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="82" y="79" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                                stroke-width="4" stroke-linecap="round" />
                            <rect x="2" y="2" width="106" height="106" rx="5" stroke="#CBD5E1" stroke-width="4" />
                        </svg>
                    </div>
                    <h3 class="text-1xl font-semibold text-gray-300 dark:text-white py-2">4x4</h3>
                </div>


                <div class="py-6">
                    <div class="dark:bg-white dark:p-2 dark:rounded-xl">
                    <svg width="155" height="155" viewBox="0 0 155 155" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="12" y="14" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="12" y="36" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="12" y="58" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="35" y="14" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="35" y="36" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="35" y="58" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="58" y="14" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="58" y="36" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="58" y="58" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="81" y="14" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="81" y="36" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="81" y="58" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="12" y="80" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="35" y="80" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="58" y="80" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="81" y="80" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="104" y="14" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="104" y="36" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="104" y="58" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="127" y="14" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="127" y="36" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="127" y="58" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="104" y="80" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="127" y="80" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="12" y="102" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="35" y="102" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="58" y="102" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="81" y="102" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="12" y="124" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="35" y="124" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="58" y="124" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="81" y="124" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="104" y="102" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="127" y="102" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="104" y="124" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="127" y="124" width="16" height="16" rx="3" fill="#CBD5E1" stroke="#CBD5E1"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="2" y="2" width="151" height="151" rx="5" stroke="#CBD5E1" stroke-width="4" />
                    </svg>
                    </div>
                    <h3 class="text-1xl font-semibold text-gray-300 dark:text-white py-2">6x6</h3>
                </div>

            </div>
            <div v-show="!guest" class="justify-items-center">

                <div  class="py-6 justify-items-center" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-modal-spend-coin-4x4"
                data-hs-overlay="#hs-modal-spend-coin-4x4">
                    <div class="dark:bg-white dark:p-2 dark:rounded-xl" >
                    <svg width="110" height="110" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="13" y="13" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="13" y="35" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="13" y="57" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="36" y="13" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="36" y="35" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="36" y="57" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="59" y="13" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="59" y="35" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="59" y="57" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="82" y="13" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="82" y="35" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="82" y="57" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="13" y="79" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="36" y="79" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="59" y="79" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="82" y="79" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="2" y="2" width="106" height="106" rx="5" stroke="#1F2937" stroke-width="4" />
                    </svg>
                </div>
                    <h3 class="text-1xl font-semibold text-gray-800 dark:text-white py-2">4x4</h3>

                </div>
                <div  class="py-6" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-modal-spend-coin-6x6"
                data-hs-overlay="#hs-modal-spend-coin-6x6">
                    <div class="dark:bg-white dark:p-2 dark:rounded-xl">
                    <svg width="155" height="155" viewBox="0 0 155 155" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="12" y="14" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="12" y="36" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="12" y="58" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="35" y="14" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="35" y="36" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="35" y="58" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="58" y="14" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="58" y="36" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="58" y="58" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="81" y="14" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="81" y="36" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="81" y="58" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="12" y="80" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="35" y="80" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="58" y="80" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="81" y="80" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="104" y="14" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="104" y="36" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="104" y="58" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="127" y="14" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="127" y="36" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="127" y="58" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="104" y="80" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="127" y="80" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="12" y="102" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="35" y="102" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="58" y="102" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="81" y="102" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="12" y="124" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="35" y="124" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="58" y="124" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="81" y="124" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="104" y="102" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="127" y="102" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="104" y="124" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="127" y="124" width="16" height="16" rx="3" fill="#1F2937" stroke="#1F2937"
                            stroke-width="4" stroke-linecap="round" />
                        <rect x="2" y="2" width="151" height="151" rx="5" stroke="#1F2937" stroke-width="4" />
                    </svg>
                    </div>
                    <h3 class="text-1xl font-semibold text-gray-800 dark:text-white py-2">6x6</h3>
                </div>
            </div>

        </div>
      <!-- Modal for 4x4 -->
    <div id="hs-modal-spend-coin-4x4"
      class="hs-overlay hs-overlay-backdrop-open:bg-blue-950/90 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 hidden size-full fixed top-0 start-0 z-[80] opacity-0 overflow-x-hidden transition-all overflow-y-auto pointer-events-none"
      role="dialog" tabindex="-1" aria-labelledby="hs-modal-spend-coin-label-4x4">
      <div class="min-h-[calc(100%-3.5rem)] flex items-center justify-center">
        <div
          class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
          <div class="p-4 sm:p-10 text-center overflow-y-auto">
            <!-- Icon -->
            <span
              class="mb-4 inline-flex justify-center items-center size-[62px] rounded-full border-4 border-yellow-50 bg-yellow-500 text-yellow-500 dark:bg-yellow-700 dark:border-yellow-600 dark:text-yellow-100">
              <i class="pi pi-question-mark size-[2.5rem]"></i>
            </span>
            <!-- End Icon -->
            <h3 id="hs-modal-spend-coin-label-4x4" class="mb-2 text-2xl font-bold text-gray-800 dark:text-neutral-200">
              Start Game
            </h3>
            <p class="text-gray-500 dark:text-neutral-500">
              Are you sure you want to start this game? <br> Starting this game spends <strong>1 coin</strong>.
            </p>
            <div class="mt-6 grid gap-y-2">
              <button @click.prevent="checkMoney(2)" type="button"
                class="py-2.5 px-4 w-full inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border
                 border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 
                 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent
                  dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800
                   dark:focus:bg-neutral-800" data-hs-overlay="#hs-modal-spend-coin">
                
                Yes, start game
              </button>
              <button type="button" @click=""
                class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                No, go back
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal for 6x6 -->
    <div id="hs-modal-spend-coin-6x6"
      class="hs-overlay hs-overlay-backdrop-open:bg-blue-950/90 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 hidden size-full fixed top-0 start-0 z-[80] opacity-0 overflow-x-hidden transition-all overflow-y-auto pointer-events-none"
      role="dialog" tabindex="-1" aria-labelledby="hs-modal-spend-coin-label-6x6">
      <div class="min-h-[calc(100%-3.5rem)] flex items-center justify-center">
        <div
          class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
          <div class="p-4 sm:p-10 text-center overflow-y-auto">
            <!-- Icon -->
            <span
              class="mb-4 inline-flex justify-center items-center size-[62px] rounded-full border-4 border-yellow-50 bg-yellow-500 text-yellow-500 dark:bg-yellow-700 dark:border-yellow-600 dark:text-yellow-100">
              <i class="pi pi-question-mark size-[2.5rem]"></i>
            </span>
            <!-- End Icon -->
            <h3 id="hs-modal-spend-coin-label-6x6" class="mb-2 text-2xl font-bold text-gray-800 dark:text-neutral-200">
              Start Game
            </h3>
            <p class="text-gray-500 dark:text-neutral-500">
              Are you sure you want to start this game? <br> Starting this game spends <strong>1 coin</strong>.
            </p>
            <div class="mt-6 grid gap-y-2">
              <button @click.prevent="checkMoney(3)" type="button"
                class="py-2.5 px-4 w-full inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                Yes, start game
              </button>
              <button type="button" @click="closeModal('6x6')"
                class="py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                No, go back
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>