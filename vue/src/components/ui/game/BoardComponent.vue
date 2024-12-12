<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';

import CardComponent from './CardComponent.vue';
import { useAuthStore } from '@/stores/auth';

const numbers = ref([]);
const flippedCards = ref([]);
const nJogadas = ref(0);
const timer = ref(0);

const props = defineProps({
    game: {
        type: Object,
        required: true
    }
})

const board = props.game.board;
const flipped = props.game.flipped;

const emits = defineEmits(['play']);

const isGameWon = ref(false);

const router = useRouter();
const authStore = useAuthStore();

const cartaVirada = (index) => {
    emits('play', index);
};

onMounted(() => {
    flippedCards.value = props.game.flipped;

    setInterval(() => {
        if (!isGameWon.value) {
            timer.value++;
        }
    }, 1000);
});
</script>
<template>
    <h1 v-if="isGameWon" class="text-lg font-semibold text-center text-green-600">
        {{ user ? `Parabéns ${authStore.userFirstLastName}! Ganhou o jogo em ${timer} segundos, com apenas
        ${nJogadas} jogadas!` : `Parabéns, jogador anónimo! Ganhou o jogo em ${timer} segundos, com apenas
        ${nJogadas} jogadas!` }}
    </h1>
    <div class="grid grid-cols-4 gap-4 border divide-y divide-x" :class="{ 'hidden': isGameWon }">
        <CardComponent v-for="(number, index) in board" :number="number" :key="index" :isFlipped="flipped[index]"
            @has-been-flipped="cartaVirada(index)" :game="props.game"/>
    </div>
</template>