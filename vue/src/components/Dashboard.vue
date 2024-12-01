<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router';
import { useErrorStore } from '@/stores/error';
import Header from '@/components/ui/Header.vue'
import Footer from '@/components/ui/Footer.vue';

const router = useRouter()
const errorStore = useErrorStore()
const authStore = useAuthStore()

const logout = async () => {
  if(authStore.user) {
    if(!await authStore.logout(authStore.user))
        console.log(errorStore.message + ' ' + errorStore.statusCode)
  }
  router.push({name: 'login'})
}
</script>

<template>
  
  <div class="min-h-screen grid content-between">
    <Header></Header>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="authStore.user" class="text-center">
        <h2 class="block text-2xl font-bold text-gray-800 dark:text-white">Hello {{ authStore.userFirstLastName }}!</h2>
      </div>
      <div class="flex flex-col gap-y-2">
        <div class="inline-flex flex-wrap gap-2">



        </div>

        <div id="hs-sign-out-alert-small-window  " class="size-full overflow-x-hidden p-16 " role="dialog" tabindex="-1"
          aria-labelledby="hs-sign-out-alert-small-window-label">
          <div class="mt-7 opacity-100 duration-500 sm:max-w-xs sm:w-full m-3 sm:mx-auto  ">
            <div
              class="relative flex flex-col bg-white shadow-2xl shadow-cyan-200 rounded-xl dark:bg-neutral-900">
              <div class="p-4 sm:p-10 text-center overflow-y-auto ">
                <!-- Icon -->
                <span
                  class="mb-4 inline-flex justify-center items-center size-[62px] rounded-xl  border-yellow-50 bg-slate-800 text-yellow-500 dark:bg-yellow-700 dark:border-yellow-600 dark:text-yellow-100 ">
                  <svg width="48" height="48" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8 14.6666H13.3333" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M10.6667 12V17.3333" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M20 16H20.0133" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M24 13.3334H24.0133" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M23.0933 6.66663H8.90666C7.58703 6.66693 6.31437 7.15646 5.3347 8.04058C4.35503 8.92469 3.73792 10.1406 3.60266 11.4533C3.59466 11.5226 3.58932 11.588 3.57999 11.656C3.47199 12.5546 2.66666 19.2746 2.66666 21.3333C2.66666 22.3942 3.08808 23.4116 3.83823 24.1617C4.58838 24.9119 5.60579 25.3333 6.66666 25.3333C7.99999 25.3333 8.66666 24.6666 9.33332 24L11.2187 22.1146C11.7186 21.6145 12.3968 21.3334 13.104 21.3333H18.896C19.6032 21.3334 20.2813 21.6145 20.7813 22.1146L22.6667 24C23.3333 24.6666 24 25.3333 25.3333 25.3333C26.3942 25.3333 27.4116 24.9119 28.1618 24.1617C28.9119 23.4116 29.3333 22.3942 29.3333 21.3333C29.3333 19.2733 28.528 12.5546 28.42 11.656C28.4107 11.5893 28.4053 11.5226 28.3973 11.4546C28.2624 10.1417 27.6454 8.92546 26.6657 8.04107C25.686 7.15668 24.4132 6.66697 23.0933 6.66663Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

                  
                </span>
                <!-- End Icon -->

                <h3 id="hs-sign-out-alert-small-window-label"
                  class="mb-2 text-2xl font-bold text-gray-800 dark:text-neutral-200">
                  Start New Game?
                </h3>
                <div class="mt-6 grid gap-y-2">
                  <RouterLink to="/modes">
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
          <button @click.prevent="logout" type="button"
            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
            Go Back
          </button>
      </div>
    </main>
    <Footer></Footer>
  </div>

</template>