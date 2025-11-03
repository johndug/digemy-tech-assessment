<template>
    <div class="fixed inset-0 z-50 overflow-y-auto" @click.self="$emit('close')">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="$emit('close')"></div>

            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md transform transition-all">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Add New Invoice</h2>
                    <button
                        @click="$emit('close')"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="handleSubmit" class="px-6 py-4">
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="title"
                                v-model="title"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                                placeholder="Enter invoice title"
                            />
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea
                                id="description"
                                v-model="description"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                                placeholder="Enter invoice description"></textarea>
                        </div>
                        <div>
                            <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-1">
                                Total Amount <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                id="total_amount"
                                v-model.number="total_amount"
                                step="0.01"
                                min="0.01"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                                placeholder="0.00"
                            />
                        </div>

                        <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
                            {{ error }}
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="$emit('close')"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                        >
                            <svg v-if="loading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span v-if="!loading">Add Invoice</span>
                            <span v-else>Adding...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useInvoicesStore } from '../../store/invoices';

const emit = defineEmits(['close', 'invoice-added']);

const invoicesStore = useInvoicesStore();
const title = ref('');
const description = ref('');
const total_amount = ref(null);
const loading = ref(false);
const error = ref('');

const handleSubmit = async () => {
    error.value = '';

    if (!title.value || !total_amount.value || total_amount.value <= 0) {
        error.value = 'Please fill in all required fields with valid values';
        return;
    }

    loading.value = true;

    try {
        await invoicesStore.createInvoice({
            title: title.value,
            description: description.value,
            total_amount: total_amount.value
        });

        title.value = '';
        description.value = '';
        total_amount.value = null;

        emit('invoice-added');
        emit('close');
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to create invoice. Please try again.';
    } finally {
        loading.value = false;
    }
};
</script>
