<script setup>
import Header from '@/components/ui/Header.vue'
import { useAuthStore } from '@/stores/auth';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { toast } from '@/components/ui/toast';
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';
import { format } from 'date-fns';

const confirm = useConfirm();
const authStore = useAuthStore()

const type = ref('-1')
const reference = ref('')
const value = ref('')
const placeholder = ref('')

watch(value, (newValue) => {
    if (isNaN(newValue)) {
        value.value = ''
    }
})

watch(type, (newValue) => {
    if (newValue === 'PAYPAL') {
        placeholder.value = 'e.g. john.doe@gmail.com'
    } else if (newValue === 'MBWAY') {
        placeholder.value = 'e.g. 915785345'
    } else if (newValue === 'MB') {
        placeholder.value = 'e.g. 45634-123456789'
    } else if (newValue === 'VISA') {
        placeholder.value = 'e.g. 4321567812345678'
    } else if (newValue === 'IBAN') {
        placeholder.value = 'e.g. PT50123456781234567812349'
    } else {
        placeholder.value = 'Invalid Payment Method'
    }
})

const comprar = async () => {
    if (type.value === '-1') {
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
        .then(async () => {
            await axios.post('/registerTransaction', {
                transaction_datetime: format(new Date(), 'yyyy-MM-dd HH:mm:ss'),
                user_id: authStore.user.id,
                type: 'P',
                euros: value.value,
                payment_type: type.value,
                payment_reference: reference.value,
                brain_coins: value.value * 10,
            })
            toast({
                description: 'Coins bought successfully',
                title: 'Success',
                variant: 'success'
            })
        })
        .catch((error) => {
            toast({
                description: error.response.data.message,
                title: `Error buying coins - ${error.response.data.status}`,
                variant: 'error'
            })
        })
}

const showTemplate = () => {
    if (type.value === '-1') {
        toast({
            description: 'Choose a payment method',
            title: 'Error',
            variant: 'warning'
        })
        return
    }
    if (reference.value === '') {
        toast({
            description: 'Insert a reference',
            title: 'Error',
            variant: 'warning'
        })
        return
    }
    if (value.value === '') {
        toast({
            description: 'Insert a value',
            title: 'Error',
            variant: 'warning'
        })
        return
    }
    confirm.require({
        group: 'templating',
        header: 'Confirm purchase',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancel',
            icon: 'pi pi-times',
            outlined: true,
            size: 'small'
        },
        acceptProps: {
            label: 'Buy Coins',
            icon: 'pi pi-check',
            size: 'small'
        },
        accept: async () => {
            await comprar()
            authStore.refreshUserData()
        },
        reject: () => {

        }
    });
};

onMounted(() => {
    document.title = 'Memory Card Game | Buy Coins'
})
</script>
<template>
    <Toaster />
    <div class="min-h-screen grid bg-sky-50 dark:bg-gray-800">
        <Header></Header>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col space-y-8">
                <div class="flex flex-col gap-y items-center bg-white p-6 rounded-xl shadow-md">
                    <svg width="86" height="86" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M21 12V7H5C4.46957 7 3.96086 6.78929 3.58579 6.41421C3.21071 6.03914 3 5.53043 3 5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19V7"
                            stroke="#374151" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M3 5V19C3 19.5304 3.21071 20.0391 3.58579 20.4142C3.96086 20.7893 4.46957 21 5 21H21V16"
                            stroke="#374151" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M18 12C17.4696 12 16.9609 12.2107 16.5858 12.5858C16.2107 12.9609 16 13.4696 16 14C16 14.5304 16.2107 15.0391 16.5858 15.4142C16.9609 15.7893 17.4696 16 18 16H22V12H18Z"
                            stroke="#374151" stroke-linecap="round" stroke-linejoin="round" />
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
                        <input v-model="reference" type="text" :placeholder="placeholder"
                            class="p-2 bg-gray-700 text-white rounded" />
                        <input v-model="value" type="number" placeholder="Value"
                            class="p-2 bg-gray-700 text-white rounded" />
                        <button @click="showTemplate()" class="p-2 bg-blue-500 text-white rounded">Buy</button>
                    </div>
                </div>
            </div>
        </main>
        <ConfirmDialog group="templating">
            <template #message="slotProps">
                <div
                    class="flex flex-col items-center w-full gap-4 border-b border-surface-200 dark:border-surface-700">
                    <i :class="slotProps.message.icon" class="!text-6xl text-primary-500"></i>
                    <p>Are you sure you want to buy {{ value * 10 }} coins for {{ value }}€?</p>
                </div>
            </template>
        </ConfirmDialog>
    </div>
</template>