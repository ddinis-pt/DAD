<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router';
import { useErrorStore } from '@/stores/error';
import Header from '@/components/ui/Header.vue'
import { ref, onBeforeMount, onMounted } from 'vue';
import axios from 'axios'

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';                   // optional
import { FilterMatchMode, FilterOperator } from '@primevue/core/api';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Button from 'primevue/button';
import Select from 'primevue/select';
import Tag from 'primevue/tag';
import MultiSelect from 'primevue/multiselect';

const router = useRouter()
const errorStore = useErrorStore()
const authStore = useAuthStore()

const users = ref([])

onMounted(() => {
    window.HSStaticMethods.autoInit();
})

const isPlayer = () => {
    if (authStore.userType === 'P') {
        router.push({ name: 'dashboard' })
    }
    return true
}

onBeforeMount(async () => {
    if (!authStore.user) {
        router.push({ name: 'login' })
    }
    isPlayer()

    try {
        const response = await axios.get('users/all')
        console.log('Users:', response.data)
        users.value = response.data
    } catch (error) {
        console.error('Error fetching users:', error)
    }
})

const columns = [
    { field: 'id', header: 'ID' },
    { field: 'name', header: 'Name' },
    { field: 'email', header: 'Email' },
    { field: 'type', header: 'Type' },
    { field: 'created_at', header: 'Created At' },
    { field: 'updated_at', header: 'Updated At' },
    { field: 'actions', header: 'Actions' }
]

const rowClass = (data) => {
    if (data.blocked === 1) {
        return ['!text-red-500'];
    }
    if (data.id === authStore.user.id) {
        return ['!text-blue-600'];
    }
    if (data.deleted_at) {
        return ['!text-slate-700'];
    }
};

const typeUser = (type) => {
    return type === 'A' ? 'Admin' : 'Player'
}

const formatDateTime = (date) => {
    return new Date(date).toLocaleString()
}

const isCurrentUser = (data) => {
    return data.id === authStore.user.id
}

const isAdmin = (data) => {
    if (data.type === 'A') {
        return true
    }
}

const isUnblocked = (data) => {
    if (data.blocked === 0) {
        return true
    }
}

const isDeleted = (data) => {
    if (data.deleted_at) {
        return true
    }
}

const filters = ref();

const sortColumn = (col) => {
    if (col == 'actions') {
        return false
    } else {
        return true
    }
}

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        type: { value: null, matchMode: FilterMatchMode.IN }
    };
};

initFilters();

const clearFilter = () => {
    initFilters();
};

const types = ref([
    { label: 'Admin', value: 'A' },
    { label: 'Player', value: 'P' }
]);

const editCurrentUser = () => {
    router.push({ name: 'settings' })
}

const blockUser = async (data) => {
    if (data.type === 'A') {
        return
    }
    try {
        const response = await axios.put(`users/${data.id}/block`)
        console.log('Block user:', response.data)
        const index = users.value.findIndex(user => user.id === data.id)
        users.value[index] = response.data
    } catch (error) {
        console.error('Error blocking user:', error)
    }
}
</script>

