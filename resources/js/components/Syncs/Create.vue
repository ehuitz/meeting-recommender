<template>
    <form @submit.prevent="submitForm">

         <!-- Status -->
         <div>
            <label for="sync-status" class="block font-medium text-sm text-gray-700">
                Status
            </label>
            <!-- <input v-model="sync.status" id="sync-status" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"> -->
            <select v-model="sync.status" id="sync-status" class="inline-block mt-1 w-full rounded-md
                            shadow-sm border-gray-300 focus:border-indigo-300 focus:ring
                            focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="active" selected>Active</option>
                            </select>
            <div class="text-red-600 mt-1">
                {{ errors.status }}
            </div>
            <div class="text-red-600 mt-1">
                <div v-for="message in validationErrors?.status">
                    {{ message }}
                </div>
            </div>
        </div>

         <!-- Origin -->
         <div>
            <label for="sync-origin" class="block font-medium text-sm text-gray-700">
                Origin
            </label>
            <!-- <input
            v-model="sync.origin"
            id="sync-origin"
            type="text"
            class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"> -->
            <select v-model="sync.origin" id="sync-origin" class="inline-block mt-1 w-full rounded-md
                            shadow-sm border-gray-300 focus:border-indigo-300 focus:ring
                            focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="manual" selected>Manual</option>
                            </select>
            <div class="text-red-600 mt-1">
                {{ errors.origin }}
            </div>
            <div class="text-red-600 mt-1">
                <div v-for="message in validationErrors?.origin">
                    {{ message }}
                </div>
            </div>
        </div>

    <!-- File -->
        <div class="mt-4">
            <label for="textFile" class="block font-medium text-sm text-gray-700">
               Upload Busy File
            </label>
            <input @change="sync.textFile = $event.target.files[0]" type="file" id="textFile" />
            <div class="text-red-600 mt-1">
                <div v-for="message in validationErrors?.textFile">
                    {{ message }}
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-4">
            <button :disabled="isLoading" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded disabled:opacity-75 disabled:cursor-not-allowed">
                <div v-show="isLoading" class="inline-block animate-spin w-4 h-4 mr-2 border-t-2 border-t-white border-r-2 border-r-white border-b-2 border-b-white border-l-2 border-l-blue-600 rounded-full"></div>
                <span v-if="isLoading">Processing...</span>
                <span v-else>Save</span>
            </button>
        </div>
    </form>
</template>

<script>
import { onMounted, reactive } from "vue";
import useSyncs from "../../composables/syncs";
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "../../validation/rules"

defineRule('required', required)
defineRule('min', min);

export default {
    setup() {
        // Define a validation schema
        const schema =
        {   status: 'required|min:3',
            origin: 'required|min:3',      }

        // Create a form context with the validation schema
        const { validate, errors } = useForm({ validationSchema: schema })

         // Define actual fields for validation
         const { value: status } = useField('status', null, { initialValue: '' });
        const { value: origin } = useField('origin', null, { initialValue: '' });

        const { storeSync,
            validationErrors,
             isLoading } = useSyncs()

        const sync = reactive({
            status,
            origin,
            textFile: ''
        })

        function submitForm() {
            validate().then(form => { if (form.valid) storeSync(sync) })
        }

        onMounted(() => {

         })

        return {
            sync,
            validationErrors,
            isLoading,
            errors,
            submitForm
        }
    }
}
</script>
