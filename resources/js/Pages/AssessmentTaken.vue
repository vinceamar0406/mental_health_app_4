<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
  assessments: Array,
});
</script>

<template>
  <Head title="Assessment Taken" />
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-900 min-h-screen"> <!-- Dark Background -->
      <h1 class="text-3xl font-bold text-white">Assessment Taken</h1>
      <p class="text-white">View all completed assessments.</p>

      <!-- Return Button -->
      <Link :href="route('admin.home')"
            class="inline-block mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition">
        &larr; Return to Home
      </Link>

      <!-- Assessment Table -->
      <div class="mt-6 overflow-x-auto">
        <table class="w-full border-collapse border border-gray-700">
          <thead>
            <tr class="bg-gray-800 text-white">
              <th class="border border-gray-700 px-4 py-2 text-left">ID</th>
              <th class="border border-gray-700 px-4 py-2 text-left">User</th>
              <th class="border border-gray-700 px-4 py-2 text-left">Assessment Type</th>
              <th class="border border-gray-700 px-4 py-2 text-left">Score</th>
              <th class="border border-gray-700 px-4 py-2 text-left">Date Taken</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="assessment in props.assessments" :key="assessment.id" class="border-b border-gray-700 hover:bg-gray-700">
              <td class="border border-gray-700 px-4 py-2 text-white">{{ assessment.id }}</td>
              <td class="border border-gray-700 px-4 py-2 text-white">{{ assessment.user.name }}</td>
              <td class="border border-gray-700 px-4 py-2 text-white">{{ assessment.type }}</td>
              <td class="border border-gray-700 px-4 py-2 text-white">{{ assessment.score }}</td>
              <td class="border border-gray-700 px-4 py-2 text-white">{{ assessment.date_taken }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
