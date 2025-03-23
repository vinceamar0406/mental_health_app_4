<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Header from '@/Components/Header.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, computed, reactive } from 'vue';

const page = usePage();
const isLoading = ref(false);

//Set the page title dynamically
page.props.title = "Depression Assessment"

// User's responses
const answers = reactive({
    q1: null, q2: null, q3: null, q4: null, q5: null, q6: null, q7: null, q8: null, q9: null, impact: ""
});

// PHQ-9 Severity Levels
const severityLevels = [
    { min: 0, max: 4, label: "Minimal Depression" },
    { min: 5, max: 9, label: "Mild Depression" },
    { min: 10, max: 14, label: "Moderate Depression" },
    { min: 15, max: 19, label: "Moderately Severe Depression" },
    { min: 20, max: 27, label: "Severe Depression" },
];

// Compute total score
const totalScore = computed(() => {
    return Object.values(answers).slice(0, 9).reduce((sum, val) => sum + (parseInt(val) || 0), 0);
});

// Determine severity
const severity = computed(() => {
    return severityLevels.find(level => totalScore.value >= level.min && totalScore.value <= level.max)?.label || "Unknown";
});

// Submit function
const submitAssessment = async () => {
    if (isLoading.value) return;

    const unanswered = Object.values(answers).slice(0, 9).some(val => val === null);
    if (unanswered || !answers.impact) {
        alert("Please answer all questions and select an impact level before submitting.");
        return;
    }

    isLoading.value = true;

    try {
        router.post('/assessment/depression/store', {
            user_id: page.props.auth?.user?.id,
            responses: Object.values(answers).slice(0, 9),
            impact: answers.impact,
            total_score: totalScore.value,
            severity: severity.value
        }, {
            replace: true,
            onSuccess: () => {
                isLoading.value = false;
                router.get('/assessment/depression/results');
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
        <Head title="Depression Assessment" />

        <div class="min-h-screen bg-blue-100 dark:bg-gray-900 flex items-center justify-center">
            <div class="w-full max-w-4xl bg-blue-200 dark:bg-gray-800 shadow-lg rounded-lg border border-blue-300 dark:border-gray-700">

                <!-- Reusable Header -->
                <Header title="PHQ-9 Depression Assessment" subtitle="Over the last two weeks, how often have you been bothered by the following problems?" />

                <div class="p-8 text-blue-900 dark:text-blue-100">

                    <!-- PHQ-9 Questions -->
                    <div class="space-y-6">
                        <div v-for="(question, index) in [
                            'Little interest or pleasure in doing things',
                            'Feeling down, depressed, or hopeless',
                            'Trouble falling or staying asleep, or sleeping too much',
                            'Feeling tired or having little energy',
                            'Poor appetite or overeating',
                            'Feeling bad about yourself, or that you are a failure',
                            'Trouble concentrating on things',
                            'Moving or speaking so slowly that others notice',
                            'Thoughts of self-harm or suicidal thoughts'
                        ]" :key="index" class="p-4 bg-blue-100 dark:bg-blue-700 rounded-lg">
                            <label class="block font-medium text-lg text-gray-800 dark:text-gray-300">
                                {{ index + 1 }}. {{ question }}
                            </label>
                            <div class="flex gap-6 mt-2">
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="0" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    Not at all
                                </label>
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="1" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    Several days
                                </label>
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="2" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    More than half the days
                                </label>
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="3" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    Nearly every day
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Impact Level -->
                    <div class="mt-8 p-4 bg-blue-100 dark:bg-blue-700 rounded-lg">
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
                        </div>
                    </div>

                     <!-- Depression Severity Explanation -->
                     <div class="mt-8 p-4 bg-blue-100 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Depression Severity Levels</h3>
                        <p class="text-gray-800 dark:text-gray-300">Your total score determines the severity of your anxiety symptoms based on the PHQ-9 scale.</p>
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
                            <p class="text-sm text-gray-800 dark:text-gray-300">
                            Kroenke, K., Spitzer, R. L., & Williams, J. B. W. (2001). The PHQ-9: Validity of a brief depression severity measure. Journal of General Internal Medicine, 16(9), 606–613.DOI: 10.1046/j.1525-1497.2001.016009606.x
                            </p>
                        </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
