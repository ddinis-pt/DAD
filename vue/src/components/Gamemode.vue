<script setup>
import Header from '@/components/ui/Header.vue'
import Footer from '@/components/ui/Footer.vue';
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router';
import axios from 'axios';
import { onMounted, ref } from 'vue';

import { format } from 'date-fns';
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';

const router = useRouter()

const authStore = useAuthStore()
const guest = authStore.user ? false : true

const confirm = useConfirm();

const hascoins = ref(true)

const showTemplate = () => {
    confirm.require({
        group: 'templating',
        header: 'Start Game',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancel',
            icon: 'pi pi-times',
            outlined: true,
            size: 'small'
        },
        acceptProps: {
            label: 'Start',
            icon: 'pi pi-check',
            size: 'small'
        },
        accept: () => {
            checkMoney(2)
        },
        reject: () => {

        }
    });
};

const showTemplate2 = () => {
    confirm.require({
        group: 'templating2',
        header: 'Start Game',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancel',
            icon: 'pi pi-times',
            outlined: true,
            size: 'small'
        },
        acceptProps: {
            label: 'Start',
            icon: 'pi pi-check',
            size: 'small'
        },
        accept: () => {
            checkMoney(3)
        },
        reject: () => {

        }
    });
};

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

    if (authStore.userCoins < 1) {
        hascoins.value = false
    }

})

</script>
<template>
    <div class="min-h-screen grid content-between bg-sky-50 dark:bg-gray-800">
        <Header></Header>
        <div class="grid justify-center text-center justify-items-center">

            <h2 class="block text-2xl font-bold text-gray-800 dark:text-white py-8">Choose your board size:</h2>
            <RouterLink :to="{ name: '3by4' }">
                <div class="py-6">
                    <div class="dark:bg-white dark:p-2 dark:rounded-xl">
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

            <div v-show="guest || !hascoins" class="justify-items-center">
                <h2 v-if="guest" class="text-xl font-semibold text-gray-300 py-2">Only available for registered players:
                </h2>
                <h2 v-else class="text-xl font-semibold text-gray-300 py-2">You don't have enough coins to play</h2>
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
                        <svg width="155" height="155" viewBox="0 0 155 155" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
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
            <div v-show="!guest" v-if="hascoins" class="justify-items-center">
                <div class="py-6" @click="showTemplate()">
                    <div class="dark:bg-white dark:p-2 dark:rounded-xl">
                        <svg width="110" height="110" viewBox="0 0 110 110" fill="none"
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
                <div class="py-6" @click="showTemplate2()">
                    <div class="dark:bg-white dark:p-2 dark:rounded-xl">
                        <svg width="155" height="155" viewBox="0 0 155 155" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
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
        <ConfirmDialog group="templating">
            <template #message="slotProps">
                <div
                    class="flex flex-col items-center w-full gap-4 border-b border-surface-200 dark:border-surface-700">
                    <i :class="slotProps.message.icon" class="!text-6xl text-primary-500"></i>
                    <p class="pb-2"> Are you sure you want to start this game? <br> Starting this game spends <strong>1
                            coin</strong>.</p>
                </div>
            </template>
        </ConfirmDialog>
        <ConfirmDialog group="templating2">
            <template #message="slotProps">
                <div
                    class="flex flex-col items-center w-full gap-4 border-b border-surface-200 dark:border-surface-700">
                    <i :class="slotProps.message.icon" class="!text-6xl text-primary-500"></i>
                    <p class="pb-2"> Are you sure you want to start this game? <br> Starting this game spends <strong>1
                            coin</strong>.</p>
                </div>
            </template>
        </ConfirmDialog>




    </div>
</template>