import { defineStore } from 'pinia';
import { ref } from 'vue';
import axiosInstance from '../api/axios';

export const useInvoicesStore = defineStore('invoices', () => {
    const invoices = ref([]);
    const loading = ref(false);
    const loadingInvoice = ref(false);

    const getInvoices = async () => {
        try {
            loading.value = true;
            const response = await axiosInstance.get('/invoices');

            invoices.value = response.data?.data || [];
        } catch (error) {
            console.error('Error getting invoices:', error);
        } finally {
            loading.value = false;
        }
    }

    const getInvoice = async (id) => {
        try {
            loading.value = true;
            const response = await axiosInstance.get(`/invoices/${id}`);
            const updated = response.data?.data;

            if (updated && updated.id) {
                const idx = invoices.value.findIndex(inv => inv.id === updated.id);
                if (idx !== -1) {
                    // Replace the existing invoice row reactively
                    invoices.value[idx] = { ...invoices.value[idx], ...updated };
                } else {
                    invoices.value.unshift(updated);
                }
            }

            return updated || [];
        } catch (error) {
            console.error('Error getting invoice:', error);
        } finally {
            loading.value = false;
        }
    }

    const createInvoice = async (invoice) => {
        try {
            loading.value = true;
            const response = await axiosInstance.post('/invoices', invoice);
            return response.data?.data || [];
        } catch (error) {
            console.error('Error creating invoice:', error);
        } finally {
            loading.value = false;
        }
    }

    const updateInvoice = async (id, invoice) => {
        try {
            loading.value = true;
            const response = await axiosInstance.put(`/invoices/${id}`, invoice);
            return response.data?.data || [];
        } catch (error) {
            console.error('Error updating invoice:', error);
        } finally {
            loading.value = false;
        }
    }

    const deleteInvoice = async (id) => {
        try {
            loadingInvoice.value = true;
            const response = await axiosInstance.delete(`/invoices/${id}`);
            invoices.value = invoices.value.filter(inv => inv.id !== id);
            return true;
        } catch (error) {
            console.error('Error deleting invoice:', error);
        } finally {
            loadingInvoice.value = false;
        }
    }

    return { invoices, loading, getInvoices, getInvoice, createInvoice, updateInvoice, deleteInvoice };
});
