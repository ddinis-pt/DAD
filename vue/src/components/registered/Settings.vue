<script setup>
import { onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import axios from 'axios';
import { toast } from '@/components/ui/toast';
import Header from '@/components/ui/Header.vue';

const email = ref('')
const password = ref('')
const name = ref('')
const nickname = ref('')
const photo = ref('')
const currentPhoto = ref('')

const authStore = useAuthStore()
const router = useRouter()

const userPhoto = authStore.userPhotoUrl

if (authStore.user === null) {
    router.push({ name: 'login' })
}

const handleFileUpload = (event) => {
    const file = event.target.files[0]
    photo.value = file;
}

const submit = async () => {
    let foto = null;
    if (photo.value !== '') {
        const form = new FormData();
        form.append('photo', photo.value);
        await axios.post('/images', form, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then(response => {
                foto = response.data.photo_filename;
            })
            .catch(() => {
                toast({
                    title: 'Error',
                    description: 'Unable to upload photo',
                    variant: 'error'
                })
                return;
            });
    } else {
        foto = userPhoto.split('/').pop();
    }
    await axios.put(`/users/${authStore.user.id}`, {
        email: email.value,
        password: password.value,
        name: name.value,
        nickname: nickname.value,
        photo_filename: foto
    })
        .then(() => {
            toast({
                title: 'Success',
                description: 'User updated successfully',
                variant: 'success'
            })
            router.push({ name: 'dashboard' })
        })
        .catch(() => {
            toast({
                title: 'Error',
                description: 'Unable to update user',
                variant: 'error'
            })
        });
}

onMounted(() => {
    email.value = authStore.userEmail
    name.value = authStore.userName
    nickname.value = authStore.userNickname
    password.value = authStore.userPassword
    currentPhoto.value = authStore.userPhotoUrl
    document.title = 'Memory Card Game | Settings'
})

const deleteAccount = () => {
    router.push({ name: 'deleteAccount' })
}

</script>
<template>
    <div class="bg-sky-50 dark:bg-gray-800 min-h-screen">
        <Header></Header>
        <main>
            <h1 class="text-3xl font-bold dark:text-white text-gray-800 text-center pt-8">Settings</h1>
            <div class="space-y-2 rounded-xl shadow-md bg-white dark:bg-slate-900 px-6 py-4 mt-4 max-w-2xl mx-auto">
                <div class="mt-5">
                    <form>
                        <div class="grid gap-y-4">
                            <div>
                                <label for="email" class="block text-sm mb-2 text-gray-800 dark:text-white">Email</label>
                                <div class="relative">
                                    <input v-model="email" type="text" id="email" name="email"
                                        placeholder="Your email goes here"
                                        class="appearance-none py-3 px-4 block w-full border border-gray-500 rounded-lg text-sm focus:border-blue-600 dark:bg-gray-800"
                                        required aria-describedby="email-error">
                                    <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                                        <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor"
                                            viewBox="0 0 16 16" aria-hidden="true">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="nickname" class="block text-sm mb-2 text-gray-800 dark:text-white">Nickname</label>
                                <div class="relative">
                                    <input v-model="nickname" type="text" id="nickname" name="nickname"
                                        placeholder="Your nickname goes here"
                                        class="appearance-none py-3 px-4 block w-full border border-gray-500 rounded-lg text-sm focus:border-blue-600 dark:bg-gray-800"
                                        required aria-describedby="email-error">
                                    <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                                        <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor"
                                            viewBox="0 0 16 16" aria-hidden="true">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="name" class="block text-sm mb-2 text-gray-800 dark:text-white">Name</label>
                                <div class="relative">
                                    <input v-model="name" type="text" id="name" name="name"
                                        placeholder="Your name goes here"
                                        class="appearance-none py-3 px-4 block w-full border border-gray-500 rounded-lg text-sm focus:border-blue-600 dark:bg-gray-800"
                                        required aria-describedby="email-error">
                                    <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                                        <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor"
                                            viewBox="0 0 16 16" aria-hidden="true">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center">
                                    <label for="password" class="block text-sm mb-2 text-gray-800 dark:text-white">Password</label>
                                </div>
                                <div class="relative">
                                    <input v-model="password" type="password" id="password" name="password"
                                        class="py-3 px-4 block w-full border border-gray-500 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none placeholder-neutral-500 dark:bg-gray-800"
                                        required aria-describedby="password-error">
                                    <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                                        <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor"
                                            viewBox="0 0 16 16" aria-hidden="true">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="file-upload" class="inline-block text-sm mb-2 text-gray-800 dark:text-white">Profile
                                    Picture</label>
                                <div class="current-picture pb-2">
                                    <img class="inline-block size-[100px] rounded-sm border-2 border-blue-600"
                                        :src="currentPhoto" alt="Avatar">
                                    <span class="block text-xs text-left text-gray-500">Current profile
                                        picture</span>
                                </div>
                                <div class="relative">
                                    <input type="file" id="file-upload" name="file-upload"
                                        class="appearance-none py-3 px-4 border w-full bg-blue-600 rounded-lg text-sm focus:border-blue-600"
                                        @change="handleFileUpload">
                                </div>
                            </div>
                            <button @click.prevent="submit"
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                Update
                            </button>
                            <button v-if="authStore.userType === 'P'" @click.prevent="deleteAccount"
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-700 text-white hover:bg-red-900 focus:outline-none focus:bg-red-900 disabled:opacity-50 disabled:pointer-events-none">
                                Delete Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</template>