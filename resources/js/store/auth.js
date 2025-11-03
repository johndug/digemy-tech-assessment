import { defineStore } from 'pinia';
import axiosInstance from '../api/axios';
import { ref } from 'vue';

export const useAuthStore = defineStore('auth', () => {
    // Initialize from localStorage if available
    const storedToken = localStorage.getItem('token');
    const storedUser = localStorage.getItem('user');

    const user = ref(storedUser ? JSON.parse(storedUser) : null);
    const token = ref(storedToken);
    const loading = ref(false);

    const login = async (email, password) => {
        if (email === '' || password === '') {
            throw new Error('Email and password are required');
        }
        loading.value = true;
        try {
            const response = await axiosInstance.post('/login', { email, password });

            // Handle response structure - check if data.data exists, otherwise use data directly
            const responseData = response.data?.data || response.data;

            if (!responseData?.token) {
                throw new Error('Token not found in response');
            }

            token.value = responseData.token;
            user.value = responseData.user;

            localStorage.setItem('token', token.value);
            localStorage.setItem('user', JSON.stringify(user.value));

            return response.data;
        } catch (error) {
            console.error('Login error:', error);
            throw error;
        }
        finally {
            loading.value = false;
        }
    }

    const logout = async () => {
        axiosInstance.post('/logout');

        user.value = null;
        token.value = null;
        localStorage.removeItem('token');
        localStorage.removeItem('user');

        loading.value = false;

        router.push('/login');
    }

    const fetchUser = async () => {
        if (!token.value) return

        try {
            const response = await axiosInstance.get('/me');
            user.value = response.data;
        } catch {
            logout();
        }
    }

    return { user, token, loading, login, logout, fetchUser };
});
