<template>
    <div id="app">
        <router-view />
    </div>
</template>

<script setup>
import { useAuthStore } from './store/auth'
import { onMounted } from 'vue';
// App component with router view
const auth = useAuthStore();

onMounted(() => {
    auth.fetchUser();
});
</script>

<style>
#app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
</style>

