import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import { format } from 'date-fns'
import axios from 'axios'
import { useErrorStore } from '@/stores/error'
import { useAuthStore } from '@/stores/auth'

export const useLobbyStore = defineStore('lobby', () => {
  const storeAuth = useAuthStore()
  const storeError = useErrorStore()
  const socket = inject('socket')

  const games = ref([])

  const totalGames = computed(() => games.value.length)

  const webSocketServerResponseHasError = (response) => {
    if (response.errorCode) {
      storeError.setErrorMessages(response.errorMessage, [], response.errorCode)
      return true
    }
    return false
  }

  // when the lobby changes on the server, it is updated on the client
  socket.on('lobbyChanged', (lobbyGames) => {
    games.value = lobbyGames
  })

  // fetch lobby games from the Websocket server
  const fetchGames = () => {
    storeError.resetMessages()
    socket.emit('fetchGames', (response) => {
      if (webSocketServerResponseHasError(response)) {
        return
      }
      games.value = response
    })
  }

  // add a game to the lobby
  const addGame = () => {
    storeError.resetMessages()
    socket.emit('addGame', (response) => {
      if (webSocketServerResponseHasError(response)) {
        return
      }
    })
  }

  // remove a game from the lobby
  const removeGame = (id) => {
    storeError.resetMessages()
    socket.emit('removeGame', id, (response) => {
      if (webSocketServerResponseHasError(response)) {
        return
      }
    })
  }

  // join a game of the lobby
  const joinGame = async (id) => {
    storeError.resetMessages()
    socket.emit('joinGame', id, async (response) => {
      // callback executed after the join is complete
      if (webSocketServerResponseHasError(response)) {
        return
      }
      const APIresponse = await axios.post('games', {
        created_user_id: storeAuth.user.id,
        type: 'M',
        status: 'PL',
        began_at: format(new Date(), 'yyyy-MM-dd HH:mm:ss'),
        board_id: 1,
      })
      const newGameOnDB = APIresponse.data
      newGameOnDB.player1 = response.player1.id
      newGameOnDB.player2 = response.player2.id
      newGameOnDB.player1SocketId = response.player1SocketId
      newGameOnDB.player2SocketId = response.player2SocketId
      // subtract 5 coins from each user
      await axios.put(`/users/${newGameOnDB.player1}/spend/5`)
      await axios.put(`/users/${newGameOnDB.player2}/spend/5`)
      await axios.post('/registerTransaction', {
        transaction_datetime: format(new Date(), 'yyyy-MM-dd HH:mm:ss'),
        user_id: newGameOnDB.player1,
        type: 'I',
        game_id: newGameOnDB.id,
        brain_coins: -5,
      })
      await axios.post('/registerTransaction', {
        transaction_datetime: format(new Date(), 'yyyy-MM-dd HH:mm:ss'),
        user_id: newGameOnDB.player2,
        type: 'I',
        game_id: newGameOnDB.id,
        brain_coins: -5,
      })
      storeAuth.refreshUserData()
      // After adding the game to the DB emit a message to the server to start the game
      socket.emit('startGame', newGameOnDB, (startedGame) => {
        if (webSocketServerResponseHasError(startedGame)) {
          return
        }
      })
    })
  }

  // Whether the current user can remove a specific game from the lobby
  const canRemoveGame = (game) => {
    return game.player1.id === storeAuth.user.id
  }

  // Whether the current user can join a specific game from the lobby
  const canJoinGame = (game) => {
    return storeAuth.user && game.player1.id !== storeAuth.user.id && storeAuth.userCoins >= 5
  }

  const hasMoney = () => {
    return storeAuth.userCoins >= 5
  }

  return {
    games,
    totalGames,
    fetchGames,
    addGame,
    joinGame,
    canJoinGame,
    removeGame,
    canRemoveGame,
    hasMoney,
  }
})
