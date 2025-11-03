<template>
    <nav class="bg-gray-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <router-link
                        to="/dashboard"
                        class="text-gray-200 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors"
                        active-class="text-white bg-gray-700"
                    >
                        Dashboard
                    </router-link>
                </div>
                <div>
                    <button
                        @click="handleLogout"
                        class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                    >
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '../store/auth'

const router = useRouter()
const auth = useAuthStore()

const handleLogout = () => {
    auth.logout()
    router.push('/login')
}
</script>
