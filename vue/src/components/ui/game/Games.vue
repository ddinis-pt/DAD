<script setup>
import { onMounted, watch } from 'vue'
import Game from './Game.vue'
import { useGamesStore } from '@/stores/games';

const storeGames = useGamesStore()

watch(() => storeGames.games, () => {
    storeGames.fetchPlayingGames()
})

onMounted(() => {
    storeGames.fetchPlayingGames()
})
</script>
<template>
    <div class="flex flex-wrap justify-stretch">
        <Game v-for="game in storeGames.games" :key="game.id" :game="game">
        </Game>
    </div>
</template>