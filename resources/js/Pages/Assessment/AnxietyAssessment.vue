<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Header from '@/Components/Header.vue'; // Import the reusable header
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, computed, reactive } from 'vue';
import Swal from 'sweetalert2';

const page = usePage();
const isLoading = ref(false);

// Set the page title dynamically
page.props.title = "Anxiety Assessment"

// User's responses
const answers = reactive({
    q1: null, q2: null, q3: null, q4: null, q5: null, q6: null, q7: null, impact: ""
});

// GAD-7 Severity Levels Explanation
const severityLevels = [
    { min: 0, max: 4, label: "Minimal Anxiety" }, // Low anxiety level
    { min: 5, max: 9, label: "Mild Anxiety" }, // Mild symptoms that might not require treatment
    { min: 10, max: 14, label: "Moderate Anxiety" }, // Consider seeking help
    { min: 15, max: 21, label: "Severe Anxiety" }, // Strongly consider professional support
];

// Compute total score by summing responses
const totalScore = computed(() => {
    return Object.values(answers).slice(0, 7).reduce((sum, val) => sum + (parseInt(val) || 0), 0);
});

// Determine severity based on the total score
const severity = computed(() => {
    return severityLevels.find(level => totalScore.value >= level.min && totalScore.value <= level.max)?.label || "Unknown";
});

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
            router.post('/assessment/anxiety/store', {
                user_id: page.props.auth?.user?.id,
                responses: Object.values(answers).slice(0, 7),
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
                        router.get('/assessment/anxiety/results');
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
            router.get('/assessment/anxiety/results');
        }, 500);
    }
};

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Anxiety Assessment" />

        <div class="min-h-screen bg-blue-100 dark:bg-gray-900 flex items-center justify-center">
            <div class="w-full max-w-4xl bg-blue-200 dark:bg-gray-800 shadow-lg rounded-lg border border-blue-300 dark:border-gray-700">
                <!-- Reusable Header Component -->
                <Header title="GAD-7 Anxiety Assessment" subtitle="Over the last two weeks, how often have you been bothered by the following problems?" />

                <div class="p-8 text-blue-900 dark:text-blue-100">

                    <!-- GAD-7 Questions -->
                    <div class="space-y-6">
                        <div v-for="(question, index) in [
    'Feeling nervous, anxious, or on edge',
    'Not being able to stop or control worrying',
    'Worrying too much about different things',
    'Trouble relaxing',
    'Being so restless that it is hard to sit still',
    'Becoming easily annoyed or irritable',
    'Feeling afraid, as if something awful might happen'
]" :key="index"
:data-question="`q${index + 1}`"
class="p-4 bg-blue-100 dark:bg-gray-700 rounded-lg">
                            <label class="block font-medium text-lg text-gray-800 dark:text-gray-200">
                                {{ index + 1 }}. {{ question }}
                            </label>
                            <div class="flex gap-6 mt-2">
                                <!-- Response options for each question -->
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
                    <!-- Impact Question -->
                    <div class="mt-8 p-4 bg-blue-100 dark:bg-blue-700 rounded-lg">
                        <label class="block font-medium text-lg text-gray-800 dark:text-gray-200">
                            If you checked any problems, how difficult have they made it for you to do your work, take care of things at home, or get along with other people?
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
                    <!-- Anxiety Severity Explanation -->
                    <div class="mt-8 p-4 bg-blue-100 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Anxiety Severity Levels</h3>
                        <p class="text-gray-800 dark:text-gray-300">Your total score determines the severity of your anxiety symptoms based on the GAD-7 scale.</p>
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
                        <p class="text-sm text-gray-800 dark:text-gray-300">Spitzer, R. L., Kroenke, K., Williams, J. B. W., & Löwe, B. (2006). A brief measure for assessing generalized anxiety disorder: The GAD-7. Archives of Internal Medicine, 166(10), 1092–1097. DOI: 10.1001/archinte.166.10.1092</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
