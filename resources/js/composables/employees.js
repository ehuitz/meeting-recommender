import { ref } from 'vue'

export default function useEmployees() {
    const employees = ref({})

    const getEmployees = async () => {
        axios.get('/api/employees')
            .then(response => {
                employees.value = response.data.data;
            })
    }

    return { employees, getEmployees }
}
