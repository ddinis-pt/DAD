<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth';
import { Button } from '@/components/ui/button';
import { useRouter } from 'vue-router';
import { toast } from "@/components/ui/toast/index.js";
import Header from "@/components/ui/Header.vue";

import { useErrorStore } from '@/stores/error';
import axios from 'axios';
const errorStore = useErrorStore()
const authStore = useAuthStore()

const photo = ref('')
const nickname = ref('')
const name = ref('')
const confirmPassword = ref('')

const router = useRouter()

const email = ref('')

const password = ref('')
const responseData = ref('')

const continueAsGuest = async () => {
    if (authStore.user) {
        await authStore.logout()
    }
    router.push({ name: 'dashboard' })
}

const handleFileUpload = (event) => {
    const file = event.target.files[0]
    photo.value = file;
}

const submit = async () => {
    if (password.value !== confirmPassword.value) {
        responseData.value = 'Passwords do not match'
        document.getElementById('error').classList.remove('hidden')
        return
    }
    if (photo.value !== '') {
        const form = new FormData();
        form.append('photo', photo.value)
        await axios.post('/images', form, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(async (response) => {
            let user = null
            try {
                user = await authStore.register({
                    email: email.value,
                    name: name.value,
                    nickname: nickname.value,
                    password: password.value,
                    photo_filename: response.data.photo_filename
                })
            } catch (error) {
                responseData.value = 'Unable to register, please try again later'
                document.getElementById('error').classList.remove('hidden')
            }

            if (user) {
                router.push({ name: 'dashboard' })
            }
            else {
                if (errorStore.statusCode === 401) {
                    responseData.value = 'Invalid credentials'
                }

                else if (errorStore.statusCode === 422) {
                    responseData.value = 'Please fill all the fields above'
                }
                document.getElementById('error').classList.remove('hidden')
            }
        })
        return;
    }
}

</script>

<template>
    <div class="flex items-center w-full justify-center bg-gray-800 min-h-screen min-w-screen">
        <main class="w-full max-w-md px-6 py-4 rounded-xl shadow-md bg-white">
            <div class="flex flex-col space-y-2">

                <!-- Logo -->
                <div class="flex items-center justify-center bg-gray-800 rounded-xl py-2">
                    <img class="flex-none inline-block h-8" src="../../assets/cards-light.png" alt="ESTG Logo" />
                </div>

                <div class="text-center py-4">
                    <h2 class="block text-2xl font-bold text-gray-800">Register an account</h2>
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
                                <p class="hidden text-xs text-red-600 mt-2" id="error">{{ responseData }}</p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="confirmPassword" class="block text-sm mb-2 text-gray-800">Confirm
                                    password</label>
                                <div class="relative">
                                    <input v-model="confirmPassword" type="password" id="confirmPassword" name="confirmPassword"
                                        class="appearance-none py-3 px-4 block w-full border border-gray-500 rounded-lg text-sm focus:border-blue-600"
                                        required aria-describedby="confirmPassword-error">
                                    <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                                        <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor"
                                            viewBox="0 0 16 16" aria-hidden="true">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Remember me -->
                            <div class="flex items-center">
                                <div class="flex">
                                    <input id="remember-me" name="remember-me" type="checkbox" checked
                                        class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 bg-neutral-800 checked:bg-blue-500 checked:border-blue-500 focus:ring-offset-gray-800">
                                </div>
                                <div class="ms-3">
                                    <label for="remember-me" class="text-sm text-gray-800">Remember me</label>
                                </div>
                            </div>

                            <!-- File Upload -->
                            <div>
                                <label for="file-upload" class="inline-block text-sm mb-2 text-gray-800">Profile
                                    Picture</label>
                                <div class="relative">
                                    <input type="file" id="file-upload" name="file-upload"
                                        class="appearance-none py-3 px-4 border w-full bg-blue-600 rounded-lg text-sm focus:border-blue-600"
                                        @change="handleFileUpload">
                                </div>
                            </div>

                            <button @click.prevent="submit"
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                Register
                            </button>

                            <!-- Divider -->
                            <div class="flex items-center text-xs uppercase text-neutral-500 py-3">
                                <div class="flex-1 border-t border-blue-600 rounded-full border-8"></div>
                            </div>

                            <RouterLink :to="{ name: 'login' }"
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-blue-text bg-white text-blue-text hover:bg-blue-text hover:text-white focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                Log in
                            </RouterLink>
                            <div class="flex items-center text-xs uppercase text-neutral-500 py-3">
                                <div class="flex-1 border-t border-neutral-600"></div>
                                <span class="px-3">Or</span>
                                <div class="flex-1 border-t border-neutral-600"></div>
                            </div>
                            <button @click.prevent="continueAsGuest"
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-blue-text bg-white text-blue-text hover:bg-blue-text hover:text-white focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                Continue as Guest
                            </button>

                        </div>
                    </form>
                    <!-- End Form -->
                </div>

            </div>

            <div class="text-center pt-4">
                <RouterLink :to="{ name: 'about' }"
                    class="text-sm hover:underline focus:outline-none focus:underline font-medium text-blue-500">
                    About
                </RouterLink>
            </div>
        </main>
    </div>
</template>