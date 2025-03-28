<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Swal from 'sweetalert2';
import axios from 'axios'; // Ensure axios is imported

// Fetch past assessments from Laravel backend
const page = usePage();
const assessments = ref(page.props.assessments || []);

// Format date function
const formatDate = (dateString) => {
    return dateString ? new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    }) : "No date available";
};

// Severity badge styling
const getSeverityClass = (severity) => {
    switch (severity) {
        case 'Mild': return 'bg-blue-300 text-gray-700';
        case 'Moderate': return 'bg-blue-300 text-gray-700';
        case 'Severe': return 'bg-blue-300 text-gray-700';
        default: return 'bg-blue-300 text-gray-700';
    }
};

// Sorting assessments by date (newest first)
const sortedAssessments = computed(() => {
    return [...assessments.value].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

// Request Appointment function with loading state
const isLoading = ref(false);
const requestAppointment = async (assessmentId) => {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to request an appointment for this assessment?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, request appointment!',
    });

    if (result.isConfirmed) {
        try {
            isLoading.value = true;
            const response = await axios.post(route('appointments.request'), { assessment_id: assessmentId });

            if (response.status === 200) {
                // Update the assessment status dynamically
                const assessment = assessments.value.find(a => a.id === assessmentId);
                if (assessment) {
                    assessment.appointment_status = 'pending';
                }

                Swal.fire(
                    'Appointment Requested!',
                    'Your appointment request has been sent and is pending approval.',
                    'success'
                );
            }
        } catch (error) {
            console.error("Error requesting appointment:", error);
            Swal.fire(
                'Error!',
                error.response?.data?.message || 'An unexpected error occurred. Please try again.',
                'error'
            );
        } finally {
            isLoading.value = false;
        }
    }
};
</script>

<template>
    <Head title="Assessment History" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold leading-tight text-gray-700 dark:text-white">
                Assessment History
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-md sm:rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">
                        Your Past Assessments
                    </h3>

                    <!-- Assessment History Table -->
                    <div v-if="sortedAssessments.length > 0">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-blue-100 text-gray-800 text-left">
                                    <th scope="col" class="py-4 px-5 border-b">Date</th>
                                    <th scope="col" class="py-4 px-5 border-b">Type</th>
                                    <th scope="col" class="py-4 px-5 border-b">Score</th>
                                    <th scope="col" class="py-4 px-5 border-b">Severity</th>
                                    <th scope="col" class="py-4 px-5 border-b">Impact</th>
                                    <th scope="col" class="py-4 px-5 border-b">Appointment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="assessment in sortedAssessments" :key="assessment.id"
                                    class="border-b border-gray-300 hover:bg-gray-100 transition">
                                    <td class="py-4 px-5 text-gray-700">
                                        {{ formatDate(assessment.created_at) }}
                                    </td>
                                    <td class="py-4 px-5 text-gray-800 font-medium">
                                        {{ assessment.assessment_type || 'Unknown' }}
                                    </td>
                                    <td class="py-4 px-5 text-gray-800 font-semibold">
                                        {{ assessment.total_score ?? 'N/A' }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <span class="px-4 py-2 rounded-lg text-sm font-semibold" :class="getSeverityClass(assessment.severity)">
                                            {{ assessment.severity || 'Unknown' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 text-gray-700">
                                        {{ assessment.impact ?? 'Not Available' }}
                                    </td>
                                    <td class="py-4 px-5 text-center">
                                        <template v-if="assessment.appointment_status === 'none'">
                                            <button
                                                @click="requestAppointment(assessment.id)"
                                                :disabled="isLoading"
                                                class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-lg transition focus:ring-2 focus:ring-blue-300 disabled:bg-gray-300 disabled:cursor-not-allowed">
                                                <span v-if="isLoading">Processing...</span>
                                                <span v-else>Request Appointment</span>
                                            </button>
                                        </template>
                                        <template v-else-if="assessment.appointment_status === 'pending'">
                                            <span class="text-yellow-500 font-semibold">
                                                Pending Approval
                                            </span>
                                            <div v-if="assessment.appointment_date">
                                                <span class="text-gray-600">Scheduled for: {{ formatDate(assessment.appointment_date) }}</span>
                                            </div>
                                        </template>
                                        <template v-else-if="assessment.appointment_status === 'approved'">
                                            <span class="text-green-500 font-semibold">
                                                Approved - {{ formatDate(assessment.schedule_at) }}
                                            </span>
                                        </template>
                                        <template v-else-if="assessment.appointment_status === 'completed'">
                                            <span class="text-gray-600 font-semibold">Completed</span>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- No Past Assessments -->
                    <div v-else class="text-center py-6">
                        <p class="text-gray-700">No past assessments found.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
