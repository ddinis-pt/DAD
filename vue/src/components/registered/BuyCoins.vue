<template>
    <Toaster />
    <div class="min-h-screen grid bg-gray-800">
        <Header></Header>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col space-y-8">
                <div class="flex flex-col gap-y items-center">
                    <svg width="86" height="86" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M21 12V7H5C4.46957 7 3.96086 6.78929 3.58579 6.41421C3.21071 6.03914 3 5.53043 3 5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19V7"
                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M3 5V19C3 19.5304 3.21071 20.0391 3.58579 20.4142C3.96086 20.7893 4.46957 21 5 21H21V16"
                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M18 12C17.4696 12 16.9609 12.2107 16.5858 12.5858C16.2107 12.9609 16 13.4696 16 14C16 14.5304 16.2107 15.0391 16.5858 15.4142C16.9609 15.7893 17.4696 16 18 16H22V12H18Z"
                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex flex-col space-y-4">
                        <select v-model="type" class="p-2 bg-gray-700 text-white rounded">
                            <option value="-1">-- Choose payment type --</option>
                            <option value="PAYPAL">PAYPAL</option>
                            <option value="MBWAY">MBWAY</option>
                            <option value="MB">MB</option>
                            <option value="VISA">VISA</option>
                            <option value="IBAN">IBAN</option>
                        </select>
                        <input v-model="reference" type="text" placeholder="Reference" class="p-2 bg-gray-700 text-white rounded" />
                        <input v-model="value" type="number" placeholder="Value" class="p-2 bg-gray-700 text-white rounded" />
                        <button @click="comprar" class="p-2 bg-blue-500 text-white rounded">Buy</button>
                    </div>
                </div>
            </div>
        </main>
        <!-- <Footer></Footer> -->
    </div>
</template>
<script setup>
import Header from '@/components/ui/Header.vue'
import Footer from '@/components/ui/Footer.vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { ref, watch } from 'vue';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { toast } from '@/components/ui/toast';

const router = useRouter()
const authStore = useAuthStore()

const type = ref('-1')
const reference = ref('')
const value = ref('')

watch(value, (newValue) => {
    if (isNaN(newValue)) {
        value.value = ''
    }
})

const comprar = async () => {
    if(type.value === '-1') {
        toast({
            description: 'Choose a payment method',
            title: 'Error',
            variant: 'warning'
        })
        return
    }
    await axios.post('/buy', {
        'type': type.value,
        'reference': reference.value,
        'value': value.value
    })
    .then(() => {
        toast({
            description: 'Coins bought successfully',
            title: 'Success',
            variant: 'success'
        })
    })
    .catch((error) => {
        toast({
            description: `Error buying coins - ${error.response.data.description}`,
            title: error.response.data.message,
            variant: 'error'
        })
    })
}
</script>