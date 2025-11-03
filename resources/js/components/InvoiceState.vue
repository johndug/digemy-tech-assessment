<template>
    <span
        v-if="state"
        class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white"
        :style="{ backgroundColor: state.color, color: state.font_color }"
    >
        {{ state.description }}
    </span>
    <span v-else class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-gray-500 bg-gray-200">
        -
    </span>
</template>

<script setup>
const props = defineProps({
    state: {
        type: Object,
        required: false,
        default: null,
    },
});
</script>
