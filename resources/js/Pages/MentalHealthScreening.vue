<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2'; // Import SweetAlert2

// Define form using an array for responses
const form = useForm({
    responses: ['', '', '', '', ''], // Array to hold responses
});

// Function to validate if all responses are filled
const validateResponses = () => {
    return form.responses.every(response => response.trim() !== '');
};

// Function to confirm before submitting
// Function to confirm before submitting
const confirmSubmission = async () => {
    if (!validateResponses()) {
        Swal.fire({
            title: "Incomplete Responses",
            text: "Please complete all questions before submitting.",
            icon: "warning",
            confirmButtonText: "OK",
        });
        return;
    }

    const result = await Swal.fire({
        title: "Submit Screening?",
        text: "Do you want to save your responses in the database?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, save it!",
        cancelButtonText: "No, just view results",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33"
    });

    if (result.isConfirmed) {
        submitScreening(); // Save responses to the database
    } else {
        viewResultsOnly(); // Skip saving and just show results
    }
};

// Function to submit the screening (Save to Database)
const submitScreening = () => {
    Swal.fire({
        title: "Submitting...",
        text: "Please wait while we process your responses.",
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    form.post(route('screening.submit'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                title: "Saved!",
                text: "Your screening has been recorded.",
                icon: "success",
                showConfirmButton: false,
                timer: 2000
            });

            form.reset(); // Clear form after successful submission

            setTimeout(() => {
                window.location.href = route('screening.results'); // Redirect to results page
            }, 2000);
        },
        onError: () => {
            Swal.fire({
                title: "Submission Failed",
                text: "An error occurred while submitting. Please try again later.",
                icon: "error",
                confirmButtonText: "OK",
            });
        }
    });
};

// Function to skip saving and just show results
const viewResultsOnly = () => {
    Swal.fire({
        title: "Redirecting...",
        text: "Please wait while we load your results.",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    setTimeout(() => {
        Swal.close();
        window.location.href = route('screening.results'); // Redirect without saving
    }, 500);
};
</script>

<template>
    <Head title="Mental Health Screening" />

    <AuthenticatedLayout>
        <!-- Page Header -->
        <template #header>
            <h2 class="text-2xl font-bold leading-tight text-black dark:text-white">
                Mental Health Screening
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-blue-200 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p class="mt-2 text-gray-800">
                            Please answer the following questions in detail to help assess your mental well-being.
                        </p>

                        <!-- Text Input Questions -->
                        <div class="mt-4" v-for="(question, index) in [
                            'Describe your recent emotional state and how it has impacted your daily life.',
                            'Have you been experiencing difficulties with sleep, appetite, or energy levels? Explain.',
                            'What are some recurring thoughts or worries that have been on your mind lately?',
                            'Have you noticed any changes in your behavior, social interactions, or coping strategies?',
                            'Is there anything specific that has been overwhelming or distressing for you recently?'
                        ]" :key="index">
                            <p class="text-sm text-gray-700 font-semibold">{{ question }}</p>
                            <textarea
                                v-model="form.responses[index]"
                                class="w-full mt-2 p-3 border rounded-lg bg-blue-100 text-black placeholder-gray-600
                                       focus:ring focus:ring-blue-300"
                                rows="4"
                                placeholder="Write your response here..."
                                required
                            ></textarea>
                        </div>

                        <!-- Submit Button with Confirmation -->
                        <button
                            @click="confirmSubmission"
                            :disabled="form.processing"
                            class="mt-6 px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-400 transition-all
                                   focus:ring-2 focus:ring-blue-300 disabled:opacity-50">
                            {{ form.processing ? 'Submitting...' : 'Submit Screening' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
