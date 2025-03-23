<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Substance Use Disorder Assessment" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                            Substance Use Disorder Assessment
                        </h2>
                        <p class="mt-2">Answer the following questions to assess your substance use disorder symptoms.</p>

                        <!-- Substance Use Disorder Assessment Form -->
                        <form class="mt-4">
                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300">Question 1</label>
                                <input type="text" class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300">Question 2</label>
                                <input type="text" class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">
                            </div>

                            <button type="submit" class="px-4 py-2 font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
