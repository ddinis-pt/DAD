<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router';
import { useErrorStore } from '@/stores/error';
import Header from '@/components/ui/Header.vue'
import Footer from '@/components/ui/Footer.vue';

const router = useRouter()
const errorStore = useErrorStore()
const authStore = useAuthStore()
</script>

<template>

  <div class="min-h-screen grid content-between bg-gray-800">
    <Header></Header>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="authStore.user" class="text-center flex justify-center items-center -mt-32">
        <img @click="logout" class="inline-block size-[80px] rounded-full border-8 border-slate-800 mt-4"
          :src="authStore.userPhotoUrl" alt="Avatar">
        <h2 class="block text-2xl font-bold text-white">Hello, {{ authStore.userFirstLastName }}!</h2>
      </div>
      <div class="grid grid-cols-3 grid-rows-2">
        <!-- SinglePlayer -->
        <div id="hs-sign-out-alert-small-window" class="size-full overflow-x-hidden pb-14 px-12" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-2xl shadow-cyan-200 rounded-xl bg-white">
              <div class="p-4 sm:p-10 text-center overflow-y-auto ">
                <!-- Icon -->
                <span
                  class="mb-4 inline-flex justify-center items-center size-[62px] rounded-xl  border-yellow-50 text-yellow-500 bg-slate-800">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M12 13C14.7614 13 17 10.7614 17 8C17 5.23858 14.7614 3 12 3C9.23858 3 7 5.23858 7 8C7 10.7614 9.23858 13 12 13Z"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                      d="M20 21C20 18.8783 19.1571 16.8434 17.6569 15.3431C16.1566 13.8429 14.1217 13 12 13C9.87827 13 7.84344 13.8429 6.34315 15.3431C4.84285 16.8434 4 18.8783 4 21"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </span>
                <!-- End Icon -->

                <h3 id="hs-sign-out-alert-small-window-label" class="mb-2 text-2xl font-bold text-gray-800">
                  Singleplayer
                </h3>
                <div class="mt-6 grid gap-y-2">
                  <RouterLink :to="{name: 'modes'}">
                    <button type="button"
                      class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                      Start
                    </button>
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- MultiPlayer -->
        <div id="hs-sign-out-alert-small-window" class="size-full overflow-x-hidden pb-14 px-12" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-2xl shadow-cyan-200 rounded-xl bg-white">
              <div class="p-4 sm:p-10 text-center overflow-y-auto ">
                <!-- Icon -->
                <span
                  class="mb-4 inline-flex justify-center items-center size-[62px] rounded-xl  border-yellow-50 text-yellow-500 bg-slate-800">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M14 19C14 17.4087 13.3679 15.8826 12.2426 14.7574C11.1174 13.6321 9.5913 13 8 13C6.4087 13 4.88258 13.6321 3.75736 14.7574C2.63214 15.8826 2 17.4087 2 19"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                      d="M8 13C10.2091 13 12 11.2091 12 9C12 6.79086 10.2091 5 8 5C5.79086 5 4 6.79086 4 9C4 11.2091 5.79086 13 8 13Z"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                      d="M22 19C22 17.4087 21.3679 15.8826 20.2426 14.7574C19.1174 13.6321 17.5913 13 16 13C17.0609 13 18.0783 12.5786 18.8284 11.8284C19.5786 11.0783 20 10.0609 20 9C20 7.93913 19.5786 6.92172 18.8284 6.17157C18.0783 5.42143 17.0609 5 16 5"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </span>
                <!-- End Icon -->

                <h3 id="hs-sign-out-alert-small-window-label" class="mb-2 text-2xl font-bold text-gray-800">
                  Multiplayer
                </h3>
                <div class="mt-6 grid gap-y-2">
                  <RouterLink :to="{name: 'modes'}">
                    <button type="button"
                      class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                      Find Lobby
                    </button>
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Coins -->
        <div id="hs-sign-out-alert-small-window" class="size-full overflow-x-hidden pb-14 px-12" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-2xl shadow-cyan-200 rounded-xl bg-white">
              <div class="p-4 sm:p-10 text-center overflow-y-auto ">
                <!-- Icon -->
                <span
                  class="mb-4 inline-flex justify-center items-center size-[62px] rounded-xl  border-yellow-50 text-yellow-500 bg-slate-800">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M8 14C11.3137 14 14 11.3137 14 8C14 4.68629 11.3137 2 8 2C4.68629 2 2 4.68629 2 8C2 11.3137 4.68629 14 8 14Z"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                      d="M18.09 10.3701C19.0353 10.7225 19.8765 11.3076 20.5357 12.0713C21.195 12.835 21.651 13.7526 21.8617 14.7392C22.0724 15.7258 22.0309 16.7496 21.741 17.716C21.4512 18.6823 20.9223 19.5599 20.2034 20.2677C19.4845 20.9755 18.5987 21.4906 17.6279 21.7653C16.6572 22.0401 15.6329 22.0656 14.6496 21.8396C13.6664 21.6135 12.7561 21.1432 12.0028 20.4721C11.2495 19.801 10.6776 18.9508 10.34 18.0001"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M7 6H8V10" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M16.71 13.8799L17.41 14.5899L14.59 17.4099" stroke="white" stroke-linecap="round"
                      stroke-linejoin="round" />
                  </svg>



                </span>
                <!-- End Icon -->

                <h3 id="hs-sign-out-alert-small-window-label" class="mb-2 text-2xl font-bold text-gray-800">
                  Coins
                </h3>
                <div class="mt-6 grid gap-y-2">
                  <RouterLink :to="{ name: 'buy-coins' }">
                    <button type="button"
                      class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                      Buy
                    </button>
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Scoreboards -->
        <div id="hs-sign-out-alert-small-window" class="size-full overflow-x-hidden pb-14 px-12" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-2xl shadow-cyan-200 rounded-xl bg-white">
              <div class="p-4 sm:p-10 text-center overflow-y-auto ">
                <!-- Icon -->
                <span
                  class="mb-4 inline-flex justify-center items-center size-[62px] rounded-xl  border-yellow-50 text-yellow-500 bg-slate-800">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M6 9H4.5C3.83696 9 3.20107 8.73661 2.73223 8.26777C2.26339 7.79893 2 7.16304 2 6.5C2 5.83696 2.26339 5.20107 2.73223 4.73223C3.20107 4.26339 3.83696 4 4.5 4H6"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                      d="M18 9H19.5C20.163 9 20.7989 8.73661 21.2678 8.26777C21.7366 7.79893 22 7.16304 22 6.5C22 5.83696 21.7366 5.20107 21.2678 4.73223C20.7989 4.26339 20.163 4 19.5 4H18"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M4 22H20" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M10 14.66V17C10 17.55 9.53 17.98 9.03 18.21C7.85 18.75 7 20.24 7 22" stroke="white"
                      stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M14 14.66V17C14 17.55 14.47 17.98 14.97 18.21C16.15 18.75 17 20.24 17 22" stroke="white"
                      stroke-linecap="round" stroke-linejoin="round" />
                    <path
                      d="M18 2H6V9C6 10.5913 6.63214 12.1174 7.75736 13.2426C8.88258 14.3679 10.4087 15 12 15C13.5913 15 15.1174 14.3679 16.2426 13.2426C17.3679 12.1174 18 10.5913 18 9V2Z"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>



                </span>
                <!-- End Icon -->

                <h3 id="hs-sign-out-alert-small-window-label" class="mb-2 text-2xl font-bold text-gray-800">
                  Scoreboards
                </h3>
                <div class="mt-6 grid gap-y-2">
                  <RouterLink :to="{ name: 'leaderboard' }">
                    <button type="button"
                      class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                      Check
                    </button>
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Game History -->
        <div id="hs-sign-out-alert-small-window" class="size-full overflow-x-hidden pb-14 px-12" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-2xl shadow-cyan-200 rounded-xl bg-white">
              <div class="p-4 sm:p-10 text-center overflow-y-auto ">
                <!-- Icon -->
                <span
                  class="mb-4 inline-flex justify-center items-center size-[62px] rounded-xl  border-yellow-50 text-yellow-500 bg-slate-800">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M8 21H20C20.5304 21 21.0391 20.7893 21.4142 20.4142C21.7893 20.0391 22 19.5304 22 19V17H10V19C10 19.5304 9.78929 20.0391 9.41421 20.4142C9.03914 20.7893 8.53043 21 8 21ZM8 21C7.46957 21 6.96086 20.7893 6.58579 20.4142C6.21071 20.0391 6 19.5304 6 19V5C6 4.46957 5.78929 3.96086 5.41421 3.58579C5.03914 3.21071 4.53043 3 4 3C3.46957 3 2.96086 3.21071 2.58579 3.58579C2.21071 3.96086 2 4.46957 2 5V8H6"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M19 17V5C19 4.46957 18.7893 3.96086 18.4142 3.58579C18.0391 3.21071 17.5304 3 17 3H4"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M15 8H10" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M15 12H10" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>

                </span>
                <!-- End Icon -->

                <h3 id="hs-sign-out-alert-small-window-label" class="mb-2 text-2xl font-bold text-gray-800">
                  Game History
                </h3>
                <div class="mt-6 grid gap-y-2">
                  <RouterLink :to="{ name: 'history' }">
                    <button type="button"
                      class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                      Access
                    </button>
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Settings -->
        <div id="hs-sign-out-alert-small-window" class="size-full overflow-x-hidden pb-14 px-12" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-2xl shadow-cyan-200 rounded-xl bg-white">
              <div class="p-4 sm:p-10 text-center overflow-y-auto ">
                <!-- Icon -->
                <span
                  class="mb-4 inline-flex justify-center items-center size-[62px] rounded-xl  border-yellow-50 text-yellow-500 bg-slate-800">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M12.22 2H11.78C11.2496 2 10.7409 2.21071 10.3658 2.58579C9.99072 2.96086 9.78 3.46957 9.78 4V4.18C9.77964 4.53073 9.68706 4.87519 9.51154 5.17884C9.33602 5.48248 9.08374 5.73464 8.78 5.91L8.35 6.16C8.04596 6.33554 7.70108 6.42795 7.35 6.42795C6.99893 6.42795 6.65404 6.33554 6.35 6.16L6.2 6.08C5.74107 5.81526 5.19584 5.74344 4.684 5.88031C4.17217 6.01717 3.73555 6.35154 3.47 6.81L3.25 7.19C2.98526 7.64893 2.91345 8.19416 3.05031 8.706C3.18717 9.21783 3.52154 9.65445 3.98 9.92L4.13 10.02C4.43228 10.1945 4.68362 10.4451 4.85905 10.7468C5.03448 11.0486 5.1279 11.391 5.13 11.74V12.25C5.1314 12.6024 5.03965 12.949 4.86405 13.2545C4.68844 13.5601 4.43521 13.8138 4.13 13.99L3.98 14.08C3.52154 14.3456 3.18717 14.7822 3.05031 15.294C2.91345 15.8058 2.98526 16.3511 3.25 16.81L3.47 17.19C3.73555 17.6485 4.17217 17.9828 4.684 18.1197C5.19584 18.2566 5.74107 18.1847 6.2 17.92L6.35 17.84C6.65404 17.6645 6.99893 17.5721 7.35 17.5721C7.70108 17.5721 8.04596 17.6645 8.35 17.84L8.78 18.09C9.08374 18.2654 9.33602 18.5175 9.51154 18.8212C9.68706 19.1248 9.77964 19.4693 9.78 19.82V20C9.78 20.5304 9.99072 21.0391 10.3658 21.4142C10.7409 21.7893 11.2496 22 11.78 22H12.22C12.7504 22 13.2591 21.7893 13.6342 21.4142C14.0093 21.0391 14.22 20.5304 14.22 20V19.82C14.2204 19.4693 14.3129 19.1248 14.4885 18.8212C14.664 18.5175 14.9163 18.2654 15.22 18.09L15.65 17.84C15.954 17.6645 16.2989 17.5721 16.65 17.5721C17.0011 17.5721 17.346 17.6645 17.65 17.84L17.8 17.92C18.2589 18.1847 18.8042 18.2566 19.316 18.1197C19.8278 17.9828 20.2645 17.6485 20.53 17.19L20.75 16.8C21.0147 16.3411 21.0866 15.7958 20.9497 15.284C20.8128 14.7722 20.4785 14.3356 20.02 14.07L19.87 13.99C19.5648 13.8138 19.3116 13.5601 19.136 13.2545C18.9604 12.949 18.8686 12.6024 18.87 12.25V11.75C18.8686 11.3976 18.9604 11.051 19.136 10.7455C19.3116 10.4399 19.5648 10.1862 19.87 10.01L20.02 9.92C20.4785 9.65445 20.8128 9.21783 20.9497 8.706C21.0866 8.19416 21.0147 7.64893 20.75 7.19L20.53 6.81C20.2645 6.35154 19.8278 6.01717 19.316 5.88031C18.8042 5.74344 18.2589 5.81526 17.8 6.08L17.65 6.16C17.346 6.33554 17.0011 6.42795 16.65 6.42795C16.2989 6.42795 15.954 6.33554 15.65 6.16L15.22 5.91C14.9163 5.73464 14.664 5.48248 14.4885 5.17884C14.3129 4.87519 14.2204 4.53073 14.22 4.18V4C14.22 3.46957 14.0093 2.96086 13.6342 2.58579C13.2591 2.21071 12.7504 2 12.22 2Z"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                      d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>



                </span>
                <!-- End Icon -->

                <h3 id="hs-sign-out-alert-small-window-label" class="mb-2 text-2xl font-bold text-gray-800">
                  Settings
                </h3>
                <div class="mt-6 grid gap-y-2">
                  <RouterLink :to="{ name: 'settings' }">
                    <button type="button"
                      class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                      Open
                    </button>
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>