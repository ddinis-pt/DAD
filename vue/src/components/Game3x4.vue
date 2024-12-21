<script setup>
import { ref, watch, nextTick } from 'vue';
import axios from 'axios';
import { format } from 'date-fns';
import CardComponent from './ui/game/CardSingleplayer.vue';
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();

const startedAt = new Date();

const user = authStore.user;

const confirm = useConfirm();

const router = useRouter();

// Randomized numbers
const numbers = Array.from({ length: 6 }, (_, i) => i + 1).flatMap(n => [n, n]).sort(() => Math.random() - 0.5);

// Static numbers for testing
// const numbers = [1, 1, 2, 2, 3, 3, 4, 4, 5, 5, 6, 6];

const timer = ref(0);
const nParesEncontrados = ref(0);
const nJogadas = ref(0);

const isGameWon = ref(false);
const isFirstMove = ref(true);

const segundos = ref(0);
const minutos = ref(0);
const horas = ref(0);

const flippedCards = ref(numbers.map(() => false));
const currentlyFlipped = ref([]);

let intervalo = null;

watch(nParesEncontrados, async (n) => {
    if (n == 6) {
        clearInterval(intervalo);
        isGameWon.value = true;
        document.getElementById("background").pause();
        // Save game to database
        if (user) {
            const game = {
                created_user_id: user.id,
                type: 'S',
                status: 'E',
                began_at: format(startedAt, 'yyyy-MM-dd HH:mm:ss'),
                ended_at: format(new Date(), 'yyyy-MM-dd HH:mm:ss'),
                total_time: timer.value.toFixed(2),
                board_id: 1,
                total_turns_winner: nJogadas.value,
            };
            await axios.post('/games', game);
        }
    }
});

const cartaVirada = async (index) => {
    if (isGameWon.value || flippedCards.value[index]) {
        return;
    }
    if (isFirstMove.value) {
        intervalo = setInterval(() => {
            timer.value++;
            if (minutos.value == 59 && segundos.value == 59) {
                horas.value++;
                minutos.value = 0;
                segundos.value = 0;
            } else {
                if (segundos.value == 59) {
                    segundos.value = 0;
                    minutos.value++;
                } else {
                    segundos.value++;
                }
            }
        }, 1000);
        isFirstMove.value = false;
        document.getElementById("background").play();
    }
    flippedCards.value[index] = true;
    document.getElementById("flip").play();
    currentlyFlipped.value.push(index);
    await nextTick();
    if (currentlyFlipped.value.length == 2) {
        const [index1, index2] = currentlyFlipped.value;
        const card1 = numbers[index1];
        const card2 = numbers[index2];
        if (card1 === card2) {
            nParesEncontrados.value++;
        } else {
            setTimeout(() => {
                flippedCards.value[index1] = false;
                flippedCards.value[index2] = false;
            }, 750);
        }
        currentlyFlipped.value = [];
        nJogadas.value++;
    }
}

const showHint = async () => {
    if (authStore.userCoins < 1) {
        toast({
            title: 'Not enough coins',
            description: 'You need at least 1 coin to use the hint.',
        });
        return;
    } else {
        await axios.put('/spend/1')
            .catch(() => {
                toast({
                    title: 'Error',
                    description: 'An error occurred while spending the coin.',
                    variant: 'destructive'
                });
            });
        authStore.refreshUserData();
    }
    const unflippedPairs = [];

    for (let i = 0; i < numbers.length; i++) {
        if (!flippedCards.value[i]) {
            for (let j = i + 1; j < numbers.length; j++) {
                if (!flippedCards.value[j] && numbers[i] === numbers[j]) {
                    unflippedPairs.push([i, j]);
                }
            }
        }
    }

    if (unflippedPairs.length > 0) {
        const [index1, index2] = unflippedPairs[0];
        flippedCards.value[index1] = true;
        flippedCards.value[index2] = true;
        document.getElementById("flip").play();
        setTimeout(() => {
            flippedCards.value[index1] = false;
            flippedCards.value[index2] = false;
        }, 750);
    }
}

const showTemplateHint = () => {
    confirm.require({
        group: 'hint',
        header: 'Get Hint',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancel',
            icon: 'pi pi-times',
            outlined: true,
            size: 'small'
        },
        acceptProps: {
            label: 'Yes',
            icon: 'pi pi-check',
            size: 'small'
        },
        accept: () => {
            showHint();
        },
        reject: () => {

        }
    });
};

const showTemplateBack = () => {
    confirm.require({
        group: 'back',
        header: 'Go Back',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancel',
            icon: 'pi pi-times',
            outlined: true,
            size: 'small'
        },
        acceptProps: {
            label: 'Confirm',
            icon: 'pi pi-check',
            size: 'small'
        },
        accept: () => {
            router.push({ name: 'modes' });
        },
        reject: () => {

        }
    });
};
</script>

