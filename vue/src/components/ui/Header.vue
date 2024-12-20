<script setup>
import router from '@/router';
import { onMounted, watch } from 'vue';
import { useAuthStore } from '@/stores/auth'
const authStore = useAuthStore()

const back = () => {
    router.push({ name: 'dashboard' })
}

const logout = async () => {
    if (authStore.user != null) {
        let result = await authStore.logout(authStore.user)
        if (result) {
            router.push({ name: 'login' })
        }
    } else {
        router.push({ name: 'login' })
    }
}

watch(() => authStore.userCoins, (newValue, oldValue) => {
    console.log('User coins changed from', oldValue, 'to', newValue)
    authStore.refreshUserData()
})

onMounted(() => {
    if (authStore.user) {
        authStore.refreshUserData()
    }
})
</script>
<template>
    <header
        class="flex flex-row z-50 w-full text-sm max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 justify-between self-start">
        <!-- Logo -->
        <div>
            <img @click.prevent="back" class="flex-none inline-block h-16" src="../../assets/cards-light.png"
                alt="App Logo" />
        </div>
        <!-- End Logo -->
        <!-- Navigation -->
        <div class="flex items-center gap-x-2">
            <span v-if="authStore.user"
                class="mr-3 inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium border border-white text-white dark:border-neutral-200 dark:text-white">
                <svg class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="8" cy="8" r="6" />
                    <path d="M18.09 10.37A6 6 0 1 1 10.34 18" />
                    <path d="M7 6h1v4" />
                    <path d="m16.71 13.88.7.71-2.82 2.82" />
                </svg> {{ authStore.userCoins }}
            </span>
            <div class="hs-dropdown relative items-center flex">
                <button id="hs-dropdown-custom-trigger" type="button"
                    class="hs-dropdown-toggle ps-1 pe-3 inline-flex gap-x-2 text-sm font-medium text-white disabled:opacity-50 disabled:pointer-events-none"
                    aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                    <div v-if="authStore.user" class="flex items-center">
                        <img class="inline-block size-[55px] rounded-full border-2 border-blue-600"
                            :src="authStore.userPhotoUrl" alt="Avatar">
                        <h2 class="block text-xl font-bold text-white pl-4 pr-2">Hello, {{ authStore.userFirstLastName
                            }}!
                        </h2>
                        <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </div>
                    <div v-else class="flex items-center">
                        <img class="inline-block size-[55px] rounded-full border-2 border-blue-600"
                            src="../../assets/avatar-none.png" alt="Avatar">
                        <h2 class="block text-xl font-bold text-white pl-2">Hello, Guest!</h2>
                    </div>
                </button>
                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700"
                    role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-with-header">
                    <div v-if="authStore.user" class="py-3 px-4 border-b border-gray-200 dark:border-neutral-700">
                        <p class="text-sm text-gray-500 dark:text-neutral-400">Signed in as</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-300">{{ authStore.userEmail }}</p>
                    </div>
                    <div class="p-1 space-y-0.5">
                        <RouterLink v-if="authStore.user"
                            class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
                            :to="{ name: 'settings' }">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            Settings
                        </RouterLink>
                        <RouterLink v-if="authStore.user"
                            class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
                            :to="{ name: 'buy-coins' }">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="8" r="6" />
                                <path d="M18.09 10.37A6 6 0 1 1 10.34 18" />
                                <path d="M7 6h1v4" />
                                <path d="m16.71 13.88.7.71-2.82 2.82" />
                            </svg>
                            Buy Coins
                        </RouterLink>
                        <a class="cursor-pointer flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
                            @click="logout">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" x2="9" y1="12" y2="12" />
                            </svg>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Navigation -->
    </header>
</template>