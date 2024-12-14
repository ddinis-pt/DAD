<template>
    <vue-flip v-show="!ghost" v-model="flipped" class="simple-test" height="150px" width="100px" transition="0.7s">
        <template v-slot:front>
            <img @click.prevent="flipCard" src="/src/assets/cards/semFace.png" alt="Carta Virada Para Baixo">
        </template>
        <template v-slot:back>
            <img :class="{ 'cartaVirada': flipped }" :src="getImg" :alt="getText">
        </template>
    </vue-flip>
</template>
<script setup>
import { ref, computed, watch } from 'vue'
import { VueFlip } from 'vue-flip';

const props = defineProps({
    number: Number,
    isFlipped: {
        type: Boolean,
        default: false
    },
    index: Number,
    game: {
        type: Object,
        required: true
    }
})

const ghost = ref(false)

watch(() => props.game.pairs, (newValue) => {
    newValue.forEach(element => {
        if (element === props.index) {
            ghost.value = true
        }
    });
})

const emits = defineEmits(['has-been-flipped'])

const flipped = ref(props.isFlipped)
const number = ref(props.number)

// watch on props.game flipped array
watch(() => props.game.flipped, (newValue) => {
    flipped.value = newValue[props.index]
})

const getImg = computed(() => {
    if (number.value > 6) {
        return `/src/assets/cards/c${number.value - 6}.png`;
    }
    return `/src/assets/cards/c${number.value}.png`;
})

const getText = computed(() => {
    return `Carta ${number.value}`;
})

const flipCard = () => {
    emits('has-been-flipped', props.index)
}
</script>