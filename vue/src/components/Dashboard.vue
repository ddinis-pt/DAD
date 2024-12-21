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

const isPlayer = () => {
  if (authStore.user === null) {
    return false
  }
  return authStore.userType === 'P'
}

const isAdmin = () => {
  if (authStore.user === null) {
    return false
  }
  return authStore.userType === 'A'
}

const isGuest = () => {
  if (authStore.user === null) {
    return true
  }
  return false
}

</script>

<template>

  <div class="min-h-screen md:grid bg-sky-50 dark:bg-gray-800">
    <Header></Header>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 content-top">
      <div class="grid md:grid-cols-3 grid-rows-2 sm:grid-cols-1 gap-4">
        <!-- Users -->
        <div v-if="isAdmin()" id="hs-sign-out-alert-small-window" class="size-full px-6 pb-2" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-[0px_0px_14px_5px_#a5f3fc] rounded-xl bg-white dark:bg-slate-800">
              <div class="p-4 sm:p-10 text-center overflow-y-auto ">
                <!-- Icon -->
                <span
                class="mb-4 inline-flex justify-center items-center size-[62px] rounded-xl text-white bg-slate-800 dark:bg-white dark:text-slate-800">
                  <i class="pi pi-user-edit !text-2xl"></i>
                </span>
                <!-- End Icon -->
                <h3 id="hs-sign-out-alert-small-window-label" class="mb-2 text-2xl font-bold text-gray-800 dark:text-white">
                  Users
                </h3>
                <div class="mt-6 grid gap-y-2">
                  <RouterLink :to="{ name: 'users' }">
                    <button type="button"
                      class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                      See users
                    </button>
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- SinglePlayer -->
        <div v-if="!isAdmin() || isGuest()" id="hs-sign-out-alert-small-window" class="size-full px-6 pb-2" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-[0px_0px_14px_5px_#a5f3fc] rounded-xl bg-white dark:bg-slate-800">
              <div class="p-4 sm:p-10 text-center overflow-y-auto ">
                <!-- Icon -->
                <span
                class="mb-4 inline-flex justify-center items-center size-[62px] rounded-xl text-white bg-slate-800 dark:bg-white dark:text-slate-800">
                  <i class="pi pi-user !text-2xl"></i>
                </span>
                <!-- End Icon -->

                <h3 id="hs-sign-out-alert-small-window-label" class="mb-2 text-2xl font-bold text-gray-800 dark:text-white">
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
        <div v-if="!isAdmin()" id="hs-sign-out-alert-small-window" class="size-full px-6 pb-2" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-[0px_0px_14px_5px_#a5f3fc] rounded-xl bg-white dark:bg-slate-800">
              <div class="p-4 sm:p-10 text-center overflow-y-auto ">
                <!-- Icon -->
                <span
                class="mb-4 inline-flex justify-center items-center size-[62px] rounded-xl text-white bg-slate-800 dark:bg-white dark:text-slate-800">
                  <i class="pi pi-users !text-2xl"></i>
                </span>
                <!-- End Icon -->

                <h3 id="hs-sign-out-alert-small-window-label" class="mb-2 text-2xl font-bold text-gray-800 dark:text-white">
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
            <div class="relative flex flex-col shadow-[0px_0px_14px_5px_#a5f3fc] rounded-xl bg-white dark:bg-slate-800">
              <div class="p-4 sm:p-10 text-center overflow-y-auto ">
                <!-- Icon -->
                <span
                class="mb-4 inline-flex justify-center items-center size-[62px] rounded-xl text-white bg-slate-800 dark:bg-white dark:text-slate-800">
                  <i class="pi pi-trophy !text-2xl"></i>
                </span>
                <!-- End Icon -->

                <h3 id="hs-sign-out-alert-small-window-label" class="mb-2 text-2xl font-bold text-gray-800 dark:text-white">
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
            <div class="relative flex flex-col shadow-[0px_0px_14px_5px_#a5f3fc] rounded-xl bg-white dark:bg-slate-800">
              <div class="p-4 sm:p-10 text-center overflow-y-auto ">
                <!-- Icon -->
                <span
                class="mb-4 inline-flex justify-center items-center size-[62px] rounded-xl text-white bg-slate-800 dark:bg-white dark:text-slate-800">
                  <i class="pi pi-book !text-2xl"></i>
                </span>
                <!-- End Icon -->

                <h3 id="hs-sign-out-alert-small-window-label" class="mb-2 text-2xl font-bold text-gray-800 dark:text-white">
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
        <!-- Information -->
        <div v-if="isAdmin()" id="hs-sign-out-alert-small-window" class="size-full px-6 pb-2" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-[0px_0px_14px_5px_#a5f3fc] rounded-xl bg-white dark:bg-slate-800">
              <div class="p-4 sm:p-10 text-center overflow-y-auto ">
                <!-- Icon -->
                <span
                class="mb-4 inline-flex justify-center items-center size-[62px] rounded-xl text-white bg-slate-800 dark:bg-white dark:text-slate-800">
                  <i class="pi pi-table !text-2xl"></i>
                </span>
                <!-- End Icon -->
                <h3 id="hs-sign-out-alert-small-window-label" class="mb-2 text-2xl font-bold text-gray-800 dark:text-white">
                  Information
                </h3>
                <div class="mt-6 grid gap-y-2">
                  <RouterLink :to="{ name: 'information' }">
                    <button type="button"
                      class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                      See information
                    </button>
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Statistics -->
        <div id="hs-sign-out-alert-small-window" class="size-full px-6 pb-2" role="dialog"
          tabindex="-1" aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col shadow-[0px_0px_14px_5px_#a5f3fc] rounded-xl bg-white dark:bg-slate-800">
              <div class="p-4 sm:p-10 text-center overflow-y-auto ">
                <!-- Icon -->
                <span
                class="mb-4 inline-flex justify-center items-center size-[62px] rounded-xl text-white bg-slate-800 dark:bg-white dark:text-slate-800">
                  
                  <i class="pi pi-chart-bar border-white" style="font-size: 1.5rem"></i>

                </span>
                <!-- End Icon -->

                <h3 id="hs-sign-out-alert-small-window-label" class="mb-2 text-2xl font-bold text-gray-800 dark:text-white">
                  Statistics
                </h3>
                <div class="mt-6 grid gap-y-2">
                  <RouterLink :to="{ name: 'statistics' }">
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