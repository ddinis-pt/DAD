<template>
    <Toaster />
    <div class="flex items-center w-full justify-center bg-gray-800 min-h-screen min-w-screen">
        <main class="w-full max-w-md px-6 py-4 rounded-xl shadow-md bg-white">
            <div class="flex flex-col space-y-2">
                <div class="text-center py-4">
                    <h2 class="block text-2xl font-bold text-gray-800">Type this word to confirm</h2>
                    <h3 class="text-xl font-semibold text-blue-600">{{ randomWord }}</h3>
                </div>
                <div class="mt-5">
                    <!-- Form -->
                    <form>
                        <div class="grid gap-y-4">
                            <!-- Form Group -->
                            <div>
                                <label for="confirmation" class="block text-sm mb-2 text-gray-800">Confirm
                                    Delete</label>
                                <div class="relative">
                                    <input v-model="confirmation" type="text" id="confirmation" autocomplete="off"
                                        name="econfirmationmail"
                                        class="appearance-none py-3 px-4 block w-full border border-gray-500 rounded-lg text-sm focus:border-blue-600"
                                        required aria-describedby="confirmation-error">
                                </div>
                            </div>
                            <button @click.prevent="submit"
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-900 text-white hover:bg-red-600 focus:outline-none focus:bg-red-600 disabled:opacity-50 disabled:pointer-events-none">
                                Delete Account
                            </button>
                        </div>
                    </form>
                    <!-- End Form -->
                </div>
            </div>

            <div class="text-center pt-4">
                <RouterLink :to="{ name: 'settings' }"
                    class="text-sm hover:underline focus:outline-none focus:underline font-medium text-blue-500">
                    Go back
                </RouterLink>
            </div>
        </main>
    </div>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import Toaster from '@/components/ui/toast/Toaster.vue'
import { toast } from '@/components/ui/toast'
import axios from 'axios'


import { format } from 'date-fns';

const confirmation = ref('')
const authStore = useAuthStore()
const router = useRouter()

const wordList = ref(['sapphire', 'evergreen', 'nimbus', 'cascade', 'orbit', 'stellar', 'frostbite', 'phoenix', 'harmony', 'zephyr'])
const randomWord = ref('')

if (authStore.user === null) {
    router.push({ name: 'login' })
}

const submit = async () => {
    if (confirmation.value === randomWord.value) {
        await axios.put('/spend/' + authStore.userCoins);
        await axios.post('/registerTransaction', {
            transaction_datetime: format(new Date(), 'yyyy-MM-dd HH:mm:ss'),
            user_id: authStore.user.id,
            type: 'B',
            brain_coins: -authStore.userCoins,
        })
        await axios.delete(`/users/${authStore.user.id}`)
            .then(() => {
                authStore.logout()
                router.push({ name: 'login' })
                toast({
                    description: 'Your account has been deleted',
                    title: 'Account Deleted',
                    variant: 'success'
                })
            })
            .catch(() => {
                toast({
                    description: 'An error occurred while deleting your account',
                    title: 'Account Deletion Error',
                    variant: 'error'
                })
            })
    } else {
        toast({
            description: 'The word you entered is incorrect',
            title: 'Input Error',
            variant: 'warning'
        })
    }
}

onMounted(() => {
    randomWord.value = wordList.value[Math.floor(Math.random() * wordList.value.length)]
})
</script>