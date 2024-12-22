<template>
    <vue-flip :class="{'opacity-0': ghost}" v-model="flipped" class="simple-test" height="150px" width="100px" transition="0.7s">
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


const flipped = ref(props.isFlipped)

const ghost = ref(false)

if (props.game && Object.hasOwn(props.game, 'pairs')) {
    watch(() => props.game.pairs, (newValue) => {
        newValue.forEach(element => {
            if (element.includes(props.index)) {
                ghost.value = true
            }
        });
    })
}

const emits = defineEmits(['has-been-flipped'])

const number = ref(props.number)

watch(
    () => props.game.flipped[props.index],
    (newValue) => {
        flipped.value = newValue;
    }
);

const getImg = computed(() => {
    if (number.value > 6) {
        return `/assets/cards/c${number.value - 6}.png`;
    }
    return `/assets/cards/c${number.value}.png`;
})

const getText = computed(() => {
    return `Carta ${number.value}`;
})

const flipCard = () => {
    emits('has-been-flipped', props.index)
}
</script>