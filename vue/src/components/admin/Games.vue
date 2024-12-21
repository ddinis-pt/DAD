<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router';
import { useErrorStore } from '@/stores/error';
import Header from '@/components/ui/Header.vue'
import { ref, onBeforeMount, onMounted } from 'vue';
import axios from 'axios'

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { FilterMatchMode, FilterOperator } from '@primevue/core/api';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Button from 'primevue/button';
import Select from 'primevue/select';
import DatePicker from 'primevue/datepicker';

const router = useRouter()
const errorStore = useErrorStore()
const authStore = useAuthStore()

const games = ref([])

onMounted(() => {
    window.HSStaticMethods.autoInit();
})

// Validations
const isPlayer = () => {
    if (authStore.userType === 'P') {
        router.push({ name: 'dashboard' })
    }
    return true
}

onBeforeMount(() => {
    if (!authStore.user || authStore.userType !== 'A') {
        router.push({ name: 'login' })
    }
    isPlayer()

    fetchData()
})

const formatDateTime = (date) => {
    return new Date(date).toUTCString()
}

async function fetchData() {
    try {
        const response = await axios.get('games/all')

        response.data.forEach(game => {
            game.created_at = new Date(game.created_at)
            game.updated_at = new Date(game.updated_at)
        })
        games.value = response.data



    } catch (error) {
        // Handle errors
        console.error('Error fetching games:', error)
    }
}

const getDataType = (field) => {
    if (field === 'began_at' || field === 'ended_at') {
        return 'date';
    }
}

const showFilterMatchModes = (data) => {
    if (data === 'began_at' || data === 'ended_at') {
        return true
    } else {
        return false
    }
}

// === Games ===
const columnsGames = [
    { field: 'created_user_id', header: 'Created User ID' },
    { field: 'winner_user_id', header: 'Winner User ID' },
    { field: 'type', header: 'Type' },
    { field: 'status', header: 'Status' },
    { field: 'began_at', header: 'Began At' },
    { field: 'ended_at', header: 'Ended At' },
    { field: 'total_time', header: 'Total Time' },
    { field: 'board_id', header: 'Board' },
]

const boardGames = ref([
    { label: '3x4', value: 1 },
    { label: '4x4', value: 2 },
    { label: '6x6', value: 3 },
]);

const getBoardName = (board_id) => {
    if (board_id === 1) {
        return '3x4'
    } else if (board_id === 2) {
        return '4x4'
    } else if (board_id === 3) {
        return '6x6'
    }
}

const statusGames = ref([
    { label: 'Pending', value: 'PE' },
    { label: 'Ended', value: 'E' },
    { label: 'In Progress', value: 'PL' },
    { label: 'Interrupted', value: 'I' },
]);

const getStatusGame = (game) => {
    if (game.status === 'PE') {
        return 'Pending'
    } else if (game.status === 'E') {
        return 'Ended'
    } else if (game.status === 'PL') {
        return 'In Progress'
    } else if (game.status === 'I') {
        return 'Interrupted'
    }
}

const typesGames = ref([
    { label: 'Single-player', value: 'S' },
    { label: 'Multiplayer', value: 'M' },
]);

const getTypesGame = (type) => {
    if (type === 'S') {
        return 'Single-player'
    } else if (type === 'M') {
        return 'Multiplayer'
    }
}

const filtersGames = ref();

const initFiltersGames = () => {
    filtersGames.value = {
        global: { value: null, matchMode: FilterMatchMode.EQUALS },
        type: { value: null, matchMode: FilterMatchMode.EQUALS },
        board_id: { value: null, matchMode: FilterMatchMode.EQUALS },
        status: { value: null, matchMode: FilterMatchMode.EQUALS },
        winner_user_id: { value: null, matchMode: FilterMatchMode.EQUALS },
        created_user_id: { value: null, matchMode: FilterMatchMode.EQUALS },
        began_at: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }] },
        ended_at: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }] },
    };
};

