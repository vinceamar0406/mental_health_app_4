<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2'; // Import SweetAlert2

// Get assessment results from Laravel backend
const page = usePage();
const latestResult = page.props.latest_result || { total_score: 0, severity: "Unknown", impact: "Not provided" };
const pastResults = page.props.past_results || []; // Store previous assessments

// Store assessment data for navigation
let lastAssessment = null;

// Function to confirm retake the assessment
const confirmRetakeAssessment = () => {
    // Show first alert (confirmation to retake assessment)
    Swal.fire({
        title: "Do you want to retake the assessment?",
        text: "If you choose to retake the assessment, your previous responses will remain unchanged. Would you like to proceed?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        width: "600px", // Wider modal for better readability
        customClass: {
            container: 'swal2-container',
            popup: 'swal2-popup custom-modal',
            title: 'swal2-title text-lg font-semibold text-gray-800',
            content: 'swal2-content text-sm text-gray-600',
            confirmButton: 'swal2-confirm bg-blue-600 text-white hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-200 transition duration-300 ease-in-out rounded-lg py-2 px-6 mx-2 my-3', // Added margin and padding for spacing
            cancelButton: 'swal2-cancel bg-transparent border-2 border-gray-400 text-black hover:bg-gray-100 active:bg-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-300 transition duration-300 ease-in-out rounded-lg py-2 px-6 mx-2 my-3' // Added margin and padding for spacing
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // If confirmed, show the privacy policy notice
            showPrivacyPolicy();
        }
    });
};


// Function to show the second prompt (Data Privacy Act Notice)
const showPrivacyPolicy = (assessment) => {
    lastAssessment = assessment; // Store assessment for back navigation

    Swal.fire({
        title: "Data Privacy Notice",
        html: `
            <p><strong>Republic Act No. 10173 - Data Privacy Act of 2012</strong></p>
            <p style="margin-bottom: 15px;">This website collects, processes, and stores personal data in accordance with the Data Privacy Act of 2012 (RA 10173). By proceeding, you acknowledge and agree that:</p>
            <ul style="text-align:left; margin-bottom: 25px;">
                <li>✅ Your responses will be collected solely for providing insights into your mental health.</li>
                <li>✅ Your data will be kept confidential and will not be shared without consent.</li>
                <li>✅ This screening is for informational purposes only and is not a medical diagnosis.</li>
                <li>✅ You have the right to access, correct, or request deletion of your personal data.</li>
            </ul>
            <hr style="margin: 15px 0;">
            <p>For more details, visit the <a href="https://www.privacy.gov.ph" target="_blank" style="color: #007bff; text-decoration: underline;">National Privacy Commission (NPC)</a>.</p>
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "I Accept",
        cancelButtonText: "Cancel",
        customClass: {
            title: 'text-lg font-semibold text-orange-700 dark:text-orange-300',
            content: 'text-base text-gray-700 dark:text-gray-300',
            confirmButton: 'bg-blue-600 text-white rounded-lg py-2 px-4 hover:bg-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50',
            cancelButton: 'bg-gray-300 text-gray-800 rounded-lg py-2 px-4 hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50'
        },
        width: '600px',
    }).then((result) => {
        if (result.isConfirmed) {
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
                // Redirect to the assessment page after a delay
                window.location.href = route('anxiety_assessment');
            }, 600); // Simulating a delay before redirecting
        },
        customClass: {
            container: 'swal2-container',
            popup: 'swal2-popup custom-modal',
            title: 'swal2-title text-lg font-semibold',
            content: 'swal2-content text-sm',
            confirmButton: 'swal2-confirm bg-blue-600 text-white hover:bg-blue-700',
            cancelButton: 'swal2-cancel bg-gray-400 text-white hover:bg-gray-500'
        }
    });
};

// Function to go back to the dashboard
const goToAssessmentHistory = () => {
    router.get('/assessment/history');
};
</script>

<template>
    <Head title="Anxiety Assessment Results" />

    <AuthenticatedLayout>
        <div class="py-12 flex justify-center">
            <div class="w-full max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-blue-100 shadow-md dark:bg-gray-800 sm:rounded-lg p-6">
                    <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-300 mb-4">
                        Anxiety Assessment Results
                    </h2>

                    <!-- Latest Assessment Result -->
                    <div class="border p-4 rounded-lg bg-white dark:bg-gray-700 dark:text-gray-200 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">Latest Result</h3>
                        <p class="text-lg"><strong>Total Score:</strong> {{ latestResult.total_score }}</p>
                        <p class="text-lg"><strong>Severity Level:</strong> {{ latestResult.severity }}</p>
                        <p class="text-lg"><strong>Impact Level:</strong> {{ latestResult.impact }}</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex flex-col sm:flex-row gap-4">
                        <button
                            @click="confirmRetakeAssessment"
                            class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Retake Assessment
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
    </AuthenticatedLayout>
</template>

<style scoped>
/* You can add any custom styles here */
</style>