<template>
    <audio id="background" volume="0.04" loop>
        <source src="/src/assets/background-music.mp3" type="audio/mpeg">
    </audio>
    <audio id="flip" volume="0.2">
        <source src="/src/assets/flip.mp3" type="audio/mpeg">
    </audio>
    <div class="min-h-screen flex flex-col justify-between bg-sky-50 dark:bg-gray-800">
        <header>
            <div class="flex gap-x-8 gap-y-4 grid-cols-3 justify-between items-center px-4 py-2">
                <div @click="showTemplateBack()" id="back" class="justify-center bg-white py-2 px-3 rounded-full">
                    <i class="pi pi-arrow-left text-gray-900"></i>
                </div>
                <div id="time" class="justify-center">
                    <p class="font-semibold text-lg dark:text-white text-gray-900">{{ ('0' + horas).slice(-2) + ':' +
                        ('0' +
                            minutos).slice(-2) + ':' + ('0' + segundos).slice(-2) }}</p>
                </div>
                <div v-show="authStore.user" @click="showTemplateHint()" id="hint" class="justify-center bg-white py-2 px-3 rounded-full">
                    <i class="pi pi-lightbulb text-gray-900"></i>
                </div>
            </div>
        </header>
        <main class="container mx-auto p-8 space-y-8 max-w-xl">
            <h1 v-if="isGameWon" class="text-lg font-semibold text-center text-green-600">
                {{ user ? `Parabéns ${authStore.userFirstLastName}! Ganhou o jogo em ${timer} segundos, com apenas
                ${nJogadas} jogadas!` : `Parabéns, jogador anónimo! Ganhou o jogo em ${timer} segundos, com apenas
                ${nJogadas} jogadas!` }}
            </h1>
            <div class="grid grid-cols-4 gap-4 justify-items-center" :class="{ 'hidden': isGameWon }">
                <CardComponent v-for="(number, index) in numbers" :number="number" :key="index"
                    :isFlipped="flippedCards[index]" @has-been-flipped="() => cartaVirada(index)" />
            </div>
        </main>
        <footer class="bg-gray-dark py-4 px-3">
            <div class="grid gap-x-8 gap-y-4 grid-cols-2 items-center">
                <div id="pairs" class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="33" height="33"
                        viewBox="0 0 33 33" fill="none">
                        <g clip-path="url(#clip0_317_1463)">
                            <path
                                d="M28.9437 0.34375H16.0875C14.7125 0.34375 13.475 1.1 12.8562 2.26875L3.16247 4.88125C2.19997 5.15625 1.37497 5.775 0.824973 6.66875C0.343723 7.5625 0.206223 8.59375 0.481223 9.55625L5.98122 29.9062C6.46247 31.625 7.97497 32.725 9.62497 32.725C9.96872 32.725 10.3125 32.6562 10.5875 32.5875L22.825 29.2875L23.4437 29.0812H28.875C30.9375 29.0812 32.5875 27.4313 32.5875 25.3688V4.05625C32.6562 1.99375 31.0062 0.34375 28.9437 0.34375ZM12.375 20.9L8.86872 18.15L11.55 11.55L12.375 12.1688V20.9ZM10.2437 31.2125C8.93747 31.5563 7.63122 30.8 7.28747 29.4938L1.78747 9.14375C1.64997 8.525 1.71872 7.8375 1.99372 7.2875C2.33747 6.7375 2.88747 6.39375 3.50622 6.1875L12.375 3.78125V10.45L11.6875 9.9C11.55 9.7625 11.275 9.69375 11.0687 9.7625C10.8625 9.83125 10.725 9.96875 10.5875 10.175L7.35622 18.0812C7.21872 18.3562 7.35622 18.7 7.56247 18.9062L12.3062 22.6188V25.3C12.3062 27.3625 13.9562 29.0125 16.0187 29.0125H18.3562L10.2437 31.2125ZM31.2812 25.3C31.2812 26.6062 30.25 27.6375 28.9437 27.6375H16.0875C14.7812 27.6375 13.75 26.6062 13.75 25.3V4.05625C13.75 3.78125 13.8187 3.4375 13.8875 3.1625C13.8875 3.09375 13.9562 3.025 13.9562 3.025C14.3687 2.26875 15.125 1.71875 16.0875 1.71875H28.9437C30.25 1.71875 31.2812 2.75 31.2812 4.05625V25.3Z"
                                fill="white" />
                            <path
                                d="M23.0313 7.42499C22.7563 7.08124 22.2063 7.08124 21.9313 7.42499L16.775 14.2312C16.5688 14.5062 16.5688 14.85 16.775 15.0562L21.9313 21.8625C22.0688 22 22.275 22.1375 22.4813 22.1375C22.6876 22.1375 22.8938 22.0687 23.0313 21.8625L28.1875 15.0562C28.3938 14.7812 28.3938 14.4375 28.1875 14.2312L23.0313 7.42499ZM22.55 20.35L18.2188 14.7125L22.55 9.07499L26.8813 14.7125L22.55 20.35Z"
                                fill="white" />
                        </g>
                        <defs>
                            <clipPath id="clip0_317_1463">
                                <rect width="33" height="33" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                    <p class="font-semibold text-center text-white ps-2">{{ nParesEncontrados }}/6</p>
                </div>
                <div id="flips flex items-right justify-end">
                    <p class="font-semibold text-right text-white">Flips done: {{ nJogadas }}</p>
                </div>

            </div>
        </footer>
        <ConfirmDialog group="hint">
            <template #message="slotProps">
                <div
                    class="flex flex-col items-center w-full gap-4 border-b border-surface-200 dark:border-surface-700">
                    <i :class="slotProps.message.icon" class="!text-6xl text-primary-500"></i>
                    <p class="pb-2">Are you sure you want to use the hint? <br> Using the Hint costs <strong>1 coin</strong></p>
                </div>
            </template>
        </ConfirmDialog>
        <ConfirmDialog group="back">
            <template #message="slotProps">
                <div
                    class="flex flex-col items-center w-full gap-4 border-b border-surface-200 dark:border-surface-700">
                    <i :class="slotProps.message.icon" class="!text-6xl text-primary-500"></i>
                    <p class="pb-2">Are you sure you want to leave the game? <br>Your progress will be lost.</p>
                </div>
            </template>
        </ConfirmDialog>
    </div>
</template>