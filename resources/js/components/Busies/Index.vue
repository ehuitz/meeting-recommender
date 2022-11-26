<template>
    <div class="p-6 bg-white border-gray-200">
        <div class="min-w-full align-middle">
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="sticky top-0">
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left">
                            <input v-model="search_id" type="text"
                                class="inline-block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                placeholder="Filter by ID">
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left">
                            <select v-model="search_employee" class="inline-block mt-1 w-full rounded-md
                            shadow-sm border-gray-300 focus:border-indigo-300 focus:ring
                            focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="" selected>-- All employees --</option>
                                <option v-for="employee in employees" :value="employee.id">
                                    {{ employee.name }}
                                </option>
                            </select>
                        </th>

                        <th class="px-6 py-3 bg-gray-50 text-left"></th>
                        <th class="px-6 py-3 bg-gray-50 text-left"></th>
                        <th class="px-6 py-3 bg-gray-50 text-left"></th>
                    </tr>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left">
                            <!--                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</span>-->
                            <div class="flex flex-row items-center justify-between cursor-pointer"
                                @click="updateOrdering('id')">
                                <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                    :class="{ 'font-bold text-blue-600': orderColumn === 'id' }">
                                    ID
                                </div>
                                <div class="select-none">
                                    <span :class="{
                                        'text-blue-600': orderDirection === 'asc' && orderColumn === 'id',
                                        'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'id',
                                    }">&uarr;</span>
                                    <span :class="{
                                        'text-blue-600': orderDirection === 'desc' && orderColumn === 'id',
                                        'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'id',
                                    }">&darr;</span>
                                </div>
                            </div>
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left">
                            <span
                                class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Employee</span>
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left">
                            <!--                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</span>-->
                            <div class="flex flex-row items-center justify-between cursor-pointer"
                                @click="updateOrdering('date')">
                                <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                    :class="{ 'font-bold text-blue-600': orderColumn === 'id' }">
                                    Date
                                </div>
                                <div class="select-none">
                                    <span :class="{
                                        'text-blue-600': orderDirection === 'asc' && orderColumn === 'date',
                                        'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'date',
                                    }">&uarr;</span>
                                    <span :class="{
                                        'text-blue-600': orderDirection === 'desc' && orderColumn === 'date',
                                        'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'date',
                                    }">&darr;</span>
                                </div>
                            </div>
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left">
                            <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Start
                                Time</span>
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left">
                            <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">End
                                Time</span>
                        </th>

                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                    <tr v-for="busy in busies.data">
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                            {{ busy.id }}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                            {{ busy.employee }}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                            {{ busy.date }}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                            {{ busy.start_time }}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                            {{ busy.end_time }}
                        </td>

                    </tr>
                </tbody>
            </table>

            <Pagination :data="busies" :limit="3"
                @pagination-change-page="page => getBusies(page, search_employee, search_id, orderColumn, orderDirection)"
                class="mt-4" />
        </div>
    </div>
</template>

<script>
import { ref, onMounted, watch } from "vue";
import useBusies from "../../composables/busies";
import useEmployees from "../../composables/employees";
import { useAbility } from '@casl/vue'

export default {
    setup() {
        //const search_employee = ref('')
        const search_employee = ref('')
        const search_id = ref('')
        const orderColumn = ref('created_at')
        const orderDirection = ref('desc')
        const { busies, getBusies } = useBusies()
        const { employees, getEmployees } = useEmployees()
        const { can } = useAbility()
        onMounted(() => {
            getBusies()
            getEmployees()
        })

        const updateOrdering = (column) => {
            orderColumn.value = column;
            orderDirection.value = (orderDirection.value === 'asc') ? 'desc' : 'asc';
            getBusies(
                1,
                search_employee.value,
                search_id.value,
                orderColumn.value,
                orderDirection.value
            );
        }

        watch(search_employee, (current, previous) => {
            getBusies(
                1,
                current,
                search_id.value,
            )
        })
        watch(search_id, (current, previous) => {
            getBusies(
                1,
                search_employee.value,
                current,
            )
        })



        return {
            busies,
            getBusies,
            employees,
            search_employee,
            search_id,
            orderColumn,
            orderDirection,
            updateOrdering,
            can
        }
    }
}
</script>
