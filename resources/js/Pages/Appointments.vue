<template>
    <Head title="Appointments" />
    <AdminLayout>
      <div class="flex-1 p-8 space-y-8">
        <!-- Title and Return Button Section -->
        <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-6xl mx-auto flex flex-col sm:flex-row justify-between items-center">
          <div>
            <h1 class="text-4xl font-semibold text-gray-900">Appointments</h1>
            <p class="text-gray-600 mt-2">Manage all appointment requests efficiently.</p>
          </div>
          <Link :href="route('admin.home')"
                class="inline-block px-6 py-3 bg-blue-500 text-white rounded-lg shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-200 ease-in-out mt-4 sm:mt-0"
                role="button"
                aria-label="Return to Home">
            &larr; Return to Home
          </Link>
        </div>

        <!-- Search Input Section -->
        <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-6xl mx-auto">
          <input
            v-model="searchQuery"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg"
            placeholder="Search by user name, assessment, or status..."
          />
        </div>

        <!-- Appointments Table Section -->
        <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-6xl mx-auto overflow-x-auto">
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

                <!-- Scheduled Date Column -->
                <td class="border border-gray-700 px-4 py-2 text-gray-800">
                  <span v-if="appointment.appointment_date">
                    {{ new Date(appointment.appointment_date).toLocaleString('en-US', {
                      year: 'numeric',
                      month: 'long',
                      day: 'numeric',
                      hour: 'numeric',
                      minute: 'numeric',
                      second: 'numeric',
                      hour12: true
                    }) }}
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
                  <div v-if="appointment.status === 'pending'" class="flex items-center space-x-6">
                    <input
                      v-model="appointment.scheduled_at"
                      type="datetime-local"
                      class="p-2 border-2 rounded-lg w-36 bg-gray-700 text-white"
                      style="height: 40px; line-height: 40px;"
                    />
                    <button
                      @click="approveAppointment(appointment)"
                      class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg"
                      style="height: 40px; display: flex; align-items: center; justify-content: center;"
                    >
                      Approve Appointment
                    </button>
                  </div>
                  <button
                    v-if="appointment.status === 'approved'"
                    @click="completeAppointment(appointment.id)"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg mx-auto block"
                  >
                    Mark as Completed
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </AdminLayout>
  </template>

  <script setup>
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { Head, Link } from '@inertiajs/vue3';
  import { defineProps, computed, ref } from 'vue';
  import axios from 'axios';
  import Swal from 'sweetalert2';

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
    // Get the selected date and time
    const selectedDate = new Date(appointment.scheduled_at);

    // Get the day of the week (0 = Sunday, 6 = Saturday)
    const dayOfWeek = selectedDate.getDay();

    // Get the hour and minute of the selected date
    const hour = selectedDate.getHours();
    const minute = selectedDate.getMinutes();

    // Validate the selected time
    if (
      (hour >= 8 && hour < 11) ||  // 8 AM - 11 AM
      (hour >= 13 && hour < 16)    // 1 PM - 4 PM
    ) {
      // Validate the selected day (Monday to Saturday)
      if (dayOfWeek === 0) { // If it's Sunday
        Swal.fire("Invalid Day!", "Appointments cannot be scheduled on Sundays.", "error");
        return;
      }

      try {
        Swal.fire({
          title: "Approving Appointment...",
          text: "Please wait while we process the request.",
          imageUrl: 'https://via.placeholder.com/150',
          showConfirmButton: false,
          allowOutsideClick: false,
          willOpen: () => {
            Swal.showLoading();
          }
        });

        const response = await axios.post(`/appointments/${appointment.id}/approve`, {
          scheduled_at: appointment.scheduled_at,
        });

        const updatedAppointment = response.data.appointment;
        const index = props.appointments.findIndex((app) => app.id === updatedAppointment.id);
        if (index !== -1) {
          props.appointments[index] = updatedAppointment;
        }

        Swal.fire("Success!", "Appointment approved.", "success");
        location.reload();
      } catch (error) {
        Swal.fire("Error!", "Something went wrong.", "error");
      }
    } else {
      Swal.fire("Invalid Time!", "Appointments can only be scheduled between 8 AM - 11 AM or 1 PM - 4 PM.", "error");
    }
  };

  const completeAppointment = async (appointmentId) => {
    const result = await Swal.fire({
      title: "Are you sure?",
      text: "Do you want to mark this appointment as completed?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, mark as completed!",
    });

    if (result.isConfirmed) {
      // Show loading spinner after confirmation
      Swal.fire({
        title: "Processing...",
        text: "Please wait while we mark this appointment as completed.",
        imageUrl: 'https://via.placeholder.com/150',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      try {
        // Send request to mark appointment as completed
        await axios.post(`/appointments/${appointmentId}/complete`);

        // Update SweetAlert to success state
        Swal.fire("Success!", "Appointment marked as completed.", "success");

        // Reload the page to reflect the updated appointment list
        location.reload();
      } catch (error) {
        // Handle error state
        Swal.fire("Error!", "Something went wrong while completing the appointment.", "error");
      }
    }
  };
  </script>

  <style scoped>
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

  @media (max-width: 640px) {
    .w-full {
      width: 100%;
    }
    .max-w-6xl {
      max-width: 100%;
    }
    .sm\:table-auto {
      table-layout: auto;
    }
  }
  </style>
