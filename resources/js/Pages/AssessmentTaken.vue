<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';  // Import AdminLayout for consistent sidebar
import { Head, Link } from '@inertiajs/vue3';
import { defineProps, ref, computed } from 'vue';  // Import ref and computed

const props = defineProps({
  assessments: Array,  // Accepting the assessments prop
});

// Define a reactive search query
const searchQuery = ref('');

// Create a computed property to filter assessments based on the search query
const filteredAssessments = computed(() => {
  return props.assessments.filter(assessment => {
    // Format the created_at date to MM/DD/YYYY format
    const formattedDate = new Date(assessment.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

    // Check if the search query matches the user name, assessment type, or date
    return (
      assessment.user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      assessment.assessment_type.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      formattedDate.includes(searchQuery.value)
    );
  });
});
</script>

<template>
  <Head title="Assessment Taken" />
  <AdminLayout> <!-- Using AdminLayout for consistent sidebar -->
    <div class="flex-1 p-6">
      <div class="bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-4xl font-bold text-gray-800">Assessment Taken</h1>
        <p class="text-lg text-gray-700 mt-2">View all completed assessments.</p>

        <!-- Return Button -->
        <Link :href="route('admin.home')"
              class="inline-block mt-4 px-6 py-3 bg-blue-500 text-white rounded-lg shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-200 ease-in-out"
              role="button"
              aria-label="Return to Home">
          &larr; Return to Home
        </Link>

        <!-- Search Input -->
        <div class="mt-6 mb-4">
          <input
            v-model="searchQuery"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg"
            placeholder="Search by user name, assessment type, or date"
          />
        </div>

        <!-- Assessment List Table -->
        <div class="mt-6 overflow-x-auto">
          <table class="w-full border-collapse border border-gray-700 sm:table-auto">
            <thead class="bg-blue-200">
              <tr>
                <th class="border border-gray-700 px-4 py-2 text-left">ID</th>
                <th class="border border-gray-700 px-4 py-2 text-left">User</th>
                <th class="border border-gray-700 px-4 py-2 text-left">Assessment Type</th>
                <th class="border border-gray-700 px-4 py-2 text-left">Score</th>
                <th class="border border-gray-700 px-4 py-2 text-left">Date Taken</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="assessment in filteredAssessments" :key="assessment.id" class="border-b border-gray-700 hover:bg-blue-50">
                <td class="border border-gray-700 px-4 py-2 text-gray-800">{{ assessment.id }}</td>
                <td class="border border-gray-700 px-4 py-2 text-gray-800">{{ assessment.user.name }}</td>
                <td class="border border-gray-700 px-4 py-2 text-gray-800">{{ assessment.assessment_type }}</td>
                <td class="border border-gray-700 px-4 py-2 text-gray-800">{{ assessment.total_score }}</td>
                <td class="border border-gray-700 px-4 py-2 text-gray-800">{{ new Date(assessment.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>
/* Additional custom styles */
table {
  width: 100%;
  margin-top: 20px;
}

th, td {
  text-align: left;
}

tr:hover {
  background-color: #f0f4f8;
}
</style>
