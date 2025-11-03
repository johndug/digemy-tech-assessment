<template>
    <template v-if="displayPayments && displayPayments.length > 0">
        <div
            v-for="payment in displayPayments"
            :key="payment.id"
            class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-gray-200 hover:bg-gray-50 transition-colors"
        >
            <div class="col-span-1 text-gray-900">{{ payment.id }}</div>
            <div class="col-span-2 text-gray-700 font-medium">{{ numberToCurrency(payment.amount) }}</div>
            <div class="col-span-2 text-gray-600">{{ formatDate(payment.created_at) }}</div>
            <div class="col-span-7 text-right">
                <button
                    @click="handleDelete(payment.id)"
                    :disabled="loading || deletingPaymentId !== null"
                    class="px-3 py-1 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors text-xs disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span v-if="deletingPaymentId !== payment.id">Delete</span>
                    <span v-else-if="deletingPaymentId === payment.id" class="flex items-center gap-1">
                        <svg class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Deleting...
                    </span>
                </button>
            </div>
        </div>
    </template>
    <div v-else class="px-6 py-4 text-center text-gray-500 text-sm">
        No payments found
    </div>
    <div class="px-6 py-4 border-t border-gray-300 bg-white" v-if="totalPaid < invoiceAmount">
        <div class="flex gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Amount</label>
                <input
                    type="number"
                    step="0.01"
                    min="0.01"
                    :max="remainingAmount"
                    v-model.number="amount"
                    :disabled="loading"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    placeholder="Enter amount"
                />
                <p v-if="remainingAmount > 0" class="mt-1 text-xs text-gray-500">
                    Remaining: {{ numberToCurrency(remainingAmount) }}
                </p>
            </div>
            <button
                @click="addPayment"
                :disabled="!amount || amount <= 0 || loading"
                class="px-6 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
            >
                <svg v-if="loading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span v-if="!loading">Add Payment</span>
                <span v-else>Adding...</span>
            </button>
        </div>
        <div v-if="error" class="mt-2 text-sm text-red-600">
            {{ error }}
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { numberToCurrency } from '../../utils/numberToCurrency';
import { usePaymentStore } from '../../store/payment';
import { useInvoicesStore } from '../../store/invoices';

const props = defineProps({
    payments: {
        type: Array,
        required: true,
        default: () => [],
    },
    invoiceId: {
        type: Number,
        required: true,
    },
    invoiceAmount: {
        type: Number,
        required: true,
    },
});


const paymentStore = usePaymentStore();
const invoicesStore = useInvoicesStore();
const amount = ref(null);
const loading = ref(false);
const deletingPaymentId = ref(null);
const error = ref('');

const displayPayments = computed(() => {
    return props.payments;
});

const totalPaid = computed(() => {
    return props.payments.reduce((sum, payment) => sum + parseFloat(payment.amount || 0), 0);
});

const remainingAmount = computed(() => {
    return props.invoiceAmount - totalPaid.value;
});

const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const addPayment = async () => {
    error.value = '';

    if (!amount.value || amount.value <= 0) {
        error.value = 'Please enter a valid amount';
        return;
    }

    if (amount.value > remainingAmount.value) {
        error.value = `Amount cannot exceed remaining amount of ${numberToCurrency(remainingAmount.value)}`;
        return;
    }

    loading.value = true;

    try {
        await paymentStore.createPayment({
            invoice_id: props.invoiceId,
            amount: amount.value
        });
        amount.value = null;
        await invoicesStore.getInvoice(props.invoiceId);
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to add payment. Please try again.';
    } finally {
        loading.value = false;
    }
};

const handleDelete = async (paymentId) => {
    if (!confirm('Are you sure you want to delete this payment?')) {
        return;
    }

    error.value = '';
    deletingPaymentId.value = paymentId;
    loading.value = true;


    try {
        await paymentStore.deletePayment(paymentId);
        await invoicesStore.getInvoice(props.invoiceId);
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to delete payment. Please try again.';
    } finally {
        loading.value = false;
        deletingPaymentId.value = null;
    }
};
</script>
