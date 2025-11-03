<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full text-center">
            <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-2">Page Not Found</h2>
            <p class="text-gray-600 mb-8">The page you are looking for does not exist.</p>
            <router-link
                to="/dashboard"
                class="inline-block bg-gray-800 hover:bg-gray-700 text-white px-6 py-3 rounded-md font-medium transition-colors"
            >
                Go to Dashboard
            </router-link>
        </div>
    </div>
</template>
