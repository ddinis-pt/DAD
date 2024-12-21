<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
const authStore = useAuthStore()
const router = useRouter()
</script>
<template>
    <footer class="bg-gray-dark">
        <div class="grid gap-x-8 gap-y-4 grid-cols-3 justify-center items-center justify-items-center">
            <div class="leaderboard">
                <RouterLink :to="{name: 'leaderboard'}" class="">
                    <!-- Icon -->
                    <svg width="41" height="41" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.5 15.5H8.00004C6.89497 15.5 5.83516 15.061 5.05376 14.2796C4.27236 13.4982 3.83337 12.4384 3.83337 11.3333C3.83337 10.2282 4.27236 9.16842 5.05376 8.38701C5.83516 7.60561 6.89497 7.16663 8.00004 7.16663H10.5"
                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M30.5 15.5H33C34.1051 15.5 35.1649 15.061 35.9463 14.2796C36.7277 13.4982 37.1667 12.4384 37.1667 11.3333C37.1667 10.2282 36.7277 9.16842 35.9463 8.38701C35.1649 7.60561 34.1051 7.16663 33 7.16663H30.5"
                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7.16663 37.1666H33.8333" stroke="white" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M17.1666 24.9333V28.8333C17.1666 29.75 16.3833 30.4667 15.55 30.85C13.5833 31.75 12.1666 34.2333 12.1666 37.1667"
                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M23.8334 24.9333V28.8333C23.8334 29.75 24.6167 30.4667 25.45 30.85C27.4167 31.75 28.8334 34.2333 28.8334 37.1667"
                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M30.5 3.83337H10.5V15.5C10.5 18.1522 11.5536 20.6957 13.4289 22.5711C15.3043 24.4465 17.8478 25.5 20.5 25.5C23.1522 25.5 25.6957 24.4465 27.5711 22.5711C29.4464 20.6957 30.5 18.1522 30.5 15.5V3.83337Z"
                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <!-- End Icon -->
                </RouterLink>
            </div>
            <div class="text-center gap-5 profile">
                <img class="inline-block size-[80px] rounded-full border-8 border-slate-700 -mt-4"
                    :src="authStore.userPhotoUrl" alt="Avatar">
            </div>
            <div class="history">
                <RouterLink :to="{name: 'history'}">
                    <svg width="41" height="41" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.8334 35.5H33.8334C34.7174 35.5 35.5653 35.1488 36.1904 34.5237C36.8155 33.8986 37.1667 33.0507 37.1667 32.1667V28.8333H17.1667V32.1667C17.1667 33.0507 16.8155 33.8986 16.1904 34.5237C15.5653 35.1488 14.7174 35.5 13.8334 35.5ZM13.8334 35.5C12.9493 35.5 12.1015 35.1488 11.4764 34.5237C10.8512 33.8986 10.5 33.0507 10.5 32.1667V8.83333C10.5 7.94928 10.1489 7.10143 9.52373 6.47631C8.89861 5.85119 8.05076 5.5 7.16671 5.5C6.28265 5.5 5.43481 5.85119 4.80968 6.47631C4.18456 7.10143 3.83337 7.94928 3.83337 8.83333V13.8333H10.5"
                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M32.1666 28.8333V8.83333C32.1666 7.94928 31.8154 7.10143 31.1903 6.47631C30.5652 5.85119 29.7173 5.5 28.8333 5.5H7.16663"
                            stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M25.5 13.8334H17.1666" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M25.5 20.5H17.1666" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </RouterLink>
            </div>
        </div>
    </footer>
</template>