<template>
  <Toaster />
  <div class="min-h-screen bg-sky-50 dark:bg-gray-800">
    <Header></Header>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-0">
      <div class="flex flex-col space-y-4">
        <div class="text-center">
          <h2 class="block text-2xl font-bold text-gray-800 dark:text-white">Scoreboards</h2>
        </div>
        <div class="p-2 text-black dark:text-white">
          <nav class="-mb-0.5 flex flex-wrap gap-x-6 justify-center">
            <a
              v-if="!guest"
              class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm whitespace-nowrap hs-tab-active:border focus:border-blue-500 dark:focus:border-blue-500 hover:text-blue-500 dark:hover:text-blue-500 hover:cursor-pointer"
              @click.prevent="setMode('personal')"
              :class="{ 'text-blue-500 border-b-2 !border-blue-500': mode === 'personal' }"
            >
              <i class="pi pi-user"></i>
              Personal Scoreboard
            </a>
            <a
              class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm whitespace-nowrap focus:border-blue-500 dark:focus:border-blue-500 neutral-700 hover:text-blue-500 dark:hover:text-blue-500 hover:cursor-pointer"
              @click.prevent="setMode('global')"
              :class="{ 'text-blue-500 border-b !border-blue-500': mode === 'global' }"
              aria-current="page"
            >
              <i class="pi pi-globe"></i>
              Global Scoreboard
            </a>
          </nav>
        </div>

        <div v-if="mode === 'personal' && !guest">
          <!-- Singleplayer Stats -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Best Times -->
            <div
              class="bg-white dark:bg-slate-900 p-6 rounded-lg shadow-lg text-gray-600 dark:text-white"
            >
              <h2 class="text-xl font-semibold text-blue-600 mb-4">
                Top 3 Best Times by Board Size
              </h2>
              <div class="space-y-4">
                <h3 class="text-lg font-medium pb-4">Board 3x4</h3>
                <div v-for="(time, index) in top3Board1.times" :key="index">
                  <ul class="space-y-2">
                    <li class="flex justify-between">
                      <span
                        :class="{
                          'text-gray-500': index == 1,
                          'text-yellow-500': index == 0,
                          'text-amber-600': index == 2
                        }"
                        class="font-semibold px-2 py-1 rounded-md bg-yellow-50"
                        >Rank {{ index + 1 }}</span
                      >
                      <span>{{ time.total_time }} seconds</span>
                    </li>
                  </ul>
                </div>
                <h3 class="text-lg font-medium pb-4">Board 4x4</h3>
                <div v-for="(time, index) in top3Board2.times" :key="index">
                  <ul class="space-y-2">
                    <li class="flex justify-between">
                      <span
                        :class="{
                          'text-gray-500': index == 1,
                          'text-yellow-500': index == 0,
                          'text-amber-600': index == 2
                        }"
                        class="font-semibold px-2 py-1 rounded-md bg-yellow-50"
                        >Rank {{ index + 1 }}</span
                      >
                      <span>{{ time.total_time }} seconds</span>
                    </li>
                  </ul>
                </div>
                <h3 class="text-lg font-medium pb-4">Board 6x6</h3>
                <div v-for="(time, index) in top3Board3.times" :key="index">
                  <ul class="space-y-2">
                    <li class="flex justify-between">
                      <span
                        :class="{
                          'text-gray-500': index == 1,
                          'text-yellow-500': index == 0,
                          'text-amber-600': index == 2
                        }"
                        class="font-semibold  px-2 py-1 rounded-md bg-yellow-50"
                        >Rank {{ index + 1 }}</span
                      >
                      <span>{{ time.total_time }} seconds</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Minimum Turns -->
            <div
              class="bg-white dark:bg-slate-900 p-6 rounded-lg shadow-lg text-gray-600 dark:text-white"
            >
              <h2 class="text-xl font-semibold text-blue-600 mb-4">
                Top 3 Minimum Turns by Board Size
              </h2>
              <div class="space-y-4">
                <h3 class="text-lg font-medium pb-4">Board 3x4</h3>
                <div v-for="(turn, index) in top3Board1.turns" :key="index">
                  <ul class="space-y-2">
                    <li class="flex justify-between">
                      <span
                        :class="{
                          'text-gray-500': index == 1,
                          'text-yellow-500': index == 0,
                          'text-amber-600': index == 2
                        }"
                        class="font-semibold px-2 py-1 rounded-md bg-yellow-50"
                        >Rank {{ index + 1 }}</span
                      >
                      <span>{{ turn.total_turns_winner }} turns</span>
                    </li>
                  </ul>
                </div>
                <h3 class="text-lg font-medium pb-4">Board 4x4</h3>
                <div v-for="(turn, index) in top3Board2.turns" :key="index">
                  <ul class="space-y-2">
                    <li class="flex justify-between">
                      <span
                        :class="{
                          'text-gray-500': index == 1,
                          'text-yellow-500': index == 0,
                          'text-amber-600': index == 2
                        }"
                        class="font-semibold px-2 py-1 rounded-md bg-yellow-50"
                        >Rank {{ index + 1 }}</span
                      >
                      <span>{{ turn.total_turns_winner }} turns</span>
                    </li>
                  </ul>
                </div>
                <h3 class="text-lg font-medium pb-4">Board 6x6</h3>
                <div v-for="(turn, index) in top3Board3.turns" :key="index">
                  <ul class="space-y-2">
                    <li class="flex justify-between">
                      <span
                        :class="{
                          'text-gray-500': index == 1,
                          'text-yellow-500': index == 0,
                          'text-amber-600': index == 2
                        }"
                        class="font-semibold px-2 py-1 rounded-md bg-yellow-50"
                        >Rank {{ index + 1 }}</span
                      >
                      <span>{{ turn.total_turns_winner }} turns</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Multiplayer Stats -->
          <div class="p-6 m-8 bg-white dark:bg-slate-900 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold text-indigo-700 mb-4">Multiplayer Statistics</h2>
            <div class="grid grid-cols-2 gap-4">
              <!-- Victories Card -->
              <div class="bg-green-50 p-4 rounded-lg text-center transition-all hover:shadow-md">
                <div class="flex items-center justify-center mb-2">
                  <span class="font-semibold text-green-700">Victories</span>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ multiplayerStats.victories }}</p>
              </div>

              <!-- Losses Card -->
              <div class="bg-red-50 p-4 rounded-lg text-center transition-all hover:shadow-md">
                <div class="flex items-center justify-center mb-2">
                  <span class="font-semibold text-red-700">Losses</span>
                </div>
                <p class="text-3xl font-bold text-red-600">{{ multiplayerStats.losses }}</p>
              </div>
            </div>
          </div>
        </div>
        <div v-if="mode === 'global'">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div
              class="bg-white dark:bg-slate-900 p-6 rounded-lg shadow-lg text-gray-600 dark:text-white"
            >
              <h2 class="text-xl font-semibold text-blue-600 mb-4">Best Times by Board Size</h2>
              <div
                class="bg-white dark:bg-slate-900 p-6 rounded-lg shadow-lg border border-blue-500 text-gray-600 dark:text-white"
              >
                <div class="space-y-4">
                  <div>
                    <ul class="space-y-2">
                      <li class="flex justify-between">
                        <h3 class="text-lg font-medium">Board 3x4</h3>

                        <span>{{ top1Board1.total_time }} seconds</span>
                        <span>{{ top1Board1.nickname_time }}</span>
                      </li>
                    </ul>
                  </div>
                  <hr />
                  <div>
                    <ul class="space-y-2">
                      <li class="flex justify-between">
                        <h3 class="text-lg font-medium">Board 4x4</h3>
                        <span>{{ top1Board2.total_time }} seconds</span>
                        <span>{{ top1Board2.nickname_time }}</span>
                      </li>
                    </ul>
                  </div>
                  <hr />
                  <div>
                    <ul class="space-y-2">
                      <li class="flex justify-between">
                        <h3 class="text-lg font-medium">Board 6x6</h3>
                        <span>{{ top1Board3.total_time }} seconds</span>
                        <span>{{ top1Board3.nickname_time }}</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div
              class="bg-white dark:bg-slate-900 p-6 rounded-lg shadow-lg text-gray-600 dark:text-white"
            >
              <h2 class="text-xl font-semibold text-blue-600 mb-4">Minimum Turns by Board Size</h2>
              <div
                class="bg-white dark:bg-slate-900 p-6 rounded-lg shadow-lg border border-blue-500 text-gray-600 dark:text-white"
              >
                <div class="space-y-4">
                  <div>
                    <ul class="space-y-2">
                      <li class="flex justify-between">
                        <h3 class="text-lg font-medium">Board 3x4</h3>
                        <span>{{ top1Board1.turns }} turns</span>
                        <span>{{ top1Board1.nickname_turns }}</span>
                      </li>
                    </ul>
                  </div>
                  <hr />
                  <div>
                    <ul class="space-y-2">
                      <li class="flex justify-between">
                        <h3 class="text-lg font-medium">Board 4x4</h3>
                        <span>{{ top1Board2.turns }} turns</span>
                        <span>{{ top1Board2.nickname_turns }}</span>
                      </li>
                    </ul>
                  </div>
                  <hr />
                  <div>
                    <ul class="space-y-2">
                      <li class="flex justify-between">
                        <h3 class="text-lg font-medium">Board 6x6</h3>
                        <span>{{ top1Board3.turns }} turns</span>
                        <span>{{ top1Board3.nickname_turns }}</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- A API deve devolver uma lista com 5 valores desta forma {player_name, total_wins} -->
          <div>
            <div class="p-6 m-8 bg-white dark:bg-slate-900 rounded-lg shadow-lg">
              <h2 class="text-xl font-bold text-indigo-700 mb-4">
                Top 5 players with the most victories
              </h2>
              <div class="border border-blue-500 rounded-lg p-4 grid grid-cols-1">
                <ul class="space-y-2">
                  <li
                    v-for="(linha, index) in top5MostWins"
                    class="flex justify-between"
                    :key="index"
                  >
                    <div class="flex mb-5 items-center">
                      <div class="px-4 py-2 rounded-full bg-yellow-50">
                        <span
                          :class="{
                            'text-yellow-500': index == 0,
                            'text-gray-500': index == 1,
                            'text-amber-600': index == 2,
                            'text-slate-600': index == 3 || index == 4
                          }"
                          class="font-semibold"
                          >{{ index + 1 }}</span
                        >
                      </div>
                      <span class="px-2">{{ linha.nickname }}</span>
                    </div>
                    <span class="font-semibold">{{ linha.victories }} wins</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import Header from '@/components/ui/Header.vue'
