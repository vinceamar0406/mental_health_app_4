<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';  // Import AdminLayout
import { Head, Link } from '@inertiajs/vue3';
import { defineProps, computed, ref } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';  // Assuming SweetAlert2 is included in your project

const props = defineProps({
  appointments: Array,
});

const searchQuery = ref("");
const filteredAppointments = computed(() => {
  return props.appointments.filter((appointment) => {
    return (
      appointment.user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      appointment.assessment.type.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      appointment.status.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  });
});

const statusClass = (status) => {
  switch (status) {
    case "pending":
      return "bg-yellow-500";
    case "approved":
      return "bg-blue-600";
    case "completed":
      return "bg-green-600";
    default:
      return "bg-gray-500";
  }
};

const approveAppointment = async (appointment) => {
  try {
    await axios.post(`/appointments/${appointment.id}/approve`, { scheduled_at: appointment.scheduled_at });
    Swal.fire("Success!", "Appointment approved.", "success");
  } catch (error) {
    Swal.fire("Error!", "Something went wrong.", "error");
  }
};

const completeAppointment = async (id) => {
  try {
    await axios.post(`/appointments/${id}/complete`);
    Swal.fire("Success!", "Appointment marked as completed.", "success");
  } catch (error) {
    Swal.fire("Error!", "Something went wrong.", "error");
  }
};
</script>

<template>
  <Head title="Appointments" />
  <AdminLayout>
    <div class="flex-1 p-6">
      <div class="bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-4xl font-bold text-gray-800">Appointments</h1>
        <p class="text-lg text-gray-700 mt-2">Manage all appointment requests.</p>

        <!-- Return Button -->
        <Link :href="route('admin.home')"
              class="inline-block mt-4 px-6 py-3 bg-blue-500 text-white rounded-lg shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-200 ease-in-out"
              role="button"
              aria-label="Return to Home">
          &larr; Return to Home
        </Link>

        <!-- Search & Filter -->
        <div class="mt-6">
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Search by user name, assessment, or status..."
            class="p-3 border-2 rounded-lg w-full bg-gray-200 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-200 ease-in-out"
          />
        </div>

        <!-- Appointments Table -->
        <div class="mt-6 overflow-x-auto">
          <table class="w-full border-collapse border border-gray-700 sm:table-auto">
            <thead class="bg-blue-200">
              <tr>
                <th class="border border-gray-700 px-4 py-2 text-left">User</th>
                <th class="border border-gray-700 px-4 py-2 text-left">Assessment Type</th>
                <th class="border border-gray-700 px-4 py-2 text-left">Scheduled Date</th>
                <th class="border border-gray-700 px-4 py-2 text-left">Status</th>
                <th class="border border-gray-700 px-4 py-2 text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="appointment in filteredAppointments" :key="appointment.id" class="border-b border-gray-700 hover:bg-blue-50">
                <td class="border border-gray-700 px-4 py-2 text-gray-800">{{ appointment.user.name }}</td>
                <td class="border border-gray-700 px-4 py-2 text-gray-800">{{ appointment.assessment.type }}</td>
                <td class="border border-gray-700 px-4 py-2 text-gray-800">
                  <span v-if="appointment.scheduled_at">
                    {{ new Date(appointment.scheduled_at).toLocaleString() }}
                  </span>
                  <span v-else>Not scheduled yet</span>
                </td>
                <td class="border border-gray-700 px-4 py-2">
                  <span
                    class="px-4 py-2 rounded-lg text-sm font-semibold"
                    :class="statusClass(appointment.status)"
                  >
                    {{ appointment.status }}
                  </span>
                </td>
                <td class="border border-gray-700 px-4 py-2 text-center">
                  <div v-if="appointment.status === 'pending'">
                    <input
                      v-model="appointment.scheduled_at"
                      type="datetime-local"
                      class="p-3 border-2 rounded-lg w-36 mb-2 bg-gray-700 text-white"
                    />
                    <button
                      @click="approveAppointment(appointment)"
                      class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg"
                    >
                      Approve Appointment
                    </button>
                  </div>
                  <button
                    v-if="appointment.status === 'approved'"
                    @click="completeAppointment(appointment.id)"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg"
                  >
                    Mark as Completed
                  </button>
                </td>
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
