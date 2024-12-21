import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'
import { useErrorStore } from '@/stores/error'
import { useAuthStore } from '@/stores/auth'
import { useToast } from '@/components/ui/toast/use-toast'

import { format } from 'date-fns'

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
            const winner =
                game.gameStatus === 1 ? game.player1 : game.gameStatus === 2 ? game.player2 : null
            const endedAt = new Date()
            const APIresponse = await axios.put('games/' + game.id, {
                status: 'E',
                winner_user_id: winner,
                ended_at: format(endedAt, 'yyyy-MM-dd HH:mm:ss'),
                total_time: Math.round((endedAt - new Date(game.began_at)) / 1000).toFixed(2),
                total_turns_winner:
                    winner == game.player1
                        ? game.movesByPlayer1
                        : winner == game.player2
                            ? game.movesByPlayer2
                            : null,
                player1: game.player1,
                player2: game.player2
            })
            const updatedGameOnDB = APIresponse.data

            // give the winner 7 coins
            if (game.gameStatus !== 3) {
                const APIresponse2 = await axios.put(`/users/${winner}/win/7`)
                await axios.post('/registerTransaction', {
                    transaction_datetime: format(new Date(), 'yyyy-MM-dd HH:mm:ss'),
                    user_id: winner,
                    type: 'I',
                    game_id: game.id,
                    brain_coins: 7,
                  })
            }
        }

        const currentPlayerWon = () => {
            return game.gameStatus === 1 && game.player1 === storeAuth.userId
                ? true
                : game.gameStatus === 2 && game.player2 === storeAuth.userId
                    ? true
                    : false
        }
        // insert the game into the multiplayer games played table
        const MPresponse = await axios.post('/games/multiplayer', {
            game_id: game.id,
            user_id: storeAuth.userId,
            player_won: currentPlayerWon(),
            pairs_discovered: game.player1 === storeAuth.userId ? game.pairsByPlayer1 : game.pairsByPlayer2,
        });
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
        payload.game.gameStatus = payload.game.player1 === storeAuth.userId ? 2 : 1
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
        const endedAt = new Date()
        const APIresponse = await axios.put('games/' + game.id, {
            status: 'I',
            winner_user_id: storeAuth.userId,
            ended_at: format(endedAt, 'yyyy-MM-dd HH:mm:ss'),
            total_time: Math.round((endedAt - new Date(game.began_at)) / 1000).toFixed(2)
        })
        const updatedGameOnDB = APIresponse.data
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
