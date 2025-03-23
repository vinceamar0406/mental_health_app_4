<script setup>
import { ref } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2'; // Import SweetAlert2
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Define props to receive data from the controller
defineProps({
    message: String,
    predicted_condition: String,
});

// Store assessment data
let lastAssessment = null;

// Function to confirm retake the screening with SweetAlert
const confirmRetakeScreening = () => {
    // Show first alert (confirmation to retake screening)
    Swal.fire({
        title: "Do you want to retake the assessment?",
        text: "Your current results have been saved. If you'd like to retake the screening, you can proceed.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, Retake",
        cancelButtonText: "No, Stay",
        width: "600px", // Wider modal for better readability
    }).then((result) => {
        if (result.isConfirmed) {
            // If confirmed, show the privacy policy notice
            showPrivacyPolicy();
        }
    });
};

// Function to show the second prompt (Data Privacy Act Notice)
const showPrivacyPolicy = () => {
    Swal.fire({
        title: "Data Privacy Notice",
        html: `
            <p><strong>Republic Act No. 10173 - Data Privacy Act of 2012</strong></p>
            <p>This website collects, processes, and stores personal data in accordance with the Data Privacy Act of 2012 (RA 10173). By proceeding, you acknowledge and agree that:</p>
            <ul style="text-align:left;">
                <li>✅ Your responses will be collected solely for providing insights into your mental health.</li>
                <li>✅ Your data will be kept confidential and will not be shared without consent.</li>
                <li>✅ This screening is for informational purposes only and is not a medical diagnosis.</li>
                <li>✅ You have the right to access, correct, or request deletion of your personal data.</li>
            </ul>
            <p>For more details, visit the <a href="https://www.privacy.gov.ph" target="_blank" style="color:blue;">National Privacy Commission (NPC)</a>.</p>
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "I Accept",
        cancelButtonText: "Cancel",
        width: "600px",
    }).then((result) => {
        if (result.isConfirmed) {
            // If accepted, show the loading animation
            showLoadingAnimation();
        }
    });
};

// Function to show a loading animation before proceeding
const showLoadingAnimation = () => {
    Swal.fire({
        title: "Preparing Your Assessment...",
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
            setTimeout(() => {
                // Redirect to the screening page after a delay
                window.location.href = route('mental_health_screening');
            }, 600); // Simulating a delay before redirecting
        }
    });
};

// Handle back button navigation to reshow the last opened modal
window.onpopstate = function () {
    if (lastAssessment) {
        retakeScreening(); // Reinitiate the process if the user goes back
    }
};

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

                        <!-- Action Buttons -->
                        <div class="mt-6 flex flex-col sm:flex-row gap-4">
                            <button
                                @click="confirmRetakeScreening"
                                class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Retake Screening
                            </button>
                            <button
                                @click="goToAssessmentHistory"
                                class="w-full sm:w-auto px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                View Assessment History
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* You can add any custom styles here */
</style>
