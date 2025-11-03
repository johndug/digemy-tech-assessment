import { defineStore } from 'pinia';
import axiosInstance from '../api/axios';

export const usePaymentStore = defineStore('payment', () => {
    const createPayment = async (payment) => {
        try {
            const response = await axiosInstance.post('/payments', payment);
            return response.data?.data || response.data;
        } catch (error) {
            console.error('Error creating payment:', error);
            throw error;
        }
    }

    const deletePayment = async (id) => {
        try {
            await axiosInstance.delete(`/payments/${id}`);
            return true;
        } catch (error) {
            console.error('Error deleting payment:', error);
            throw error;
        }
    }

    return { createPayment, deletePayment };
});
