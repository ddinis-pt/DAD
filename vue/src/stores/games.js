import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'
import { useErrorStore } from '@/stores/error'
import { useAuthStore } from '@/stores/auth'
import { useToast } from '@/components/ui/toast/use-toast'

import { format } from 'date-fns';

export const useGamesStore = defineStore('games', () => {
  const storeAuth = useAuthStore()
  const storeError = useErrorStore()
  const { toast } = useToast()
  const socket = inject('socket')

  const games = ref([])

  const totalGames = computed(() => games.value.length)

  // Use this function to update the game object in the games array
  const updateGame = (game) => {
    const gameIndex = games.value.findIndex((g) => g.id === game.id)
    if (gameIndex !== -1) {
      games.value[gameIndex] = { ...game } // shallow copy
    }
  }

  const playerNumberOfCurrentUser = (game) => {
    if (game.player1 === storeAuth.userId) {
      return game.player1
    }
    if (game.player2 === storeAuth.userId) {
      return game.player2
    }
    return null
  }

  const webSocketServerResponseHasError = (response) => {
    if (response.errorCode) {
      storeError.setErrorMessages(response.errorMessage, [], response.errorCode)
      return true
    }
    return false
  }

  const removeGameFromList = (game) => {
    const gameIndex = games.value.findIndex((g) => g.id === game.id)
    if (gameIndex >= 0) {
      games.value.splice(gameIndex, 1)
    }
  }

  // fetch playing games from the Websocket server
  const fetchPlayingGames = () => {
    storeError.resetMessages()
    socket.emit('fetchPlayingGames', (response) => {
      if (webSocketServerResponseHasError(response)) {
        return
      }
      games.value = response
    })
  }

  const play = (game, idx) => {
    storeError.resetMessages()
    socket.emit(
      'play',
      {
        index: idx,
        gameId: game.id
      },
      (response) => {
        if (webSocketServerResponseHasError(response)) {
          return
        }
        updateGame(response)
      }
    )
  }

  const quit = (game) => {
    storeError.resetMessages()
    socket.emit('quitGame', game.id, (response) => {
      if (webSocketServerResponseHasError(response)) {
        return
      }
      removeGameFromList(game)
    })
  }

  const close = (game) => {
    storeError.resetMessages()
    socket.emit('closeGame', game.id, (response) => {
      if (webSocketServerResponseHasError(response)) {
        return
      }
      removeGameFromList(game)
    })
  }

  socket.on('gameStarted', async (game) => {
    if (game.player1 === storeAuth.user.id) {
      toast({
        title: 'Game Started',
        description: `Game #${game.id} has started!`
      })
    }
    fetchPlayingGames()
  })

  socket.on('gameEnded', async (game) => {
    updateGame(game)
    fetchPlayingGames()
    // Player that created the game is responsible for updating on the database
    if (playerNumberOfCurrentUser(game) === game.created_user_id) {
      const winner = game.gameStatus === 1 ? game.player1 : game.gameStatus === 2 ? game.player2 : null
      const APIresponse = await axios.patch('games/' + game.id, {
        status: 'E',
        winner_user_id: winner,
        ended_at: format(new Date(), 'yyyy-MM-dd HH:mm:ss'),
        total_time: game.totalTime,
        total_turns_winner: winner == game.player1 ? game.movesByPlayer1 : winner == game.player2 ? game.movesByPlayer2 : null,
        player1: game.player1,
        player2: game.player2,
      })
      const updatedGameOnDB = APIresponse.data
      console.log('Game has ended and updated on the database: ', updatedGameOnDB)
    }
  })

  socket.on('gameChanged', (game) => {
    updateGame(game)
  })

  socket.on('gameQuitted', async (payload) => {
    if (payload.userQuit.id != storeAuth.userId) {
      toast({
        title: 'Game Quit',
        description: `${payload.userQuit.name} has quitted game #${payload.game.id}, giving you the win!`
      })
    }
    updateGame(payload.game)
  })

  socket.on('gameInterrupted', async (game) => {
    updateGame(game)
    fetchPlayingGames()
    toast({
      title: 'Game Interruption',
      description: `Game #${game.id} was interrupted because your opponent has gone offline!`,
      variant: 'destructive'
    })
    const APIresponse = await axios.patch('games/' + game.id, {
      status: 'I',
        winner_user_id:
          game.gameStatus === 1 ? game.player1 : game.gameStatus === 2 ? game.player2 : null,
        ended_at: format(new Date(), 'yyyy-MM-dd HH:mm:ss'),
        total_time: game.totalTime,
    })
    const updatedGameOnDB = APIresponse.data
    console.log('Game was interrupted and updated on the database: ', updatedGameOnDB)
  })

  return {
    games,
    totalGames,
    playerNumberOfCurrentUser,
    fetchPlayingGames,
    play,
    quit,
    close
  }
})