import Toaster from '@/components/ui/toast/Toaster.vue'
import { onBeforeMount, ref } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import { toast } from '@/components/ui/toast/use-toast'

const authStore = useAuthStore()
const guest = authStore.user === null
const dataByTime = ref([])
const dataByTurns = ref([])
const top3Board1 = ref({
  times: Array,
  turns: Array
})
const top3Board2 = ref({
  times: Array,
  turns: Array
})

const top3Board3 = ref({
  times: Array,
  turns: Array
})
const multiplayerStats = ref({
  victories: '',
  losses: ''
})

const top5MostWins = ref({
  nickname: String,
  victories: Number
})

const top1Board1 = ref({
  total_time: Number,
  turns: Number,
  nickname_time: String,
  nickname_turns: String
})

const top1Board2 = ref({
  total_time: Number,
  turns: Number,
  nickname_time: String,
  nickname_turns: String
})

const top1Board3 = ref({
  total_time: Number,
  turns: Number,
  nickname_time: String,
  nickname_turns: String
})

const mode = ref('global')

const setMode = (chosenMode) => {
  mode.value = chosenMode
}

async function getTop3(board_id) {
  try {
    const timeResponse = await axios.get(`/leaderboard/personal/time/${board_id}`)
    const turnResponse = await axios.get(`/leaderboard/personal/turns/${board_id}`)
    return {
      times: timeResponse.data,
      turns: turnResponse.data
    }
  } catch (error) {
    toast({
      description: `An error occurred while fetching the Personal LeaderBoard data for board ${board_id}`,
      title: 'Error on Personal LeaderBoard',
      variant: 'destructive'
    })
    return {
      times: [],
      turns: []
    }
  }
}
async function getMultiplayerStats() {
  try {
    const winsResponse = await axios.get(`games/multiplayer/wins`)
    const lossesResponse = await axios.get(`games/multiplayer/losses`)
    return {
      victories: winsResponse.data,
      losses: lossesResponse.data
    }
  } catch (error) {
    toast({
      description: 'An error occurred while fetching the Multiplayer LeaderBoard data',
      title: 'Error on Multiplayer LeaderBoard',
      variant: 'destructive'
    })
    return {
      victories: 'N/A',
      losses: 'N/A'
    }
  }
}
async function getTop5MostWins() {
  try {
    const top5Response = await axios.get(`/leaderboard/top/wins`)
    return top5Response.data
  } catch (error) {
    toast({
      description: 'An error occurred while fetching the Multiplayer LeaderBoard data',
      title: 'Error on Multiplayer LeaderBoard',
      variant: 'destructive'
    })
    return []
  }
}

