import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import { useErrorStore } from '@/stores/error'
import { useAuthStore } from '@/stores/auth'

export const useChatStore = defineStore('chat', () => {
    const storeAuth = useAuthStore()
    const storeError = useErrorStore()

    const socket = inject('socket')

    const messages = ref([])

    const totalMessages = computed(() => messages.value.length)

    const addMessageToArray = (messageObj) => {
        if (totalMessages.value >= 10) {
            messages.value.shift();
        }
        messages.value.push(messageObj)
    }
    
    const sendMessageToChat = (message) => {
        console.log('Sending message:', message)
        storeError.resetMessages()
        socket.emit('chatMessage', message)
    }

    socket.on('chatMessage', (messageObj) => {
        console.log('Received message:', messageObj)
        addMessageToArray(messageObj)
    })

    const sendPrivateMessageToUser = (destinationUser, message) => {
        storeError.resetMessages()
        // Send the message to the Websocket server
        // Payload object format:
        // {
        //     destinationUser: destinationUser,
        //     message: 'text message to send'
        // }
    }

    return {
        messages, totalMessages, sendMessageToChat, sendPrivateMessageToUser
    }
})
