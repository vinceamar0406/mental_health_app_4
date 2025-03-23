<template>
    <header class="bg-blue-600 text-white p-6 rounded-t-lg dark:bg-blue-500 text-center">
        <h1 class="text-3xl font-bold">{{ title }}</h1>
        <p v-if="subtitle" class="mt-1 text-sm text-blue-100">{{ subtitle }}</p>
    </header>
</template>

<script setup>
defineProps({
    title: String,     // Title for the header
    subtitle: String   // Optional subtitle (description)
});
</script>
