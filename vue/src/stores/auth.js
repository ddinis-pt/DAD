import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'
import { useErrorStore } from '@/stores/error'

import avatarNoneAssetURL from '@/assets/avatar-none.png'

export const useAuthStore = defineStore('auth', () => {
  const storeError = useErrorStore()

  const socket = inject('socket')

  const user = ref(JSON.parse(sessionStorage.getItem('user')) || null)
  const token = ref(sessionStorage.getItem('token') || '')

  if (token.value) {
    axios.defaults.headers.common.Authorization = 'Bearer ' + token.value
  }

  const userId = computed(() => {
    return user.value ? user.value.id : -1
})

  const userName = computed(() => {
    return user.value ? user.value.name : ''
  })

  const userNickname = computed(() => {
    return user.value ? user.value.nickname : ''
  })

  const userPassword = computed(() => {
    return user.value ? user.value.password : ''
  })

  const getFirstLastName = (fullName) => {
    const names = fullName.trim().split(' ')
    const firstName = names[0] ?? ''
    const lastName = names.length > 1 ? names[names.length -1 ] : ''
    return (firstName + ' ' + lastName).trim()
}

  const userFirstLastName = computed(() => {
    return getFirstLastName(userName.value)
  })

  const userEmail = computed(() => {
    return user.value ? user.value.email : ''
  })

  const userCoins = computed(() => {
    return user.value ? user.value.brain_coins_balance : ''
  })

  const userType = computed(() => {
    return user.value ? user.value.type : ''
  })

  const userGender = computed(() => {
    return user.value ? user.value.gender : ''
  })

  const userPhotoUrl = computed(() => {
    const photoFile = user.value ? (user.value.photo_filename ?? '') : ''
    if (photoFile) {
      return axios.defaults.baseURL.replaceAll('/api', '/storage/photos/' + photoFile)
    }
    return avatarNoneAssetURL
  })

  // This function is "private" - not exported by the store
  const clearUser = () => {
    resetIntervalToRefreshToken()
    if (user.value) {
      socket.emit('logout', user.value)
    }
    user.value = null
    token.value = ''
    sessionStorage.removeItem('user')
    sessionStorage.removeItem('token')
    axios.defaults.headers.common.Authorization = ''
  }

  const login = async (credentials) => {
    storeError.resetMessages()
    try {
      const responseLogin = await axios.post('auth/login', credentials)
      token.value = responseLogin.data.token
      axios.defaults.headers.common.Authorization = 'Bearer ' + token.value
      const responseUser = await axios.get('users/me')
      user.value = responseUser.data
      socket.emit('login', user.value)
      sessionStorage.setItem('token', token.value)
      sessionStorage.setItem('user', JSON.stringify(user.value))
      repeatRefreshToken()
      return user.value
    } catch (e) {
      clearUser()
      storeError.setErrorMessages(
        e.response.data.message,
        e.response.data.errors,
        e.response.status,
        'Authentication Error!'
      )
      return false
    }
  }

  const register = async (credentials) => {
    storeError.resetMessages()
    try {
      const responseRegister = await axios.post('auth/register', credentials)
      token.value = responseRegister.data.token
      axios.defaults.headers.common.Authorization = 'Bearer ' + token.value
      const responseUser = await axios.get('users/me')
      user.value = responseUser.data
      socket.emit('register', user.value)
      sessionStorage.setItem('token', token.value)
      sessionStorage.setItem('user', JSON.stringify(user.value))
      repeatRefreshToken()
      return user.value
    } catch (e) {
      clearUser()
      storeError.setErrorMessages(
        e.response.data.message,
        e.response.data.errors,
        e.response.status,
        'Authentication Error!'
      )
      return false
    }
  }

  const logout = async (user) => {
    storeError.resetMessages()
    try {
      await axios.post('auth/logout', user)
      clearUser()
      return true
    } catch (e) {
      clearUser()
      storeError.setErrorMessages(
        e.response.data.message,
        [],
        e.response.status,
        'Authentication Error!'
      )
      return false
    }
  }

  let intervalToRefreshToken = null

  const resetIntervalToRefreshToken = () => {
    if (intervalToRefreshToken) {
      clearInterval(intervalToRefreshToken)
    }
    intervalToRefreshToken = null
  }

  const repeatRefreshToken = () => {
    if (intervalToRefreshToken) {
      clearInterval(intervalToRefreshToken)
    }
    intervalToRefreshToken = setInterval(
      async () => {
        try {
          const response = await axios.post('auth/refreshtoken')
          token.value = response.data.token
          axios.defaults.headers.common.Authorization = 'Bearer ' + token.value
          sessionStorage.setItem('token', token.value);
          return true
        } catch (e) {
          clearUser()
          storeError.setErrorMessages(
            e.response.data.message,
            e.response.data.errors,
            e.response.status,
            'Authentication Error!'
          )
          return false
        }
      },
      1000 * 60 * 110
    )
    return intervalToRefreshToken
  }

  const restoreToken = async function () {
    let storedToken = sessionStorage.getItem('token')
    if (storedToken) {
      try {
        token.value = storedToken
        axios.defaults.headers.common.Authorization = 'Bearer ' + token.value
        const responseUser = await axios.get('users/me')
        user.value = responseUser.data
        socket.emit('login', user.value)
        repeatRefreshToken()
        return true
      } catch {
        clearUser()
        return false
      }
    }
    return false
  }

  const refreshUserData = async () => {
    try {
      const responseUser = await axios.get('/users/me')
      user.value = responseUser.data
      sessionStorage.setItem('user', JSON.stringify(user.value))
    } catch (e) {
      storeError.setErrorMessages(
        e.response.data.message,
        e.response.data.errors,
        e.response.status,
        'Error refreshing user data!'
      )
    }
  }

  return {
    user,
    userId,
    userName,
    userFirstLastName,
    userEmail,
    userType,
    userGender,
    userPhotoUrl,
    userCoins,
    userNickname,
    userPassword,
    login,
    logout,
    restoreToken,
    getFirstLastName,
    register,
    refreshUserData
  }
})
