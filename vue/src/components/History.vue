<template>
  <div class="min-h-screen grid content-between bg-gray-800">
    <Header></Header>
    <main>
      <div class="flex flex-col space-y-4">
        <div class="text-center">
          <h2 class="block text-2xl font-bold text-black dark:text-white">Scoreboards</h2>
        </div>
        <div v-show="modeChosen === ''" class="text-center">
          <h3 class="block text-l font-bold text-black dark:text-white">
            No records to be shown, please choose a mode
          </h3>
        </div>
        <div class="border-b-2 border-gray-200 dark:border-white text-black dark:text-white">
          <nav class="-mb-0.5 flex flex-wrap gap-x-6 justify-center">
            <a
              class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm whitespace-nowrap focus:outline-none focus:border-gray-800 dark:focus:border-neutral-700 hover:border-gray-800 dark:hover:border-neutral-700 hover:cursor-pointer"
              @click.prevent="changeMode(1)"
              :class="{ 'text-blue-700 border-b border-blue-700': modeChosen === 'singleplayer' }"
            >
              <i class="pi pi-user"></i>
              Singleplayer
            </a>
            <a
              class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm whitespace-nowrap focus:outline-none focus:border-gray-800 dark:focus:border-neutral-700 hover:border-gray-800 dark:hover:border-neutral-700 hover:cursor-pointer"
              @click.prevent="changeMode(2)"
              :class="{ 'text-blue-700 border-b border-blue-700': modeChosen === 'multiplayer' }"
              aria-current="page"
            >
              <i class="pi pi-users"></i>
              Multiplayer
            </a>
          </nav>
        </div>
      </div>
    </main>
    <!-- Tabela Singleplayer -->
    <div v-if="modeChosen === 'singleplayer'" class="flex flex-grow justify-center">
      <DataTable
      v-model:filters="filters"
        :value="games"
        showGridLines
        filterDisplay="menu"
        paginator
        :loading="loading"
        removableSort
        :rows="10"
        :globalFilterFields="['ended_at', 'board_id']"
        
      >
      <template #empty>
          <div class="flex justify-center items-center p-4">
            <span class="text-gray-500 text-lg">No leaderboard available</span>
          </div>
        </template>
        <template #header>
          <div class="flex items-center justify-between">
            <div class="border rounded-lg border-blue-500">
              <Button
                style="color:"
                type="button"
                icon="pi pi-refresh"
                @click="refresh"
                text
              ></Button>
            </div>

            <span class="text-xl font-bold mx-auto">Game History</span>

            <Button
              type="button"
              icon="pi pi-filter-slash"
              label="Clear"
              outlined
              @click="clearFilter"
            ></Button>
          </div>
        </template>
        <Column sortable filterField="ended_at" field="ended_at" dataType="date" header="Date - Time">
            <template #body="{ data }"> 
            {{ formatDate(data.ended_at) }}
          </template>
          <template #filter="{ filterModel }">
            <DatePicker
              v-model="filterModel.value"
              dateFormat="dd/mm/yy"
              placeholder="dd/mm/yyyy"
            ></DatePicker>
          </template>
        </Column>
        <Column field="board_id" header="Board" :showFilterMatchModes="false"
        :showFilterMenu="true">
            <template #body="{ data }">
            <span v-if="data.board_id == 1">3x4</span>
            <span v-else-if="data.board_id == 2">4x4</span>
            <span v-else>6x6</span>
          </template>
          <template #filter="{ filterModel }">
            <MultiSelect
              v-model="filterModel.value"
              :options="boards"
              optionValue="value"
              optionLabel="label"
              placeholder="Any"
              
            >
              <template #option="slotProps">
                <span>{{ slotProps.option.label }}</span>
              </template>
            </MultiSelect>
          </template>
        </Column>
        <Column sortable field="total_time" header="Total Score"></Column>
        <Column sortable field="total_turns_winner" header="Turns">
           
        </Column>
       
        <Column field="status" header="Game Status">
            <template #body="{ data }">
            <span v-if="data.status==='E'" class="text-green-500">Ended</span>
            <span v-else-if="data.status==='PE'" class="text-yellow-500">Pending</span>
            <span v-else-if="data.status==='PL'" class="text-blue-500">In Progress</span>
            <span v-else-if="data.status==='I'" class="text-red-500">Interrupted</span>
            </template>
        </Column>
      </DataTable>
    </div>
    <!-- Tabela Multiplayer -->
    <div v-if="modeChosen === 'multiplayer'" class="flex flex-grow justify-center">
      <DataTable
      v-model:filters="filters"
        :value="games"
        showGridLines
        filterDisplay="menu"
        paginator
        :loading="loading"
        removableSort
        :rows="10"
        :globalFilterFields="['ended_at', 'board_id']"
        
      >
      <template #empty>
          <div class="flex justify-center items-center p-4">
            <span class="text-gray-500 text-lg">No leaderboard available</span>
          </div>
        </template>
        <template #header>
          <div class="flex items-center justify-between">
            <div class="border rounded-lg border-blue-500">
              <Button
                style="color:"
                type="button"
                icon="pi pi-refresh"
                @click="refresh"
                text
              ></Button>
            </div>

            <span class="text-xl font-bold mx-auto">Game History</span>

            <Button
              type="button"
              icon="pi pi-filter-slash"
              label="Clear"
              outlined
              @click="clearFilter"
            ></Button>
          </div>
        </template>
        <Column sortable filterField="ended_at" field="ended_at" dataType="date" header="Date - Time">
            <template #body="{ data }"> 
            {{ formatDate(data.ended_at) }}
          </template>
          <template #filter="{ filterModel }">
            <DatePicker
              v-model="filterModel.value"
              dateFormat="dd/mm/yy"
              placeholder="dd/mm/yyyy"
            ></DatePicker>
          </template>
        </Column>
        <Column field="board_id" header="Board" :showFilterMatchModes="false"
        :showFilterMenu="true">
            <template #body="{ data }">
            <span v-if="data.board_id == 1">3x4</span>
            <span v-else-if="data.board_id == 2">4x4</span>
            <span v-else>6x6</span>
          </template>
          <template #filter="{ filterModel }">
            <MultiSelect
              v-model="filterModel.value"
              :options="boards"
              optionValue="value"
              optionLabel="label"
              placeholder="Any"
              
            >
              <template #option="slotProps">
                <span>{{ slotProps.option.label }}</span>
              </template>
            </MultiSelect>
          </template>
        </Column>
        <Column sortable field="total_time" header="Total Score"></Column>
        <Column sortable field="total_turns_winner" header="Turns">
            <template #body="{ data }">
            <span >{{ data.total_turns_winner }}</span>
          </template>
        </Column>
        <Column field="winner_user_id" header="Winner">
            <template #body="{ data }">
            <span v-if="data.winner_user_id == user.id" class="font-bold">You</span>
            <span v-else>{{ data.nickname }}</span>
          </template>
        </Column>
        <Column field="status" header="Game Status">
            <template #body="{ data }">
            <span v-if="data.status==='E'" class="text-green-500">Ended</span>
            <span v-else-if="data.status==='PE'" class="text-yellow-500">Pending</span>
            <span v-else-if="data.status==='PL'" class="text-blue-500">In Progress</span>
            <span v-else-if="data.status==='I'" class="text-red-500">Interrupted</span>
            </template>
        </Column>
      </DataTable>
    </div>
    <button
      @click.prevent="goBack"
      type="button"
      class="py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
    >
      Go Back
    </button>
    <Footer></Footer>
  </div>
