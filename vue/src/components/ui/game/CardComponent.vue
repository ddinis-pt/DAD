<template>
    <vue-flip v-model="flipped" class="simple-test" height="150px" width="100px" transition="0.7s">
        <template v-slot:front>
            <img @click="flipCard" src="/src/assets/cards/semFace.png" alt="Carta Virada Para Baixo">
          </template>
          <template v-slot:back>
            <img :class="{ 'cartaVirada': flipped }" :src="getImg" :alt="getText">
          </template>    
    <div>
        
        
    </div>
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
    game: {
        type: Object,
        required: false
    }
})

const emits = defineEmits(['has-been-flipped'])

const flipped = ref(props.isFlipped)
const number = ref(props.number)

watch(() => props.isFlipped, (newVal) => {
    flipped.value = newVal
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
    console.log(props.game)
    // check if the user can flip the card
    if (props.game.currentPlayer !== props.game.currentPlayer) {
        return
    }
    if (flipped.value) {
        return
    }
    flipped.value = !flipped.value
    emits('has-been-flipped')
}
</script>