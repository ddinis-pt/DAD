<script setup>
import { useTemplateRef, provide, inject } from 'vue'
import { RouterView } from 'vue-router'
import { useChatStore } from '@/stores/chat'
import GlobalAlertDialog from '@/components/common/GlobalAlertDialog.vue'
import GlobalInputDialog from '@/components/common/GlobalInputDialog.vue'

const storeChat = useChatStore()

const socket = inject('socket')
let userDestination = null

const alertDialog = useTemplateRef('alert-dialog')
provide('alertDialog', alertDialog)

const inputDialog = useTemplateRef('input-dialog')
provide('inputDialog', inputDialog)

socket.on('privateMessage', (messageObj) => {
  userDestination = messageObj.user
  inputDialog.value.open(
    handleMessageFromInputDialog,
    'Message from ' + messageObj.user.name,
    `This is a private message sent by ${messageObj?.user?.name}!`,
    'Reply Message', '',
    'Close', 'Reply',
    messageObj.message
  )
})

const handleMessageFromInputDialog = (message) => {
  storeChat.sendPrivateMessageToUser(userDestination, message)
}

</script>

<template>
  <GlobalAlertDialog ref="alert-dialog"></GlobalAlertDialog>
  <GlobalInputDialog ref="input-dialog"></GlobalInputDialog>
  <RouterView />
</template>