</template>
<script setup>
import Header from '@/components/ui/Header.vue'
import Footer from '@/components/ui/Footer.vue'
import Toaster from '@/components/ui/toast/Toaster.vue';
import Button from 'primevue/button'
import DatePicker from 'primevue/datepicker'
import MultiSelect from 'primevue/multiselect'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { FilterMatchMode, FilterOperator } from '@primevue/core/api'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { toast } from '@/components/ui/toast/use-toast';
import axios from 'axios'
import { ref,watch } from 'vue'


const router = useRouter()
const authStore = useAuthStore()

const loading = ref(true)
const games = ref([])
const modeChosen = ref('')
const user = authStore.user
const filters = ref({})

const initFilters = () => {
  filters.value = {
    ended_at: {
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }]
    },
    board_id: {
      value: null,
      matchMode: FilterMatchMode.IN
    }
  }
}
const clearFilter = () => {
  initFilters()
}

const goBack = async () => {
  router.push({ name: 'dashboard' })
}

const changeMode = (mode) => {
  if (mode == 1) {
    modeChosen.value = 'singleplayer'
  } else {
    modeChosen.value = 'multiplayer'
  }
}

const formatDate = (value) => {
    const date = new Date(value)
    if (isNaN(date)) {
    return 'Invalid Date'
  }
  return date.toLocaleDateString('pt-pt', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  })
}
const boards = ref([
  { label: '3x4', value: 1 },
  { label: '4x4', value: 2 },
  { label: '6x6', value: 3 }
])

const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms))

const refresh = async () => {
  games.value = []  
  loading.value = true
  await delay(500)
  
  try {
    if (modeChosen.value === 'singleplayer') {
      const response = await axios.get('/games/singleplayer/' + user.id)
      games.value = response.data
    } else if (modeChosen.value === 'multiplayer') {
      const response = await axios.get('/games/multiplayer/' + user.id)
      games.value = response.data
    }
  } catch (error) {
    toast({
      description: 'An error occurred while fetching the data',
      title: 'Error',
      variant: 'destructive'
    })
  } finally {
    loading.value = false
  }
}

watch(modeChosen, async (newValue) => {
    if (!user) {
    return
  }
  initFilters();
  if (newValue === 'singleplayer') {
    await axios
      .get('/games/singleplayer/' + user.id)
      .then((response) => {
        games.value = response.data
      })
      .catch(() => {
        toast({
          description: 'An error occurred while fetching the Personal LeaderBoard by Time data',
          title: 'Error on Personal LeaderBoard by Time',
          variant: 'destructive'
        })
      })
      loading.value = false
      console.log(games.value)
  } else if (newValue === 'multiplayer') {
    await axios
      .get('/games/multiplayer/' + user.id)
      .then((response) => {
        games.value = response.data
      })
      .catch(() => {
        toast({
          description: 'An error occurred while fetching the Global LeaderBoard by Time data',
          title: 'Error on Global LeaderBoard by Time',
          variant: 'destructive'
        })
      })
      loading.value = false
      console.log(games.value)
  }
  
})

</script>
