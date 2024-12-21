<script setup>
import { onBeforeMount, onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { toast } from '@/components/ui/toast';
import Header from '@/components/ui/Header.vue';
import avatarNoneAssetURL from '@/assets/avatar-none.png'

const id = ref('')
const email = ref('')
const password = ref('')
const name = ref('')
const nickname = ref('')
const photo = ref('')
const currentPhoto = ref('')
let photoFile = ref('')

const authStore = useAuthStore()
const router = useRouter()
const route = useRoute()

if (authStore.user === null) {
    router.push({ name: 'login' })
}

const handleFileUpload = (event) => {
    const file = event.target.files[0]
    photo.value = file;
}

const submit = async () => {
    let foto = null;
    console.log(photo.value)
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
    }
    if (foto === null) {
        foto = currentPhoto.value
    }
    console.log(foto)
    await axios.put(`/users/${id.value}/update`, {
        email: email.value,
        password: password.value,
        name: name.value,
        nickname: nickname.value,
        photo_filename: foto,
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
    if (!authStore.user) {
        router.push({ name: 'login' })
    }
    getUser()
})

const getUser = async () => {
    await axios.get(`/users/${route.params.id}`)
        .then(response => {
            id.value = response.data.id
            email.value = response.data.email
            name.value = response.data.name
            nickname.value = response.data.nickname
            currentPhoto.value = response.data.photo_filename
            password.value = response.data.password
            getUserPhoto(currentPhoto.value)
        })
        .catch(() => {
            toast({
                title: 'Error',
                description: 'Unable to get user',
                variant: 'error'
            })
        });

}

const getUserPhoto = (photo) => {
    if (photo) {
        photoFile = axios.defaults.baseURL.replace(/\/api(?!.*\/api)/, '/storage/photos/' + photo)
    } else {
        photoFile = avatarNoneAssetURL
    }
}

</script>
<template>
    <div class="bg-gray-800 min-h-screen">
        <Header></Header>
        <main class="">
            <h1 class="text-3xl font-bold text-white text-center pt-8">Edit User:</h1>
            <div class="space-y-2 rounded-xl shadow-md bg-white px-6 py-4 mt-4 max-w-2xl mx-auto">
                <div class="text-center py-4">
                    <h2 class="block text-2xl font-bold text-gray-800">{{ name }}</h2>
                </div>
                <div class="mt-5">

                    <!-- Form -->
                    <form>
                        <div class="grid gap-y-4">
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm mb-2 text-gray-800">Email</label>
                                <div class="relative">
                                    <input v-model="email" type="text" id="email" name="email"
                                        placeholder="Your email goes here"
                                        class="appearance-none py-3 px-4 block w-full border border-gray-500 rounded-lg text-sm focus:border-blue-600"
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

                            <!-- Nickname -->
                            <div>
                                <label for="nickname" class="block text-sm mb-2 text-gray-800">Nickname</label>
                                <div class="relative">
                                    <input v-model="nickname" type="text" id="nickname" name="nickname"
                                        placeholder="Your nickname goes here"
                                        class="appearance-none py-3 px-4 block w-full border border-gray-500 rounded-lg text-sm focus:border-blue-600"
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

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm mb-2 text-gray-800">Name</label>
                                <div class="relative">
                                    <input v-model="name" type="text" id="name" name="name"
                                        placeholder="Your name goes here"
                                        class="appearance-none py-3 px-4 block w-full border border-gray-500 rounded-lg text-sm focus:border-blue-600"
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

                            <!-- Password -->
                            <div>
                                <div class="flex justify-between items-center">
                                    <label for="password" class="block text-sm mb-2 text-gray-800">Password</label>
                                </div>
                                <div class="relative">
                                    <input v-model="password" type="password" id="password" name="password"
                                        class="py-3 px-4 block w-full border border-gray-500 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none placeholder-neutral-500"
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

                            <!-- File Upload -->
                            <div class="flex flex-col">
                                <label for="file-upload" class="inline-block text-sm mb-2 text-gray-800">Profile
                                    Picture</label>

                                <div class="current-picture">
                                    <img class="inline-block size-[100px] rounded-sm border-2 border-blue-600"
                                        :src="photoFile" alt="Avatar">
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
                            <button @click.prevent="deleteAccount"
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-700 text-white hover:bg-red-900 focus:outline-none focus:bg-red-900 disabled:opacity-50 disabled:pointer-events-none">
                                Delete Account
                            </button>
                        </div>
                    </form>
                    <!-- End Form -->
                </div>
            </div>
        </main>
    </div>
</template>