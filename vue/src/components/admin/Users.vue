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

const users = ref([])

onMounted(() => {
    window.HSStaticMethods.autoInit();
    document.title = 'Memory Card Game | Users'
})

const isPlayer = () => {
    if (authStore.userType === 'P') {
        router.push({ name: 'dashboard' })
    }
    return true
}

onBeforeMount(() => {
    if (authStore.user == null) {
        router.push({ name: 'login' })
    }
    isPlayer()
    getUsers()
})

const columns = [
    { field: 'id', header: 'ID' },
    { field: 'name', header: 'Name' },
    { field: 'email', header: 'Email' },
    { field: 'type', header: 'Type' },
    { field: 'created_at', header: 'Created At' },
    { field: 'updated_at', header: 'Updated At' },
    { field: 'actions', header: 'Actions' },
]

const rowClass = (data) => {
    if (data.blocked === 1) {
        return ['!text-red-500'];
    }
    if (authStore.user != null && data.id === authStore.user.id) {
        return ['!text-blue-600'];
    }
    if (data.deleted_at) {
        return ['!text-slate-300 dark:!text-slate-700'];
    }
};

const typeUser = (type) => {
    return type === 'A' ? 'Admin' : 'Player'
}

const formatDateTime = (date) => {
    return new Date(date).toLocaleString()
}

const isCurrentUser = (data) => {
    return authStore.user != null && data.id === authStore.user.id
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
        type: { value: null, matchMode: FilterMatchMode.IN },
        created_at: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }] },
        updated_at: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }] },
        status: { value: null, matchMode: FilterMatchMode.EQUALS }
    };
};

initFilters();

const clearFilter = () => {
    initFilters();
};

const showFilterMatchModes = (data) => {
    if (data === 'created_at' || data === 'updated_at') {
        return true
    } else {
        return false
    }
}

const types = ref([
    { label: 'Admin', value: 'A' },
    { label: 'Player', value: 'P' }
]);

const status = ref([
    { label: 'Active', value: 'Active' },
    { label: 'Deleted', value: 'Deleted' },
    { label: 'Blocked', value: 'Blocked' },
    { label: 'Current User', value: 'Current User' }
]);


const getType = (field) => {
    if (field === 'created_at' || field === 'updated_at') {
        return 'date';
    }
}

const getUsers = async () => {
    try {
        const response = await axios.get('users/all')
        response.data.forEach(user => {
            user.created_at = new Date(user.created_at)
            user.updated_at = new Date(user.updated_at)
            user.status = getStatus(user)
        })
        users.value = response.data
    } catch (error) {
        console.error('Error fetching users:', error)
    }
}

const editCurrentUser = () => {
    router.push({ name: 'settings' })
}

const editUser = (data) => {
    router.push({ name: 'edit-user', params: { id: data } })
}

const blockUser = async (data) => {
    if (data.type === 'A') {
        return
    }
    try {
        const response = await axios.put(`users/${data}/block`)
        const index = users.value.findIndex(user => user.id === data.id)
        users.value[index] = response.data
    } catch (error) {
        console.error('Error blocking user:', error)
    }
    getUsers()
}

const unblockUser = async (data) => {
    if (data.type === 'A') {
        return
    }
    try {
        const response = await axios.put(`users/${data}/unblock`)
        const index = users.value.findIndex(user => user.id === data.id)
        users.value[index] = response.data
    } catch (error) {
        console.error('Error unblocking user:', error)
    }
    getUsers()
}

const getStatus = (data) => {
    if (data.deleted_at) {
        return 'Deleted'
    }
    if (data.blocked === 1) {
        return 'Blocked'
    }
    if (authStore.user.id === data.id) {
        return 'Current User'
    }
    return 'Active'
}