<template>

    <div class="min-h-screen bg-white dark:bg-gray-800">
        <Header></Header>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 content-top min-w-full">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white text-center py-6">Users</h1>
            <div class="bg-white rounded-xl p-4 md:p-2 dark:bg-slate-900">
                <DataTable :value="users" paginator :rows="10" :rowsPerPageOptions="[10, 25, 50]" size="small"
                    :rowClass="rowClass" v-model:filters="filters" filterDisplay="menu"
                    :globalFilterFields="['name', 'email']" removableSort>
                    <template #header>
                        <div class="flex justify-between">
                            <Button type="button"
                                class="border rounded-md py-2 px-3 inline-flex justify-center items-center"
                                @click="clearFilter()">
                                <i class="pi pi-filter-slash pr-2"></i>
                                <span>Clear</span>
                            </Button>
                            <IconField>
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                            </IconField>
                        </div>
                    </template>
                    <Column v-for="col of columns" :key="col.field" :field="col.field" :header="col.header"
                        filterField="type" :showFilterMatchModes="false" :showFilterMenu="true"
                        :sortable="sortColumn(col.field)">
                        <template #body="slotProps">
                            <span v-if="col.field === 'type'">{{ typeUser(slotProps.data[col.field]) }}</span>
                            <span v-else-if="col.field === 'created_at' || col.field === 'updated_at'">{{
                                formatDateTime(slotProps.data[col.field]) }}</span>
                            <span v-else-if="col.field === 'actions' && !isCurrentUser(slotProps.data) && !isDeleted(slotProps.data)">
                                <div class="inline-flex rounded-lg shadow-sm">
                                    <button type="button" @click="editUser(slotProps.data)"
                                        class="py-2 px-3 inline-flex justify-center items-center gap-2 -ms-px first:rounded-s-lg first:ms-0 last:rounded-e-lg text-sm font-medium focus:z-10 border border-gray-200 bg-white text-blue-600 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-100 dark:border-neutral-700 dark:text-neutral-800 dark:hover:bg-blue-600 dark:focus:bg-neutral-100 dark:hover:text-white">
                                        <i class="pi pi-pencil !text-xs" />
                                    </button>
                                    <button v-if="isUnblocked(slotProps.data) && !isAdmin(slotProps.data)" type="button"
                                        @click="blockUser(slotProps.data)"
                                        class="py-2 px-3 inline-flex justify-center items-center gap-2 -ms-px first:rounded-s-lg first:ms-0 last:rounded-e-lg text-sm font-medium focus:z-10 border border-gray-200 bg-white text-red-600 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-100 dark:border-neutral-700 dark:text-neutral-800 dark:hover:bg-red-500 dark:focus:bg-neutral-100 dark:hover:text-white">
                                        <i class="pi pi-lock !text-xs" />
                                    </button>
                                    <button v-if="!isUnblocked(slotProps.data) && !isAdmin(slotProps.data)" type="button"
                                        @click="unblockUser(slotProps.data)"
                                        class="py-2 px-3 inline-flex justify-center items-center gap-2 -ms-px first:rounded-s-lg first:ms-0 last:rounded-e-lg text-sm font-medium focus:z-10 border border-gray-200 bg-white text-green-600 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-100 dark:border-neutral-700 dark:text-neutral-800 dark:hover:bg-green-500 dark:focus:bg-neutral-100 dark:hover:text-white">
                                        <i class="pi pi-lock-open !text-xs" />
                                    </button>
                                </div>
                            </span>
                            <span v-else-if="col.field === 'actions' && isCurrentUser(slotProps.data)">
                                <div class="inline-flex rounded-lg shadow-sm">
                                    <button type="button" @click="editCurrentUser()"
                                        class="py-2 px-3 inline-flex justify-center items-center gap-2 -ms-px first:rounded-s-lg first:ms-0 last:rounded-e-lg text-sm font-medium focus:z-10 border border-gray-200 bg-white text-blue-600 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-100 dark:border-neutral-700 dark:text-neutral-800 dark:hover:bg-neutral-800 dark:focus:bg-neutral-100 dark:hover:text-white">
                                        <i class="pi pi-pencil !text-xs" />
                                    </button>
                                </div>
                            </span>
                            <span v-else>{{ slotProps.data[col.field] }}</span>
                        </template>
                        <template #filter="{ filterModel }" v-if="col.field === 'type'">
                            <Select v-model="filterModel.value" :options="types" optionValue="value" optionLabel="label"
                                placeholder="Any">
                                <template #option="slotProps">
                                    <span>{{ slotProps.option.label }}</span>
                                </template>
                            </Select>
                        </template>
                    </Column>
                    <template #footer> Total of users: {{ users ? users.length : 0 }}</template>
                </DataTable>
            </div>
        </main>
    </div>


</template>