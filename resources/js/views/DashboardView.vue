<template>
    <Navbar />
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Dashboard | Invoices</h1>
            <div class="mb-6">
                <button class="px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600 transition-colors" @click="addInvoice">
                    Add Invoice
                </button>
            </div>
            <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
                <div class="bg-gray-100 border-b border-gray-200">
                    <div class="grid grid-cols-12 gap-4 px-6 py-3 text-sm font-semibold text-gray-700">
                        <div class="col-span-1">ID</div>
                        <div class="col-span-2">Title</div>
                        <div class="col-span-3">Description</div>
                        <div class="col-span-2">Total Amount</div>
                        <div class="col-span-2">State</div>
                        <div class="col-span-2 text-center">Actions</div>
                    </div>
                </div>
                <div class="divide-y divide-gray-200">
                    <template v-for="invoice in invoices" :key="invoice.id">
                        <div
                            class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-gray-50 transition-colors"
                            :class="{ 'opacity-50 pointer-events-none': loadingInvoice && deletingInvoiceId === invoice.id }"
                        >
                            <div class="col-span-1 text-gray-900">{{ invoice.id }}</div>
                            <div class="col-span-2 text-gray-700">{{ invoice.title }}</div>
                            <div class="col-span-3 text-gray-700">{{ invoice.description }}</div>
                            <div class="col-span-2 text-gray-900 font-medium">{{ numberToCurrency(invoice.total_amount) }}</div>
                            <div class="col-span-2">
                                <InvoiceState :state="invoice.state" />
                            </div>
                            <div class="col-span-2 text-center space-x-2">
                                <button
                                    class="px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600 transition-colors"
                                    @click="handleView(invoice.id)"
                                    :disabled="loadingInvoice && deletingInvoiceId === invoice.id"
                                >
                                    {{ expandedInvoiceId === invoice.id ? 'Hide' : 'View' }}
                                </button>
                                <button
                                    class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
                                    @click="handleDelete(invoice.id)"
                                    :disabled="loadingInvoice && deletingInvoiceId === invoice.id"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                        <div v-if="expandedInvoiceId === invoice.id" class="bg-gray-50 border-t border-gray-300">
                            <div class="px-6 py-3 border-b border-gray-300">
                                <h3 class="text-sm font-semibold text-gray-700">Payments</h3>
                            </div>
                            <div class="grid grid-cols-12 gap-4 px-6 py-2 text-xs font-semibold text-gray-600 bg-gray-100">
                                <div class="col-span-1">ID</div>
                                <div class="col-span-2">Amount</div>
                                <div class="col-span-2">Date</div>
                            </div>
                            <Payments
                                :payments="invoice.payments || []"
                                :invoice-id="invoice.id"
                                :invoice-amount="invoice.total_amount"
                            />
                        </div>
                    </template>
                    <div v-if="loading" class="px-6 py-8 text-center text-gray-500">
                        <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-gray-500 mx-auto"></div>
                    </div>
                    <div v-else-if="invoices.length === 0" class="px-6 py-8 text-center text-gray-500">
                        No invoices found
                    </div>
                </div>
            </div>
            <AddInvoiceView v-if="isAddInvoiceModalOpen" @close="closeAddInvoiceModal" @invoice-added="handleInvoiceAdded" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useInvoicesStore } from '../store/invoices';
import Navbar from '../components/Navbar.vue';
import InvoiceState from '../components/InvoiceState.vue';
import Payments from './partials/PaymentView.vue';
import AddInvoiceView from './partials/AddInvoiceView.vue';
import { numberToCurrency } from '../utils/numberToCurrency';

const invoicesStore = useInvoicesStore();
const { invoices, loading } = storeToRefs(invoicesStore);
const expandedInvoiceId = ref(null);
const isAddInvoiceModalOpen = ref(false);
const loadingInvoice = ref(false);
const deletingInvoiceId = ref(null);

const handleView = (id) => {
    if (expandedInvoiceId.value === id) {
        expandedInvoiceId.value = null;
    } else {
        expandedInvoiceId.value = id;
    }
}

const addInvoice = () => {
    isAddInvoiceModalOpen.value = true;
    document.body.style.overflow = 'hidden';
}

const handleInvoiceAdded = async () => {
    await invoicesStore.getInvoices();
}

const closeAddInvoiceModal = () => {
    isAddInvoiceModalOpen.value = false;
    document.body.style.overflow = 'auto';
}

const handleDelete = async (id) => {
    if (!confirm('Are you sure you want to delete this invoice?')) {
        return;
    }

    try {
        loadingInvoice.value = true;
        deletingInvoiceId.value = id;
        await invoicesStore.deleteInvoice(id);
        await invoicesStore.getInvoices();
    } catch (error) {
        console.error('Error deleting invoice:', error);
    } finally {
        loadingInvoice.value = false;
        deletingInvoiceId.value = null;
    }
}

onMounted(async () => {
    await invoicesStore.getInvoices();
});
</script>


