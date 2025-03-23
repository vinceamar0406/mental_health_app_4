<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { BarChart3, Users, FileText, Calendar } from 'lucide-vue-next';

const props = defineProps({
  totalUsers: Number,
  totalAssessments: Number,
  totalAppointments: Number,
  totalReports: Number,
});

// Define admin stats with real-time data and correct routes
const adminStats = [
  { title: "Total Users", value: props.totalUsers, icon: Users, route: route('admin.users') },
  { title: "Assessments Taken", value: props.totalAssessments, icon: FileText, route: route('admin.assessments') },
  { title: "Upcoming Appointments", value: props.totalAppointments, icon: Calendar, route: route('admin.appointments') },
  { title: "Reports Generated", value: props.totalReports, icon: BarChart3, route: route('admin.reports') },
];
</script>

<template>
  <Head title="Admin Dashboard" />
  <AuthenticatedLayout>
    <div class="p-6">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
      <p class="text-gray-600 dark:text-gray-400">Manage users, assessments, and appointments.</p>

      <!-- Dashboard Cards with Navigation -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        <Link v-for="(stat, index) in adminStats" :key="index" :href="stat.route" class="block">
          <div class="p-4 shadow-lg rounded-xl bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            <div class="flex items-center space-x-4">
              <component :is="stat.icon" class="w-10 h-10 text-blue-500" />
              <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ stat.title }}</h3>
                <p class="text-xl font-bold text-gray-700 dark:text-gray-300">{{ stat.value }}</p>
              </div>
            </div>
          </div>
        </Link>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
