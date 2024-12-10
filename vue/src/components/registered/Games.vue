<script setup>
import { onMounted } from 'vue'
import Game from './Game.vue'
import { useGamesStore } from '@/stores/games';

const storeGames = useGamesStore()

onMounted(() => {
    storeGames.fetchPlayingGames()
})
</script>

<template>
    <div v-for="game in storeGames.games" :key="game.id" :game="game" class="flex flex-wrap justify-stretch">
        <Game3x4></Game3x4>
    </div>
</template>