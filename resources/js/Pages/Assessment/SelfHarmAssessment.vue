<script setup>
import { onMounted } from 'vue';
import Swal from 'sweetalert2'; // Import SweetAlert
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

onMounted(() => {
    // Show SweetAlert when the component is mounted
    Swal.fire({
        title: 'Maintenance Mode',
        text: 'The assessment is temporarily unavailable and is under maintenance. We apologize for the inconvenience.',
        icon: 'warning',
        showConfirmButton: false, // Hides the default "OK" button
        showCancelButton: true,  // Shows only the "Go Back" button
        cancelButtonText: 'Go Back', // Text for the "Go Back" button
        reverseButtons: true, // To place the "Go Back" button on the left
        focusCancel: true, // Focus on "Go Back" button by default
    }).then((result) => {
        if (result.isDismissed) {
            // Redirect to the previous page when "Go Back" is clicked
            window.history.back();
        }
    });
});
</script>

<template>
    <Head title="Self-Harm Challenges Assessment" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                            Self-Harm Challenges Assessment
                        </h2>
                        <p class="mt-2">Answer the following questions to assess self-harm challenges.</p>

                        <!-- Self-Harm Challenges Assessment Form -->
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
