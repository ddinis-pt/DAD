<script setup>
import { onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { useErrorStore } from '@/stores/error';
const errorStore = useErrorStore()
const authStore = useAuthStore()

const router = useRouter()

const email = ref('')

const password = ref('')
const responseData = ref('')

const continueAsGuest = async () => {
  router.push({ name: 'dashboard' })
}

const submit = async () => {
  let user = null
  try {
    user = await authStore.login({
      email: email.value,
      password: password.value
    })
  } catch (error) {
    responseData.value = 'Unable to login, please try again later'
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
}

onMounted(() => {
  if (authStore.user) {
    authStore.logout()
  }
  document.title = 'Memory Card Game | Login'
})

</script>
<template>
  <Toaster />
  <div class="flex items-center w-full justify-center bg-sky-50 dark:bg-gray-800 min-h-screen min-w-screen">
    <main class="w-full max-w-md px-6 py-4 rounded-xl shadow-md bg-white">
      <div class="flex flex-col space-y-2">
        <div class="flex items-center justify-center bg-gray-800 rounded-xl py-2">
          <img class="flex-none inline-block h-8" src="../../assets/cards-light.png" alt="The Memory Game Logo" />
        </div>
        <div class="text-center py-4">
          <h2 class="block text-2xl font-bold text-gray-800">Log into your account</h2>
        </div>
        <div class="mt-5">
          <form>
            <div class="grid gap-y-4">
              <div>
                <label for="email" class="block text-sm mb-2 text-gray-800">Email</label>
                <div class="relative">
                  <input v-model="email" type="text" id="email" name="email" placeholder="Your email goes here"
                    class="appearance-none py-3 px-4 block w-full border border-gray-500 rounded-lg text-sm focus:border-blue-600 dark:bg-gray-800"
                    required aria-describedby="email-error">
                  <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                    <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
                      aria-hidden="true">
                      <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                    </svg>
                  </div>
                </div>
              </div>
              <div>
                <div class="flex justify-between items-center">
                  <label for="password" class="block text-sm mb-2 text-gray-800">Password</label>
                </div>
                <div class="relative">
                  <input v-model="password" type="password" id="password" name="password"
                    placeholder="&#9679; &#9679; &#9679; &#9679; &#9679; &#9679; &#9679; &#9679; &#9679; "
                    class="py-3 px-4 block w-full border border-gray-500 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 disabled:opacity-50 disabled:pointer-events-none placeholder-white-100"
                    required aria-describedby="password-error">
                  <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                    <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
                      aria-hidden="true">
                      <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                    </svg>
                  </div>
                </div>
                <p class="hidden text-xs text-red-600 mt-2" id="error">{{ responseData }}</p>
              </div>
              <div class="flex items-center">
                <div class="flex">
                  <input id="remember-me" name="remember-me" type="checkbox" checked
                    class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 bg-neutral-800 checked:bg-blue-500 checked:border-blue-500 focus:ring-offset-gray-800">
                </div>
                <div class="ms-3">
                  <label for="remember-me" class="text-sm text-gray-800">Remember me</label>
                </div>
              </div>
              <button @click.prevent="submit"
                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                Log in
              </button>
              <div class="flex items-center text-xs uppercase text-neutral-500 py-3">
                <div class="flex-1 border-t border-blue-600 rounded-full border-2"></div>
              </div>
              <RouterLink :to="{ name: 'register' }"
                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-blue-text bg-white text-blue-text hover:bg-blue-text hover:text-white focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                Register
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