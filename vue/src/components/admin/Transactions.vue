<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router';
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
const authStore = useAuthStore()

const transactions = ref([])

onMounted(() => {
    window.HSStaticMethods.autoInit();
    document.title = 'Memory Card Game | Transactions'
})

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
        const response = await axios.get('transactions')
        response.data.forEach(transaction => {
            transaction.transaction_datetime = new Date(transaction.transaction_datetime)
        })
        transactions.value = response.data
    } catch (error) {
        console.error('Error fetching transactions:', error)
    }
}

const columnsTransactions = [
    { field: 'transaction_datetime', header: 'Transaction Datetime' },
    { field: 'user_id', header: 'User ID' },
    { field: 'game_id', header: 'Game ID' },
    { field: 'type', header: 'Type' },
    { field: 'euros', header: 'Euros' },
    { field: 'brain_coins', header: 'Brain Coins' },
    { field: 'payment_type', header: 'Payment Type' },
    { field: 'payment_reference', header: 'Payment Reference' },
]

const typesTransactions = ref([
    { label: 'Bonus', value: 'B' },
    { label: 'Purchases', value: 'P' },
    { label: 'Internal Spending/earnings', value: 'I' },
]);

const payment_type = ref([
    { label: 'MBWAY', value: 'MBWAY' },
    { label: 'IBAN', value: 'IBAN' },
    { label: 'MB', value: 'MB' },
    { label: 'VISA', value: 'VISA' },
]);

const typeTransaction = (type) => {
    if (type === 'B') {
        return 'Bonus'
    } else if (type === 'P') {
        return 'Purchases'
    } else if (type === 'I') {
        return 'Internal Spending/earnings'
    }
}

const filtersTransactions = ref();

const initFiltersTransactions = () => {
    filtersTransactions.value = {
        global: { value: null, matchMode: FilterMatchMode.EQUALS },
        type: { value: null, matchMode: FilterMatchMode.EQUALS },
        payment_type: { value: null, matchMode: FilterMatchMode.EQUALS },
        transaction_datetime: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }] },
    };
};

initFiltersTransactions();

const clearFilterTransactions = () => {
    initFiltersTransactions();
};

const getDataType = (field) => {
    if (field === 'transaction_datetime') {
        return 'date';
    }
}

const showFilterMatchModes = (data) => {
    if (data === 'transaction_datetime') {
        return true
    } else {
        return false
    }
}

</script>

<template>
    <div class="min-h-screen bg-sky-50 dark:bg-gray-800">
        <Header></Header>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 content-top min-w-full  items-center	">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white text-center py-6">Transactions</h1>
                <div class="bg-white rounded-xl p-4 md:p-4 dark:bg-slate-900">
                    <DataTable :value="transactions" paginator :rows="10" :rowsPerPageOptions="[10, 20]"
                        size="small" v-model:filters="filtersTransactions" filterDisplay="menu"
                        :globalFilterFields="['user_id']" removableSort>
                        <template #header>
                            <div class="flex justify-between">
                                <Button type="button"
                                    class="border rounded-md py-2 px-3 inline-flex justify-center items-center"
                                    @click="clearFilterTransactions()">
                                    <i class="pi pi-filter-slash pr-2"></i>
                                    <span>Clear</span>
                                </Button>
                                <IconField>
                                    <InputIcon>
                                        <i class="pi pi-search" />
                                    </InputIcon>
                                    <InputText v-model="filtersTransactions['global'].value"
                                        placeholder="Enter user ID" />
                                </IconField>
                            </div>
                        </template>
                        <Column v-for="col of columnsTransactions" :key="col.field" :field="col.field"
                            :header="col.header" :sortable="true" :filterField="col.field" :showFilterMenu="true"
                            :showFilterMatchModes="showFilterMatchModes(col.field)"
                            :dataType="getDataType(col.field)">
                            <template #body="slotProps">
                                <span v-if="col.field === 'type'">{{ typeTransaction(slotProps.data[col.field])
                                    }}</span>
                                <span v-else-if="col.field === 'transaction_datetime'">{{
                                    formatDateTime(slotProps.data[col.field]) }}</span>
                                <span v-else>{{ slotProps.data[col.field] }}</span>
                            </template>
                            <template #filter="{ filterModel }" v-if="col.field === 'transaction_datetime'">
                                <DatePicker v-model="filterModel.value" dateFormat="dd/mm/yy"
                                    placeholder="dd/mm/yyyy" />
                            </template>
                            <template #filter="{ filterModel }" v-if="col.field === 'type'">
                                <Select v-model="filterModel.value" :options="typesTransactions" optionValue="value"
                                    optionLabel="label" placeholder="Any">
                                    <template #option="slotProps">
                                        <span>{{ slotProps.option.label }}</span>
                                    </template>
                                </Select>
                            </template>
                            <template #filter="{ filterModel }" v-if="col.field === 'payment_type'">
                                <Select v-model="filterModel.value" :options="payment_type" optionValue="value"
                                    optionLabel="label" placeholder="Any">
                                    <template #option="slotProps">
                                        <span>{{ slotProps.option.label }}</span>
                                    </template>
                                </Select>
                            </template>
                        </Column>
                        <template #footer> Total of transactions: {{ transactions ? transactions.length : 0 }}</template>
                    </DataTable>
                </div>
        </main>
    </div>
</template>