<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

import CardComponent from './CardComponent.vue';
import { useAuthStore } from '@/stores/auth';

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

const cartaVirada = (index) => {
    emits('play', index);
};

onMounted(() => {
    setInterval(() => {
        if (!isGameWon.value) {
            timer.value++;
        }
    }, 1000);
});
</script>
<template>
    <div class="grid grid-cols-4 gap-4 divide-y divide-x" :class="{ 'hidden': isGameWon }">
        <CardComponent v-for="(number, index) in board" :number="number" :key="index" :index="index" :isFlipped="flipped[index]"
            @has-been-flipped="cartaVirada" :game="props.game"/>
    </div>
</template>