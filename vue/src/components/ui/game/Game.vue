<script setup>
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'
import { Button } from '@/components/ui/button';
import { computed, inject } from 'vue'
import Board from '@/components/ui/game/BoardComponent.vue'
import { useAuthStore } from '@/stores/auth'
import { useGamesStore } from '@/stores/games'
import Toaster from '@/components/ui/toast/Toaster.vue'
import { nextTick } from 'vue';

const storeGames = useGamesStore()
const storeAuth = useAuthStore()

const props = defineProps({
    game: {
        type: Object,
        required: true
    }
})

const alertDialog = inject('alertDialog')

const gameEnded = computed(() => {
    return props.game.gameStatus > 0
})

const currentUserTurn = computed(() => {
    if (gameEnded.value) {
        return false
    }
    return props.game.currentPlayer === storeGames.playerNumberOfCurrentUser(props.game)
})

const statusGameMessage = computed(() => {
    switch (props.game.gameStatus) {
        case null:
        case 0:
            return currentUserTurn.value ? 'Your turn' : 'Opponent turn'
        case 1:
        case 2:
            return storeGames.playerNumberOfCurrentUser(props.game) == props.game.gameStatus ? 'You won' : 'You lost'
        case 3:
            return 'Draw'
        default:
            return 'Not started!' + props.game.gameStatus
    }
})

const playPieceOfBoard = async (idx) => {
    if (props.game.currentPlayer !== storeAuth.user.id) {
        return;
    }
    storeGames.play(props.game, idx)
}

const clickCardButton = () => {
    if (gameEnded.value) {
        close()
    } else {
        alertDialog.value.open(quit, 'Quit game', 'Cancel', `Yes, I want to quit`,
            `Are you sure you want to quit the game #${props.game.id}? You'll lose the game!`)
    }
}

const close = () => {
    storeGames.close(props.game)
}

const quit = () => {
    storeGames.quit(props.game)
}
</script>
<template>
    <Toaster />
    <Card class="relative grow mx-4 mt-8 pt-2 pb-4 px-1">
        <CardHeader class="pb-0">
            <Button @click="clickCardButton" class="absolute top-4 right-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
                {{ gameEnded ? 'Close' : 'Quit' }}
            </Button>
            <CardTitle>#{{ game.id }}</CardTitle>
            <CardDescription>
                <div class="text-base">
                    {{ game.status == 'I' ? 'Interrupted' : '' }}
                </div>
            </CardDescription>
        </CardHeader>
        <CardContent class="py-4 px-8">
            <h3 class="pt-0 text-2xl font-bold py-2">
                {{ statusGameMessage }}
            </h3>
            <div>
                <Board :game="game" @play="playPieceOfBoard"></Board>
            </div>
        </CardContent>
    </Card>
</template>