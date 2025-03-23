<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Header from '@/Components/Header.vue'; // Import the reusable header
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, computed, reactive } from 'vue';

const page = usePage();
const isLoading = ref(false);

// User's responses
const answers = reactive({
    q1: null, q2: null, q3: null, q4: null, q5: null,
    impact: null // Add this line to initialize impact
});

// SCOFF Questionnaire Severity Levels Explanation
const severityLevels = [
    { min: 0, max: 1, label: "Low risk" },
    { min: 2, max: 3, label: "Moderate risk" },
    { min: 4, max: 5, label: "High risk" },
];

// Compute total score by summing responses
const totalScore = computed(() => {
    return Object.values(answers).reduce((sum, val) => sum + (parseInt(val) || 0), 0);
});

// Determine severity based on the total score
const severity = computed(() => {
    return severityLevels.find(level => totalScore.value >= level.min && totalScore.value <= level.max)?.label || "Unknown";
});

// Function to submit the assessment responses
const submitAssessment = async () => {
    if (isLoading.value) return;

    // Ensure all questions are answered before submission
    const unanswered = Object.values(answers).some(val => val === null);
    if (unanswered) {
        alert("Please answer all questions before submitting.");
        return;
    }

    isLoading.value = true;

    try {
        router.post('/assessment/eating-disorder/store', {
            user_id: page.props.auth?.user?.id,
            responses: Object.values(answers), // Collect responses
            total_score: totalScore.value, // SCOFF score
            severity: severity.value, // Assigned severity level
            impact: answers.impact, // Added impact to submission
        }, {
            replace: true,
            onSuccess: () => {
                isLoading.value = false;
                router.get('/assessment/eating-disorder/results'); // Redirect to results page
            },
            onError: () => {
                isLoading.value = false;
                alert("Failed to submit assessment. Please try again.");
            }
        });

    } catch (error) {
        console.error("Error submitting assessment:", error);
        alert("Failed to submit assessment. Please try again.");
        isLoading.value = false;
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Eating Disorder Assessment" />

        <div class="min-h-screen bg-blue-100 dark:bg-gray-900 flex items-center justify-center">
            <div class="w-full max-w-4xl bg-blue-200 dark:bg-gray-800 shadow-lg rounded-lg border border-blue-300 dark:border-gray-700">

                <!-- Reusable Header Component -->
                <Header title="SCOFF Eating Disorder Screening" subtitle="Answer the following questions with Yes or No." />

                <div class="p-8 text-gray-900 dark:text-gray-100">

                    <!-- SCOFF Questions -->
                    <div class="space-y-6">
                        <div v-for="(question, index) in [
                            'Do you make yourself Sick because you feel uncomfortably full?',
                            'Do you worry you have lost Control over how much you eat?',
                            'Have you recently lost One stone (14 pounds or 6.35 kg) in a 3-month period?',
                            'Do you believe yourself to be Fat when others say you are too thin?',
                            'Would you say that Food dominates your life?'
                        ]" :key="index" class="p-4 bg-blue-100 dark:bg-gray-700 rounded-lg">
                            <label class="block font-medium text-lg text-gray-800 dark:text-gray-200">
                                {{ question }}
                            </label>
                            <div class="flex gap-6 mt-2">
                                <!-- Response options for each question -->
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="1" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    Yes
                                </label>
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="0" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    No
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Impact Dropdown -->
                    <div class="p-4 bg-blue-100 dark:bg-gray-700 rounded-lg">
                        <label class="block text-lg font-medium text-gray-800 dark:text-gray-200">Impact Level</label>
                        <select v-model="answers.impact" class="form-select mt-2 p-2 border border-gray-300 rounded-lg w-full">
                            <option value="">Select Impact Level</option>
                            <option value="Low">Low</option>
                            <option value="Moderate">Moderate</option>
                            <option value="High">High</option>
                        </select>
                    </div>

                    <!-- Eating Disorder Severity Explanation -->
                    <div class="mt-8 p-4 bg-blue-100 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Eating Disorder Severity Levels</h3>
                        <p class="text-gray-800 dark:text-gray-300">Your total score determines the severity of your eating disorder risk.</p>
                        <table class="w-full mt-2 border border-gray-300 dark:border-gray-600">
                            <thead>
                                <tr class="bg-blue-300 dark:bg-gray-600">
                                    <th class="p-2 border">Total Score</th>
                                    <th class="p-2 border">Severity Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="level in severityLevels" :key="level.label">
                                    <td class="p-2 border">{{ level.min }}–{{ level.max }}</td>
                                    <td class="p-2 border">{{ level.label }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button @click="submitAssessment"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">
                            {{ isLoading ? 'Submitting...' : 'Submit Assessment' }}
                        </button>
                    </div>
                     <!-- Reference Information -->
                     <div class="mt-6 p-4 bg-gray-200 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold">Reference</h3>
                        <p class="text-sm">Morgan, J. F., Reid, F., & Lacey, J. H. (1999). The SCOFF questionnaire: Assessment of a new screening tool for eating disorders. BMJ, 319(7223), 1467–1468.DOI: 10.1136/bmj.319.7223.1467</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
