<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Header from '@/Components/Header.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, computed, reactive } from 'vue';
import Swal from 'sweetalert2';

const page = usePage();
const isLoading = ref(false);

// PTSD Questions (PCL-5)
const questions = [
    "Repeated, disturbing, and unwanted memories of the stressful experience?",
    "Repeated, disturbing dreams of the stressful experience?",
    "Suddenly feeling or acting as if the stressful experience were actually happening again?",
    "Feeling very upset when something reminded you of the stressful experience?",
    "Having strong physical reactions when something reminded you of the stressful experience?",
    "Avoiding memories, thoughts, or feelings related to the stressful experience?",
    "Avoiding external reminders of the stressful experience?",
    "Trouble remembering important parts of the stressful experience?",
    "Having strong negative beliefs about yourself, other people, or the world?",
    "Blaming yourself or someone else for the stressful experience or what happened after it?",
    "Having strong negative feelings such as fear, horror, anger, guilt, or shame?",
    "Loss of interest in activities that you used to enjoy?",
    "Feeling distant or cut off from other people?",
    "Trouble experiencing positive feelings?",
    "Irritable behavior, angry outbursts, or acting aggressively?",
    "Taking too many risks or doing things that could cause you harm?",
    "Being 'superalert' or watchful or on guard?",
    "Feeling jumpy or easily startled?",
    "Having difficulty concentrating?",
    "Trouble falling or staying asleep?",
];

// Store responses
const answers = reactive({ impact: "" });
questions.forEach((_, index) => answers[`q${index + 1}`] = null);

// PTSD Severity Levels (Updated based on PCL-5)
const severityLevels = [
    { min: 0, max: 10, label: "Minimal Symptoms" },
    { min: 11, max: 20, label: "Mild PTSD Symptoms" },
    { min: 21, max: 30, label: "Moderate PTSD Symptoms" },
    { min: 31, max: 39, label: "Probable PTSD (Clinical Threshold)" },
    { min: 40, max: 80, label: "Severe PTSD" },
];

// Compute total score (Ensuring PCL-5 standard)
const totalScore = computed(() => {
    return Object.values(answers)
        .slice(0, 20)
        .reduce((sum, val) => sum + (parseInt(val) || 0), 0);
});

// Determine severity level (Based on PCL-5 thresholds)
const severity = computed(() => {
    return (
        severityLevels.find(
            (level) => totalScore.value >= level.min && totalScore.value <= level.max
        )?.label || "Unknown"
    );
});


// Function to submit the assessment responses
const submitPTSDAssessment = async () => {
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
            router.post('/assessment/ptsd/store', {
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
                        router.get('/assessment/ptsd/results');
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
            router.get('/assessment/ptsd/results');
        }, 500);
    }
};

</script>

<template>
    <AuthenticatedLayout>
        <Head title="PTSD Assessment" />

        <div class="min-h-screen bg-blue-100 dark:bg-gray-900 flex items-center justify-center">
            <div class="w-full max-w-4xl bg-blue-200 dark:bg-gray-800 shadow-lg rounded-lg border border-blue-300 dark:border-gray-700">

                <!-- Reusable Header -->
                <Header title="PCL-5 PTSD Assessment" subtitle="Think about your worst stressful event and rate how much each symptom bothered you in the past month." />

                <div class="p-8 text-gray-900 dark:text-gray-100">

                    <!-- PTSD Questions -->
                    <div class="space-y-6">
                        <div v-for="(question, index) in questions" :key="index" class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <label class="block font-medium text-lg text-gray-800 dark:text-gray-200">
                                {{ index + 1 }}. {{ question }}
                            </label>
                            <div class="flex gap-6 mt-2">
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="0" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    Not at all
                                </label>
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="1" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    A little bit
                                </label>
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="2" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    Moderately
                                </label>
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="3" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    Quite a bit
                                </label>
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <input type="radio" :value="4" v-model="answers[`q${index + 1}`]" class="form-radio" />
                                    Extremely
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

                    <!-- PTSD Severity Explanation -->
                    <div class="mt-8 p-4 bg-blue-100 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">PTSD Severity Levels</h3>
                        <p class="text-gray-800 dark:text-gray-300">Your total score determines the severity of your PTSD symptoms based on the PCL-5 scale. A score of 33 or higher is considered clinically significant for PTSD.</p>
                        <table class="w-full mt-2 border border-gray-300 dark:border-gray-600">
                            <thead>
                                <tr class="bg-blue-300 dark:bg-gray-600">
                                    <th class="p-2 border">Total Score</th>
                                    <th class="p-2 border">Severity Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="p-2 border">0–19</td>
                                    <td class="p-2 border">Minimal or no PTSD symptoms</td>
                                </tr>
                                <tr>
                                    <td class="p-2 border">20–39</td>
                                    <td class="p-2 border">Mild PTSD symptoms</td>
                                </tr>
                                <tr>
                                    <td class="p-2 border">40–59</td>
                                    <td class="p-2 border">Moderate PTSD symptoms</td>
                                </tr>
                                <tr>
                                    <td class="p-2 border">60–79</td>
                                    <td class="p-2 border">Severe PTSD symptoms</td>
                                </tr>
                                <tr>
                                    <td class="p-2 border">80</td>
                                    <td class="p-2 border">Very severe PTSD symptoms</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button @click="submitPTSDAssessment"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">
                            {{ isLoading ? 'Submitting...' : 'Submit Assessment' }}
                        </button>
                    </div>
                     <!-- Reference Information -->
                     <div class="mt-6 p-4 bg-gray-200 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold">Reference</h3>
                        <p class="text-sm">Weathers, F. W., Litz, B. T., Keane, T. M., Palmieri, P. A., Marx, B. P., & Schnurr, P. P. (2013). The PTSD Checklist for DSM-5 (PCL-5). National Center for PTSD.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
