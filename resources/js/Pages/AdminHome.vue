<script setup>
import { usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue'; // Import AdminLayout
import { Head } from '@inertiajs/vue3'; // Import the Head component
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3'; // Import Link from Inertia

const props = defineProps({
  totalUsers: Number,
  totalAssessments: Number,
  totalAppointments: Number,
  totalReports: Number,
  recentActivities: Array,
});
</script>

<template>
  <!-- Setting the page title to 'Admin Dashboard' -->
  <Head title="Admin Dashboard" />

  <!-- Using AdminLayout for consistent structure -->
  <AdminLayout>
    <template #default>
      <div class="flex flex-col items-center p-8 space-y-6">
        <!-- Dashboard Overview Section -->
        <div class="bg-white shadow-lg rounded-lg w-full max-w-6xl p-6">
          <h1 class="text-4xl font-semibold text-gray-900">Admin Dashboard</h1>
          <p class="text-gray-600 mt-2">Monitor and manage users, assessments, appointments, and reports from here.</p>
        </div>

        <!-- Stat Cards with Clickable Links -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full max-w-6xl mt-8">
          <!-- Total Users -->
          <Link :href="route('admin.users')" class="bg-blue-200 p-6 rounded-lg shadow-md text-center hover:shadow-lg">
            <p class="text-xl font-bold">Total Users</p>
            <p class="text-2xl text-blue-600 mt-2">{{ props.totalUsers }}</p>
          </Link>

          <!-- Total Assessments -->
          <Link :href="route('admin.assessments')" class="bg-green-200 p-6 rounded-lg shadow-md text-center hover:shadow-lg">
            <p class="text-xl font-bold">Total Assessments</p>
            <p class="text-2xl text-green-600 mt-2">{{ props.totalAssessments }}</p>
          </Link>

          <!-- Total Appointments -->
          <Link :href="route('admin.appointments')" class="bg-yellow-200 p-6 rounded-lg shadow-md text-center hover:shadow-lg">
            <p class="text-xl font-bold">Total Appointments</p>
            <p class="text-2xl text-yellow-600 mt-2">{{ props.totalAppointments }}</p>
          </Link>

          <!-- Total Reports -->
          <Link :href="route('admin.reports')" class="bg-red-200 p-6 rounded-lg shadow-md text-center hover:shadow-lg">
            <p class="text-xl font-bold">Total Reports</p>
            <p class="text-2xl text-red-600 mt-2">{{ props.totalReports }}</p>
          </Link>
        </div>

        <!-- Recent Activity Section -->
        <div class="bg-white shadow-lg rounded-lg w-full max-w-6xl p-6 mt-8">
          <h2 class="text-2xl font-semibold text-gray-900">Recent Activity</h2>
          <div class="overflow-x-auto mt-4">
          <table class="min-w-full">
            <thead>
              <tr class="border-b">
                <th class="text-left py-3 px-4">User</th>
                <th class="text-left py-3 px-4">Action</th>
                <th class="text-left py-3 px-4">Date</th>
              </tr>
            </thead>
            <tbody>
              <!-- Loop through the recent activities passed from the backend -->
              <tr v-for="activity in props.recentActivities" :key="activity.date" class="border-b">
                <td class="py-3 px-4">{{ activity.user }}</td>
                <td class="py-3 px-4">{{ activity.action }}</td>
                <td class="py-3 px-4">{{ activity.formatted_date }}</td> <!-- Use formatted_date here -->
              </tr>
            </tbody>
          </table>
          </div>
        </div>

        <!-- User Engagement Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full max-w-6xl mt-8">
          <div class="bg-purple-100 p-6 rounded-lg shadow-md text-center">
            <p class="text-xl font-bold">Active Users</p>
            <button class="mt-4 px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600">Manage Users</button>
          </div>
          <div class="bg-teal-100 p-6 rounded-lg shadow-md text-center">
            <p class="text-xl font-bold">Assessments Completed</p>
            <button class="mt-4 px-4 py-2 bg-teal-500 text-white rounded-md hover:bg-teal-600">Review Assessments</button>
          </div>
          <div class="bg-orange-100 p-6 rounded-lg shadow-md text-center">
            <p class="text-xl font-bold">Appointments Scheduled</p>
            <button class="mt-4 px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600">Manage Appointments</button>
          </div>
        </div>

      </div>
    </template>
  </AdminLayout>
</template>

<style scoped>
/* Additional custom styles for the dashboard can go here */
</style>
