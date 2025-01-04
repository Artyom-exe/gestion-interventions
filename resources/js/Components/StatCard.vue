<template>
    <div class="bg-white shadow rounded-lg p-4 text-center">
        <h3 class="text-lg font-semibold">{{ title }}</h3>
        <p class="text-2xl font-bold text-blue-600 mt-2">{{ value }}</p>
    </div>
</template>

<script setup>
const props = defineProps({
    title: String,
    value: [String, Number],
});
</script>
