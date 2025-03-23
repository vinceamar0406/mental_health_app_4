<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Users } from 'lucide-vue-next';

const props = defineProps({
  users: Array,
});
</script>

<template>
  <Head title="User List" />
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-900 min-h-screen"> <!-- Dark Background -->
      <h1 class="text-3xl font-bold text-white">User List</h1>
      <p class="text-white">Manage all registered users.</p>

      <!-- Return Button -->
      <Link :href="route('admin.home')"
            class="inline-block mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition">
        &larr; Return to Home
      </Link>

      <!-- User List Table -->
      <div class="mt-6 overflow-x-auto">
        <table class="w-full border-collapse border border-gray-700">
          <thead>
            <tr class="bg-gray-800 text-white">
              <th class="border border-gray-700 px-4 py-2 text-left">ID</th>
              <th class="border border-gray-700 px-4 py-2 text-left">Name</th>
              <th class="border border-gray-700 px-4 py-2 text-left">Email</th>
              <th class="border border-gray-700 px-4 py-2 text-left">Role</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in props.users" :key="user.id" class="border-b border-gray-700 hover:bg-gray-700">
              <td class="border border-gray-700 px-4 py-2 text-white">{{ user.id }}</td>
              <td class="border border-gray-700 px-4 py-2 text-white">{{ user.name }}</td>
              <td class="border border-gray-700 px-4 py-2 text-white">{{ user.email }}</td>
              <td class="border border-gray-700 px-4 py-2 text-white">{{ user.role }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
