<template>
    <div class="min-h-screen grid content-between bg-gray-800">
        <Header></Header>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col space-y-8">
                <div class="flex flex-col gap-y">
                    <h1 class="text-2xl font-bold text-white">Buy Coins</h1>
                    <button @click.prevent="comprar">Comprar 10 moedas por 1€</button>
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
import axios from 'axios';

const router = useRouter()
const authStore = useAuthStore()

const goBack = async () => {
    router.push({ name: 'dashboard' })
}

const comprar = async () => {
    axios.post('/buy', {
        'type': 'MBWAY',
        'reference': '768273630',
        'value': 1
    })
}
</script>