async function getTop1(board_id) {
  try {
    const timeResponse = await axios.get(`/leaderboard/global/time/${board_id}`)
    const turnResponse = await axios.get(`/leaderboard/global/turns/${board_id}`)
    return {
      times: timeResponse.data,
      turns: turnResponse.data,
      nickname_time: timeResponse.data[0].nickname,
      nickname_turns: turnResponse.data[0].nickname
    }
  } catch (error) {
    toast({
      description: `An error occurred while fetching the Global LeaderBoard data for board ${board_id}`,
      title: 'Error on Global LeaderBoard',
      variant: 'destructive'
    })
    return {
      times: [],
      turns: []
    }
  }
}

onBeforeMount(async () => {
  if (guest) {
    mode.value = 'global'
  } else {
    mode.value = 'personal'
    const board1Data = await getTop3(1)
    const board2Data = await getTop3(2)
    const board3Data = await getTop3(3)

    top3Board1.value.times = board1Data.times
    top3Board1.value.turns = board1Data.turns
    top3Board2.value.times = board2Data.times
    top3Board2.value.turns = board2Data.turns
    top3Board3.value.times = board3Data.times
    top3Board3.value.turns = board3Data.turns


    dataByTime.value = [...board1Data.times, ...board2Data.times, ...board3Data.times]
  dataByTurns.value = [...board1Data.turns, ...board2Data.turns, ...board3Data.turns]
  
  const multiplayerStatsData = await getMultiplayerStats()
  multiplayerStats.value.victories = multiplayerStatsData.victories
  multiplayerStats.value.losses = multiplayerStatsData.losses
  }

  const board1GlobalData = await getTop1(1)
  const board2GlobalData = await getTop1(2)
  const board3GlobalData = await getTop1(3)

  console.log(board1GlobalData)

  top1Board1.value.total_time = board1GlobalData.times[0].total_time
  top1Board1.value.turns = board1GlobalData.turns[0].total_turns_winner
  top1Board1.value.nickname_time = board1GlobalData.nickname_time
  top1Board1.value.nickname_turns = board1GlobalData.nickname_turns

  top1Board2.value.total_time = board2GlobalData.times[0].total_time
  top1Board2.value.turns = board2GlobalData.turns[0].total_turns_winner
  top1Board2.value.nickname_time = board2GlobalData.nickname_time
  top1Board2.value.nickname_turns = board2GlobalData.nickname_turns

  top1Board3.value.total_time = board3GlobalData.times[0].total_time
  top1Board3.value.turns = board3GlobalData.turns[0].total_turns_winner
  top1Board3.value.nickname_time = board3GlobalData.nickname_time
  top1Board3.value.nickname_turns = board3GlobalData.nickname_turns

  console.log(top1Board1.value)

  

 

  const top5MostWinsResult = await getTop5MostWins()
  top5MostWins.value = top5MostWinsResult
})
</script>
