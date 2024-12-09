<template>
    <div class="settings">
        <h1>Settings</h1>
        <form @submit.prevent="saveSettings">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" v-model="settings.username" />
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" v-model="settings.email" />
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" v-model="settings.password" />
            </div>
            <button type="submit">Save</button>
        </form>
    </div>
</template>
<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const router = useRouter()
const authStore = useAuthStore()

const settings = ref({
    username: authStore.user.username,
    email: authStore.user.email,
    password: ''
})

const saveSettings = async () => {
    await authStore.updateUser(settings.value)
    router.push({ name: 'dashboard' })
}

</script>