<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Header from '@/Components/Header.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, computed, reactive } from 'vue';
import Swal from 'sweetalert2';

const page = usePage();
const isLoading = ref(false);

// Set the page title dynamically
page.props.title = "Stress Assessment";

// User's responses
const answers = reactive({
    q1: null, q2: null, q3: null, q4: null, q5: null,
    q6: null, q7: null, q8: null, q9: null, q10: null,
    impact: ""
});

// Perceived Stress Scale (PSS) Severity Levels
const severityLevels = [
    { min: 0, max: 13, label: "Low Stress" },
    { min: 14, max: 26, label: "Moderate Stress" },
    { min: 27, max: 40, label: "High Stress" },
];

// Compute total score
const totalScore = computed(() => {
    return Object.values(answers).slice(0, 10).reduce((sum, val) => sum + (parseInt(val) || 0), 0);
});

// Determine severity level
const severity = computed(() => {
    return severityLevels.find(level => totalScore.value >= level.min && totalScore.value <= level.max)?.label || "Unknown";
});

// Submit function
// Function to submit the assessment responses
const submitAssessment = async () => {
    if (isLoading.value) return;

    // Find the first unanswered question
    const unansweredKey = Object.keys(answers).find(key => answers[key] === null || answers[key] === "");

    if (unansweredKey) {
        Swal.fire({
            icon: "error",
            title: "Incomplete Answers",
            text: "Please answer all questions before submitting.",
            confirmButtonColor: "#3085d6",
        });

        return; // Stop further execution
    }

    // Confirmation modal
    const result = await Swal.fire({
        title: "Submit Assessment?",
        text: "Do you want to save the assessment in the database?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, save it!",
        cancelButtonText: "No, just view results",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33"
    });

    if (result.isConfirmed) {
        Swal.fire({
            title: "Submitting...",
            text: "Please wait while we save your assessment.",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        isLoading.value = true;
        try {
            router.post('/assessment/stress-related/store', {
                user_id: page.props.auth?.user?.id,
                responses: Object.values(answers).slice(0, 10),
                impact: answers.impact,
                total_score: totalScore.value,
                severity: severity.value
            }, {
                replace: true,
                onSuccess: () => {
                    isLoading.value = false;
                    Swal.fire({
                        title: "Saved!",
                        text: "Your assessment has been recorded.",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 2000
                    });

                    setTimeout(() => {
                        router.get('/assessment/stress-related/results');
                    }, 2000);
                },
                onError: () => {
                    isLoading.value = false;
                    Swal.fire("Error", "Failed to submit assessment. Please try again.", "error");
                }
            });

        } catch (error) {
            console.error("Error submitting assessment:", error);
            Swal.fire("Error", "Failed to submit assessment. Please try again.", "error");
            isLoading.value = false;
        }
    } else {
        Swal.fire({
            title: "Redirecting...",
            text: "Please wait while we load your results.",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        setTimeout(() => {
            Swal.close(); // Close loading before redirecting
            router.get('/assessment/stress-related/results');
        }, 500);
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Stress Assessment" />

        <div class="min-h-screen bg-blue-100 dark:bg-gray-900 flex items-center justify-center">
            <div class="w-full max-w-4xl bg-blue-200 dark:bg-gray-800 shadow-lg rounded-lg border border-blue-300 dark:border-gray-700">

                <!-- Reusable Header -->
                <Header title="Perceived Stress Scale (PSS)" subtitle="In the last month, how often have you experienced the following?" />

                <div class="p-8 text-gray-900 dark:text-gray-100">

                    <!-- Stress Questions -->
                    <div class="space-y-6">
                        <div v-for="(question, index) in [
                            'In the last month, how often have you been upset because of something that happened unexpectedly?',
                            'In the last month, how often have you felt that you were unable to control the important things in your life?',
                            'In the last month, how often have you felt nervous and stressed?',
                            'In the last month, how often have you felt confident about your ability to handle your personal problems?',
                            'In the last month, how often have you felt that things were going your way?',
                            'In the last month, how often have you found that you could not cope with all the things that you had to do?',
                            'In the last month, how often have you been able to control irritations in your life?',
                            'In the last month, how often have you felt that you were on top of things?',
                            'In the last month, how often have you been angered because of things that happened that were outside of your control?',
                            'In the last month, how often have you felt difficulties were piling up so high that you could not overcome them?'
                        ]" :key="index" class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <label class="block font-medium text-lg text-gray-800 dark:text-gray-200">
                                {{ index + 1 }}. {{ question }}
                            </label>
                            <div class="flex gap-6 mt-2">
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="0" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    Never
                                </label>
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="1" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    Almost Never
                                </label>
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="2" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    Sometimes
                                </label>
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="3" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    Fairly Often
                                </label>
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="4" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    Very Often
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Impact Level -->
                    <div class="mt-8 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <label class="block font-medium text-lg text-gray-800 dark:text-gray-200">
                            How difficult have these problems made it for you to function in daily life?
                        </label>
                        <div class="flex gap-6 mt-2">
                            <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                <input type="radio" value="Not difficult at all" v-model="answers.impact" class="form-radio" />
                                Not difficult at all
                            </label>
                            <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                <input type="radio" value="Somewhat difficult" v-model="answers.impact" class="form-radio" />
                                Somewhat difficult
                            </label>
                            <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                <input type="radio" value="Very difficult" v-model="answers.impact" class="form-radio" />
                                Very difficult
                            </label>
                            <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                <input type="radio" value="Extremely difficult" v-model="answers.impact" class="form-radio" />
                                Extremely difficult
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button @click="submitAssessment"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">
                            {{ isLoading ? 'Submitting...' : 'Submit Assessment' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
