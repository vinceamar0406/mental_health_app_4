<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';  // Import AdminLayout
import { Head, Link } from '@inertiajs/vue3';
import { defineProps, ref, computed } from 'vue'; // Import ref and computed

const props = defineProps({
  users: Array,
});

// Define a reactive search query
const searchQuery = ref('');

// Create a computed property to filter users based on the search query
const filteredUsers = computed(() => {
  return props.users.filter(user => {
    return user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
           user.email.toLowerCase().includes(searchQuery.value.toLowerCase());
  });
});
</script>

<template>
  <Head title="User List" />
  <AdminLayout> <!-- Using AdminLayout for consistent sidebar -->
    <div class="flex-1 p-8 space-y-8">
      <!-- Title and Return Button Section -->
      <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-6xl mx-auto flex justify-between items-center">
        <div>
          <h1 class="text-4xl font-semibold text-gray-900">User List</h1>
          <p class="text-gray-600 mt-2">Manage all registered users.</p>
        </div>
        <Link :href="route('admin.home')"
              class="inline-block px-6 py-3 bg-blue-500 text-white rounded-lg shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-200 ease-in-out"
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
          placeholder="Search by name or email"
        />
      </div>

      <!-- User List Table Section -->
      <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-6xl mx-auto overflow-x-auto">
        <table class="w-full border-collapse border border-gray-700 sm:table-auto">
          <thead class="bg-blue-200">
            <tr>
              <th class="border border-gray-700 px-4 py-2 text-left">ID</th>
              <th class="border border-gray-700 px-4 py-2 text-left">Name</th>
              <th class="border border-gray-700 px-4 py-2 text-left">Email</th>
              <th class="border border-gray-700 px-4 py-2 text-left">Role</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in filteredUsers" :key="user.id" class="border-b border-gray-700 hover:bg-blue-50">
              <td class="border border-gray-700 px-4 py-2 text-gray-800">{{ user.id }}</td>
              <td class="border border-gray-700 px-4 py-2 text-gray-800">{{ user.name }}</td>
              <td class="border border-gray-700 px-4 py-2 text-gray-800">{{ user.email }}</td>
              <td class="border border-gray-700 px-4 py-2 text-gray-800">{{ user.role }}</td>
            </tr>
          </tbody>
        </table>
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
