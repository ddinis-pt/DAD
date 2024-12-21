<script setup>
import router from '@/router';
import { useAuthStore } from '@/stores/auth'
import { onBeforeMount, onMounted, watch, ref } from 'vue';
const authStore = useAuthStore()

const back = () => {
    router.push({ name: 'dashboard' })
}

const leave = () => {
    router.push({ name: 'login' })
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
    window.HSStaticMethods.autoInit();
    if (authStore.user) {
        authStore.refreshUserData()
    }
})

const isAdmin = () => {
    return authStore.user && authStore.userType === 'A'
}

const isPlayer = () => {
    return authStore.user && authStore.userType === 'P'
}
</script>
<template>
    <header
        class="flex flex-row z-50 w-full text-sm max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 justify-between self-start">
        <!-- Logo -->
        <div class="bg-gray-800 p-2 dark:p-0 rounded-xl dark:border-neutral-700">
            <img @click.prevent="back" class="cursor-pointer flex-none inline-block h-16"
                src="../../assets/cards-light.png" alt="Memory Game Logo" title="Go to dashboard" />
        </div>
        <!-- End Logo -->
        <!-- Navigation -->
        <div class="flex items-center gap-x-2">
            <span v-if="isPlayer()"
                class="mr-3 inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium border bg-gray-800 text-white border-gray-800 dark:border-white dark:text-white">
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
                        <h2 class="block text-xl font-bold text-gray-800 dark:text-white pl-4 pr-2">Hello, {{
                            authStore.userFirstLastName
                            }}!
                        </h2>
                        <i
                            class="pi pi-chevron-down hs-dropdown-open:rotate-180 text-sm text-gray-800 dark:text-white"></i>
                    </div>
                    <div v-else class="flex items-center">
                        <img class="inline-block size-[55px] rounded-full border-2 border-blue-600"
                            src="../../assets/avatar-none.png" alt="Avatar">
                        <h2 class="block text-xl font-bold text-white pl-2">Hello, Guest!</h2>
                    </div>
                </button>
                <div class="z-10 hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 dark:bg-slate-900 dark:border dark:border-neutral-700"
                    role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-with-header">
                    <div v-if="authStore.user" class="py-3 px-4 border-b border-gray-200 dark:border-neutral-700">
                        <p class="text-sm text-gray-500 dark:text-neutral-400">Signed in as</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-300">{{ authStore.userEmail }}</p>
                    </div>
                    <div class="p-1 space-y-0.5">
                        <RouterLink :to="{ name: 'settings' }" v-if="authStore.user"
                            class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                            <i class="pi pi-cog"></i>
                            Settings
                        </RouterLink>
                        <RouterLink :to="{ name: 'buy-coins' }" v-if="isPlayer()"
                            class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
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
                        <a v-if="authStore.user"
                            class="cursor-pointer flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
                            @click="logout">
                            <i class="pi pi-sign-out"></i>
                            Logout
                        </a>

                        <a v-if="!authStore.user"
                            class="cursor-pointer flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
                            @click="leave">
                            <i class="pi pi-sign-out"></i>
                            Leave

                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Navigation -->
    </header>
</template>