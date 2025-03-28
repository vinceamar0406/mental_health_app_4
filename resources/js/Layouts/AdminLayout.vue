<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { ref } from 'vue';

// Get current URL to manage active link state
const { url } = usePage();

// Notifications state
const notifications = ref([
  "New appointment request from Jane Smith.",
  "New assessment completed by John Doe.",
  "User registration approval pending for Michael Johnson."
]);

// Function to clear notifications
const clearNotifications = () => {
  notifications.value = [];
};
</script>

<template>
  <div class="min-h-screen bg-blue-100 dark:bg-gray-900">
    <!-- Top Navbar (Admin) -->
    <nav class="border-b border-blue-300 bg-blue-200 dark:border-blue-300 dark:bg-blue-300">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
          <!-- Logo and Navigation Links -->
          <div class="flex items-center">
            <div class="flex shrink-0 items-center">
              <Link :href="route('admin.home')">
                <img src="/images/counselcarelogo.png" alt="Counsel & Care Logo" class="h-9 w-auto">
              </Link>
            </div>

            <!-- Admin Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
              <NavLink
                :href="route('admin.home')"
                :active="route().current('admin.home')"
                class="px-4 py-2 rounded-md hover:bg-blue-300 transition-all duration-200"
              >
                Dashboard
              </NavLink>
              <NavLink
                :href="route('admin.users')"
                :active="route().current('admin.users')"
                class="px-4 py-2 rounded-md hover:bg-blue-300 transition-all duration-200"
              >
                Users
              </NavLink>
              <NavLink
                :href="route('admin.assessments')"
                :active="route().current('admin.assessments')"
                class="px-4 py-2 rounded-md hover:bg-blue-300 transition-all duration-200"
              >
                Assessments
              </NavLink>
              <NavLink
                :href="route('admin.appointments')"
                :active="route().current('admin.appointments')"
                class="px-4 py-2 rounded-md hover:bg-blue-300 transition-all duration-200"
              >
                Appointments
              </NavLink>
            </div>
          </div>

          <!-- Notifications and User Profile Dropdown -->
          <div class="hidden sm:ms-6 sm:flex sm:items-center">
            <!-- Notifications Bell -->
            <div class="relative ms-3">
              <button class="p-2 text-white bg-blue-500 rounded-full" @click="clearNotifications">
                <span class="sr-only">Open notifications</span>
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405C18.526 15.658 18 14.457 18 13V9a6 6 0 10-12 0v4c0 1.457-.526 2.658-1.595 2.595L4 17h5m6 0V9a3 3 0 00-6 0v8m6 4h-6" />
                </svg>
              </button>

              <!-- Notifications Dropdown -->
              <div class="absolute right-0 mt-2 w-72 bg-white shadow-lg rounded-lg">
                <div class="py-2 px-4 text-lg font-semibold text-gray-900">
                  Notifications
                </div>
                <ul class="divide-y divide-gray-200">
                  <li v-for="(notification, index) in notifications" :key="index" class="px-4 py-2 text-sm text-gray-700">
                    {{ notification }}
                  </li>
                </ul>
                <div class="p-2 text-center">
                  <button @click="clearNotifications" class="text-blue-500 hover:text-blue-700">
                    Clear All
                  </button>
                </div>
              </div>
            </div>

            <!-- User Profile Dropdown -->
            <div class="relative ms-3">
              <Dropdown align="right" width="48">
                <template #trigger>
                  <button type="button" class="flex items-center rounded-full bg-gray-800 text-sm focus:ring-2 focus:ring-white">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-8 h-8 rounded-full"
                         :src="$page.props.auth.user.profile_photo_url || 'https://ui-avatars.com/api/?name=' + $page.props.auth.user.name"
                         alt="User Profile Picture" />
                  </button>
                </template>

                <template #content>
                  <DropdownLink :href="route('profile.edit')">
                    Edit Profile
                  </DropdownLink>
                  <DropdownLink :href="route('logout')" method="post" as="button">
                    Log Out
                  </DropdownLink>
                </template>
              </Dropdown>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content Section -->
    <main>
      <slot />
    </main>
  </div>
</template>

<style scoped>
/* Custom styles for the admin layout can go here */
</style>
