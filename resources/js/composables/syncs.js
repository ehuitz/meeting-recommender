import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useSyncs() {
    const sync = ref({
       origin: '',
       status: '',
        textFile: ''
    })
    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')


    const storeSync = async (sync) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        let serializedSync = new FormData()
        for (let item in sync) {
            if (sync.hasOwnProperty(item)) {
                serializedSync.append(item, sync[item])
            }
        }

        axios.post('/api/syncs', serializedSync)
            .then(response => {
                router.push({name: 'busies.index'})
                swal({
                    icon: 'success',
                    title: 'Sync saved successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    return {
        sync,
        storeSync,
        validationErrors,
        isLoading
    }
}