</script>
<template>
    <div class="min-h-screen bg-sky-50 dark:bg-gray-800">
        <Header></Header>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 content-top min-w-full">
            <div class="bg-white rounded-xl p-4 md:p-4 dark:bg-slate-900">
                <div class="flex justify-end pb-2">
                <RouterLink :to="{ name: 'create-user' }"
                    class="py-3 px-4 inline-flex justify-right items-right gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white dark:text-zinc-900 hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                    <i class="pi pi-user-plus"></i>
                    Create Admin Account
                </RouterLink>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white text-center py-6">Users</h1>
            <DataTable :value="users" paginator :rows="10" :rowsPerPageOptions="[10, 25, 50]" size="small"
                :rowClass="rowClass" v-model:filters="filters" filterDisplay="menu"
                :globalFilterFields="['name', 'email', 'status']" removableSort>
                <template #header>
                    <div class="flex justify-between">
                        <Button type="button"
                            class="border rounded-md py-2 px-3 inline-flex justify-center items-center"
                            @click="clearFilter()">
                            <i class="pi pi-filter-slash pr-2"></i>
                            <span>Clear</span>
                        </Button>
                        <Select v-model="filters['status'].value" :options="status" optionValue="value"
                            optionLabel="label" placeholder="Select status">
                            <template #option="slotProps">
                                <span>{{ slotProps.option.label }}</span>
                            </template>
                            <template #dropdownicon>
                                <i class="pi pi-chevron-down" />
                            </template>
                        </Select>
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                        </IconField>
                    </div>
                </template>
                <Column v-for="col of columns" :key="col.field" :field="col.field" :header="col.header"
                    :filterField="col.field" :showFilterMatchModes="showFilterMatchModes(col.field)"
                    :showFilterMenu="true" :sortable="sortColumn(col.field)" :dataType="getType(col.field)">
                    <template #body="slotProps">
                        <span v-if="col.field === 'type'">{{ typeUser(slotProps.data[col.field]) }}</span>
                        <span v-else-if="col.field === 'created_at' || col.field === 'updated_at'">{{
                            formatDateTime(slotProps.data[col.field]) }}</span>
                        <span
                            v-else-if="col.field === 'actions' && !isCurrentUser(slotProps.data) && !isDeleted(slotProps.data)">
                            <div class="inline-flex rounded-lg shadow-sm">
                                <button type="button" @click="editUser(slotProps.data.id)"
                                    class="py-2 px-3 inline-flex justify-center items-center gap-2 -ms-px first:rounded-s-lg first:ms-0 last:rounded-e-lg text-sm font-medium focus:z-10 border border-gray-200 bg-white text-blue-600 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-100 dark:border-neutral-700 dark:text-neutral-800 dark:hover:bg-blue-600 dark:focus:bg-blue-600 dark:hover:text-white">
                                    <i class="pi pi-pencil !text-xs" />
                                </button>
                                <button v-if="isUnblocked(slotProps.data) && !isAdmin(slotProps.data)" type="button"
                                    @click="blockUser(slotProps.data.id)"
                                    class="py-2 px-3 inline-flex justify-center items-center gap-2 -ms-px first:rounded-s-lg first:ms-0 last:rounded-e-lg text-sm font-medium focus:z-10 border border-gray-200 bg-white text-red-600 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-100 dark:border-neutral-700 dark:text-neutral-800 dark:hover:bg-red-500 dark:focus:bg-red-500 dark:hover:text-white">
                                    <i class="pi pi-lock !text-xs" />
                                </button>
                                <button v-if="!isUnblocked(slotProps.data) && !isAdmin(slotProps.data)"
                                    type="button" @click="unblockUser(slotProps.data.id)"
                                    class="py-2 px-3 inline-flex justify-center items-center gap-2 -ms-px first:rounded-s-lg first:ms-0 last:rounded-e-lg text-sm font-medium focus:z-10 border border-gray-200 bg-white text-green-600 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-100 dark:border-neutral-700 dark:text-neutral-800 dark:hover:bg-green-500 dark:focus:bg-neutral-100 dark:hover:text-white">
                                    <i class="pi pi-lock-open !text-xs" />
                                </button>
                            </div>
                        </span>
                        <span v-else-if="col.field === 'actions' && isCurrentUser(slotProps.data)">
                            <div class="inline-flex rounded-lg shadow-sm">
                                <button type="button" @click="editCurrentUser()"
                                    class="py-2 px-3 inline-flex justify-center items-center gap-2 -ms-px first:rounded-s-lg first:ms-0 last:rounded-e-lg text-sm font-medium focus:z-10 border border-gray-200 bg-white text-blue-600 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-100 dark:border-neutral-700 dark:text-neutral-800 dark:hover:bg-blue-600 dark:focus:bg-neutral-100 dark:hover:text-white">
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
                    <template #filter="{ filterModel }" v-if="col.field === 'created_at'">
                        <DatePicker v-model="filterModel.value" dateFormat="dd/mm/yy" placeholder="dd/mm/yyyy" />
                    </template>
                    <template #filter="{ filterModel }" v-if="col.field === 'updated_at'">
                        <DatePicker v-model="filterModel.value" dateFormat="dd/mm/yy" placeholder="dd/mm/yyyy" />
                    </template>
                </Column>
                <template #footer> Total of users: {{ users ? users.length : 0 }}</template>
            </DataTable>
            </div>
        </main>
    </div>
</template>