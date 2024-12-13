<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router';
import { useErrorStore } from '@/stores/error';
import Header from '@/components/ui/Header.vue'
import Footer from '@/components/ui/Footer.vue';
import Chat from '@/components/ui/chat/Chat.vue';
import { onMounted } from 'vue';

const router = useRouter()
const errorStore = useErrorStore()
const authStore = useAuthStore()

onMounted(() => {
  window.HSStaticMethods.autoInit();
})
</script>

<template>

  <div class="min-h-screen md:grid bg-gray-800">
    <Header></Header>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 content-top">
      <div class="grid md:grid-cols-2 grid-rows-2 sm:grid-cols-1 gap-4">
        <!-- SinglePlayer -->
        <div id="hs-sign-out-alert-small-window" class="size-full px-6 pb-2" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-[0px_0px_14px_5px_#a5f3fc] rounded-xl bg-white">
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
                  <RouterLink :to="{ name: 'modes' }">
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
        <div id="hs-sign-out-alert-small-window" class="size-full px-6 pb-2" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-[0px_0px_14px_5px_#a5f3fc] rounded-xl bg-white">
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
                  <RouterLink :to="{ name: 'multiplayer' }">
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
        <!-- Scoreboards -->
        <div id="hs-sign-out-alert-small-window" class="size-full px-6 pb-2" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-[0px_0px_14px_5px_#a5f3fc] rounded-xl bg-white">
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
        <div id="hs-sign-out-alert-small-window" class="size-full px-6 pb-2" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-[0px_0px_14px_5px_#a5f3fc] rounded-xl bg-white">
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
        
      </div>
    </main>
  </div>
</template>