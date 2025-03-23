<template>
    <div class="py-12 bg-gray-800 min-h-screen">
      <!-- Return Button -->
      <Link
        :href="route('admin.home')"
        class="inline-block mt-4 px-6 py-3 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition"
      >
        &larr; Return to Home
      </Link>

      <h1 class="text-3xl font-bold text-white mt-6 mb-8">Appointments</h1>

      <!-- Search & Filter -->
      <div class="flex flex-col md:flex-row md:justify-between mb-6">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search by user name, assessment, or status..."
          class="p-3 border-2 rounded-lg w-full md:w-1/3 bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <!-- Appointments Table (Desktop) -->
      <div class="hidden md:block">
        <table class="w-full bg-gray-900 shadow-md rounded-lg overflow-hidden">
          <thead class="bg-gray-800 text-white">
            <tr>
              <th scope="col" class="p-5 text-left">User</th>
              <th scope="col" class="p-5 text-left">Assessment Type</th>
              <th scope="col" class="p-5 text-left">Scheduled Date</th>
              <th scope="col" class="p-5 text-left">Status</th>
              <th scope="col" class="p-5 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="appointment in filteredAppointments" :key="appointment.id" class="border-t hover:bg-gray-700">
              <td class="p-5 text-white">{{ appointment.user.name }}</td>
              <td class="p-5 text-white">{{ appointment.assessment.type }}</td>
              <td class="p-5 text-white">
                <span v-if="appointment.scheduled_at">
                  {{ new Date(appointment.scheduled_at).toLocaleString() }}
                </span>
                <span v-else>Not scheduled yet</span>
              </td>
              <td class="p-5">
                <span
                  class="px-4 py-2 rounded-lg text-sm font-semibold"
                  :class="statusClass(appointment.status)"
                >
                  {{ appointment.status }}
                </span>
              </td>
              <td class="p-5 text-center">
                <div v-if="appointment.status === 'pending'">
                  <input
                    v-model="appointment.scheduled_at"
                    type="datetime-local"
                    class="p-3 border-2 rounded-lg w-36 mb-2 bg-gray-700 text-white"
                    :min="minScheduledDate"
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

      <!-- Appointments Cards (Mobile) -->
      <div class="md:hidden">
        <div
          v-for="appointment in filteredAppointments"
          :key="appointment.id"
          class="bg-gray-700 p-5 mb-6 rounded-lg shadow-md"
        >
          <div class="mb-4">
            <h4 class="text-lg font-semibold text-white">User:</h4>
            <p class="text-white">{{ appointment.user.name }}</p>
          </div>
          <div class="mb-4">
            <h4 class="text-lg font-semibold text-white">Assessment Type:</h4>
            <p class="text-white">{{ appointment.assessment.type }}</p>
          </div>
          <div class="mb-4">
            <h4 class="text-lg font-semibold text-white">Scheduled Date:</h4>
            <p class="text-white">
              <span v-if="appointment.scheduled_at">
                {{ new Date(appointment.scheduled_at).toLocaleString() }}
              </span>
              <span v-else>Not scheduled yet</span>
            </p>
          </div>
          <div class="mb-4">
            <h4 class="text-lg font-semibold text-white">Status:</h4>
            <span
              class="px-4 py-2 rounded-lg text-sm font-semibold"
              :class="statusClass(appointment.status)"
            >
              {{ appointment.status }}
            </span>
          </div>
          <div class="mt-4">
            <div v-if="appointment.status === 'pending'">
              <input
                v-model="appointment.scheduled_at"
                type="datetime-local"
                class="p-3 border-2 rounded-lg w-full mb-4 bg-gray-700 text-white"
                :min="minScheduledDate"
              />
              <button
                @click="approveAppointment(appointment)"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg w-full"
              >
                Approve Appointment
              </button>
            </div>
            <button
              v-if="appointment.status === 'approved'"
              @click="completeAppointment(appointment.id)"
              class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg w-full"
            >
              Mark as Completed
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { Link } from "@inertiajs/vue3";
  import { defineProps, ref, computed } from "vue";
  import axios from "axios";
  import Swal from "sweetalert2"; // Assuming SweetAlert2 is included in your project

  // Receive appointments as a prop from Laravel
  const props = defineProps({
    appointments: Array,
  });

  const searchQuery = ref("");
  const minScheduledDate = ref(new Date().toISOString().slice(0, 16));

  // Filtered list based on search query
  const filteredAppointments = computed(() => {
    return props.appointments.filter((a) =>
      a.user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      a.assessment.type.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      a.status.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  });

  // Status class mapping
  const statusClass = (status) => {
    return {
      pending: "bg-red-500",
      approved: "bg-blue-500",
      completed: "bg-green-500",
    }[status] || "bg-gray-500";
  };

  // Approve appointment
  const approveAppointment = async (appointment) => {
    try {
      const response = await axios.post(`/appointments/${appointment.id}/approve`, {
        scheduled_at: appointment.scheduled_at,
      });
      if (response.status === 200) {
        // Find the updated appointment and update the status in the frontend
        const updatedAppointment = response.data.appointment;
        const index = props.appointments.findIndex(a => a.id === updatedAppointment.id);

        if (index !== -1) {
          props.appointments[index] = updatedAppointment; // Update the appointment in the list
        }

        Swal.fire(
          'Appointment Approved!',
          'The appointment has been approved and scheduled.',
          'success'
        );
      }
    } catch (error) {
      console.error('Error approving appointment:', error);
      Swal.fire(
        'Error!',
        error.response?.data?.message || 'An unexpected error occurred. Please try again.',
        'error'
      );
    }
  };

  // Complete appointment
  const completeAppointment = async (id) => {
    try {
      const response = await axios.post(`/appointments/${id}/complete`);
      const appointment = props.appointments.find(a => a.id === id);
      if (appointment) {
        appointment.status = 'completed';
        // Optionally, you can set the scheduled date here too, based on the response
      }
    } catch (error) {
      console.error("Error completing appointment:", error);
      alert("An error occurred while completing the appointment. Please try again.");
    }
  };
  </script>
