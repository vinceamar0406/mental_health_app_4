<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';

// Get assessment results from Laravel backend
const page = usePage();
const latestResult = page.props.latest_result || { total_score: 0, severity: "Unknown", impact: "Not provided" };
const pastResults = page.props.past_results || []; // Store previous assessments

// Function to retake the assessment
const retakeAssessment = () => {
    router.visit('/assessment/anxiety', { method: 'get', preserveState: false });
};

// Function to go back to the dashboard
const goToAssessmentHistory = () => {
    router.get('/assessment/history');
};
</script>

<template>
    <Head title="Eating Disorder Assessment Results" />

    <AuthenticatedLayout>
        <div class="py-12 flex justify-center">
            <div class="w-full max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-blue-100 shadow-md dark:bg-gray-800 sm:rounded-lg p-6">
                    <h2 class="text-2xl font-semibold text-blue-800 dark:text-blue-300 mb-4">
                        Eating Disorder Assessment Results
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
                            @click="retakeAssessment"
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