initFiltersGames();

const clearFilterGames = () => {
    initFiltersGames();
};

</script>

<template>

    <div class="min-h-screen bg-sky-50 dark:bg-gray-800">
        <Header></Header>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 content-top min-w-full  items-center	">

            <h1 class="text-3xl font-bold text-slate-900 dark:text-white text-center py-6">Games</h1>
            <div class="bg-white rounded-xl p-4 md:p-4 dark:bg-slate-900">
                <DataTable :value="games" paginator :rows="10" :rowsPerPageOptions="[10, 20]" size="small" removableSort
                    v-model:filters="filtersGames" filterDisplay="menu">
                    <template #header>
                        <div class="flex justify-between">
                            <Button type="button"
                                class="border rounded-md py-2 px-3 inline-flex justify-center items-center"
                                @click="clearFilterGames()">
                                <i class="pi pi-filter-slash pr-2"></i>
                                <span>Clear</span>
                            </Button>
                            <IconField>
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText v-model="filtersGames['created_user_id'].value"
                                    placeholder="Enter created user ID" />
                            </IconField>
                            <IconField>
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText v-model="filtersGames['winner_user_id'].value"
                                    placeholder="Enter winner user ID" />
                            </IconField>
                        </div>
                    </template>
                    <Column v-for="col of columnsGames" :key="col.field" :field="col.field" :header="col.header"
                        :sortable="true" :filterField="col.field"
                        :showFilterMatchModes="showFilterMatchModes(col.field)" :showFilterMenu="true"
                        :dataType="getDataType(col.field)">
                        <template #body="slotProps">
                            <span v-if="col.field === 'type'">{{ getTypesGame(slotProps.data[col.field])
                                }}</span>
                            <span v-else-if="col.field === 'status'">{{ getStatusGame(slotProps.data) }}</span>
                            <span v-else-if="col.field === 'began_at'">{{
                                formatDateTime(slotProps.data[col.field])
                                }}</span>
                            <span v-else-if="col.field === 'ended_at'">{{
                                formatDateTime(slotProps.data[col.field])
                                }}</span>
                            <span v-else-if="col.field === 'board_id'">{{
                                getBoardName(slotProps.data[col.field])
                                }}</span>
                            <span v-else>{{ slotProps.data[col.field] }}</span>
                        </template>
                        <template #filter="{ filterModel }" v-if="col.field === 'type'">
                            <Select v-model="filterModel.value" :options="typesGames" optionValue="value"
                                optionLabel="label" placeholder="Any">
                                <template #option="slotProps">
                                    <span>{{ slotProps.option.label }}</span>
                                </template>
                            </Select>
                        </template>
                        <template #filter="{ filterModel }" v-if="col.field === 'status'">
                            <Select v-model="filterModel.value" :options="statusGames" optionValue="value"
                                optionLabel="label" placeholder="Any">
                                <template #option="slotProps">
                                    <span>{{ slotProps.option.label }}</span>
                                </template>
                            </Select>
                        </template>
                        <template #filter="{ filterModel }" v-if="col.field === 'board_id'">
                            <Select v-model="filterModel.value" :options="boardGames" optionValue="value"
                                optionLabel="label" placeholder="Any">
                                <template #option="slotProps">
                                    <span>{{ slotProps.option.label }}</span>
                                </template>
                            </Select>
                        </template>
                        <template #filter="{ filterModel }" v-if="col.field === 'began_at'">
                            <DatePicker v-model="filterModel.value" dateFormat="dd/mm/yy" placeholder="dd/mm/yyyy" />
                        </template>
                        <template #filter="{ filterModel }" v-if="col.field === 'ended_at'">
                            <DatePicker v-model="filterModel.value" dateFormat="dd/mm/yy" placeholder="dd/mm/yyyy" />
                        </template>
                    </Column>
                    <template #footer> Total of games: {{ games ? games.length : 0 }}</template>
                </DataTable>
            </div>
        </main>
    </div>


</template>