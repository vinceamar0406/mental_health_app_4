<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Define props to receive data from the controller
defineProps({
    message: String,
    predicted_condition: String,
});
</script>

<template>
    <Head title="Screening Result" />

    <AuthenticatedLayout>
        <!-- Main Content -->
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                            Screening Result
                        </h2>
                        <p class="mt-4">Thank you for completing the screening. Here's the result:</p>

                        <!-- Display the predicted condition -->
                        <div class="mt-4">
                            <p class="font-medium text-lg">Predicted Condition:</p>
                            <p v-if="predicted_condition !== 'No result available.'" class="mt-2 text-xl text-blue-600 dark:text-blue-400">
                                {{ predicted_condition }}
                            </p>
                            <p v-else class="mt-2 text-xl text-red-600 dark:text-red-400">
                                No result available. Please try again later.
                            </p>
                        </div>

                        <!-- Display the message -->
                        <p class="mt-4 text-sm text-gray-700 dark:text-gray-300">{{ message }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* You can add any custom styles here */
</style>
