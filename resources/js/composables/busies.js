import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useBusies() {
    const busies = ref({})

    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getBusies = async (
        page = 1,
        search_employee = '',
        search_id = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
        axios.get('/api/busies?page=' + page +
            '&search_employee=' + search_employee +
            '&search_id=' + search_id +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)
            .then(response => {
                busies.value = response.data;
            })
    }






    return {
        busies,
        getBusies,
        validationErrors,
        isLoading
    }
}
