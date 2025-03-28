<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Get current URL to manage active link state
const { url } = usePage();

// Notifications state
const notifications = ref([]);
const isDropdownOpen = ref(false); // State to track if the notification dropdown is open
const isLoading = ref(false); // State for loading status
const error = ref(null); // State to capture any errors

// Fetch notifications from the server when the component is mounted
onMounted(() => {
  fetchNotifications();
});

// Function to fetch notifications
const fetchNotifications = async () => {
    isLoading.value = true;
    error.value = null;

    try {
      const response = await axios.get('/admin/notifications');
      notifications.value = response.data.notifications || []; // Ensure we always have an array
    } catch (err) {
      error.value = err;
      console.error('Error fetching notifications:', err);
    } finally {
      isLoading.value = false;
    }
};

// Function to mark notifications as read and enlarge the clicked notification
const markAsReadAndEnlarge = async (index) => {
  const notification = notifications.value[index];
  // Mark the notification as read on the server
  try {
    await axios.post(`/admin/notifications/${notification.id}/read`);
    notifications.value[index].read_at = new Date().toISOString(); // Mark as read locally
    closeDropdown(); // Close the notification dropdown when a notification is clicked
  } catch (error) {
    console.error('Error marking notification as read:', error);
  }
};

// Function to toggle notification dropdown visibility
const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

// Function to close the dropdown
const closeDropdown = () => {
  isDropdownOpen.value = false;
};

// Function to clear notifications
const clearNotifications = async () => {
  try {
    await axios.post('/admin/notifications/clear'); // Replace with your actual API route to clear notifications
    notifications.value = []; // Clear the notifications locally after clearing them on the server
  } catch (error) {
    console.error('Error clearing notifications:', error);
  }
};
</script>

<template>
  <div class="flex-1 p-6 bg-blue-100 dark:bg-gray-900">
    <!-- Top Navbar (Admin) -->
    <nav class="border-b border-blue-300 bg-blue-200 dark:border-blue-300 dark:bg-blue-300">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between items-center"> <!-- Ensure consistent height with items centered -->
          <!-- Logo and Navigation Links -->
          <div class="flex items-center space-x-8"> <!-- Maintain space between logo and links -->
            <div class="flex shrink-0 items-center">
              <Link :href="route('admin.home')">
                <img src="/images/counselcarelogo.png" alt="Counsel & Care Logo" class="h-9 w-auto">
              </Link>
            </div>

            <!-- Admin Navigation Links -->
            <div class="hidden sm:flex space-x-8">
              <NavLink
                :href="route('admin.home')"
                :active="route().current('admin.home')"
                class="px-4 py-2 rounded-md hover:bg-blue-300 transition-all duration-200 focus:outline-none"
              >
                Dashboard
              </NavLink>
              <NavLink
                :href="route('admin.users')"
                :active="route().current('admin.users')"
                class="px-4 py-2 rounded-md hover:bg-blue-300 transition-all duration-200 focus:outline-none"
              >
                Users
              </NavLink>
              <NavLink
                :href="route('admin.assessments')"
                :active="route().current('admin.assessments')"
                class="px-4 py-2 rounded-md hover:bg-blue-300 transition-all duration-200 focus:outline-none"
              >
                Assessments
              </NavLink>
              <NavLink
                :href="route('admin.appointments')"
                :active="route().current('admin.appointments')"
                class="px-4 py-2 rounded-md hover:bg-blue-300 transition-all duration-200 focus:outline-none"
              >
                Appointments
              </NavLink>
            </div>
          </div>

          <!-- Notifications and User Profile Dropdown -->
          <div class="hidden sm:flex sm:items-center">
            <!-- Notifications Bell -->
            <div class="relative ms-3">
              <button @click="toggleDropdown" class="p-2 text-white bg-blue-500 rounded-full">
                <span class="sr-only">Open notifications</span>
                <!-- Heroicons Bell Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405C18.526 15.658 18 14.457 18 13V9a6 6 0 10-12 0v4c0 1.457-.526 2.658-1.595 2.595L4 17h5m6 0V9a3 3 0 00-6 0v8m6 4h-6" />
                </svg>
              </button>

              <!-- Notifications Dropdown -->
              <div v-if="isDropdownOpen" class="absolute right-0 mt-2 w-72 bg-white shadow-lg rounded-lg">
                <div class="py-2 px-4 text-lg font-semibold text-gray-900">
                  Notifications
                </div>
                <ul class="divide-y divide-gray-200">
                  <li v-for="(notification, index) in notifications" :key="notification.id" class="px-4 py-2 text-sm text-gray-700 cursor-pointer" @click="markAsReadAndEnlarge(index)">
                    <span>{{ notification.message }}</span> <!-- Display message here -->
                    <span v-if="notification.read_at" class="text-xs text-gray-500">Read</span> <!-- Optionally show 'Read' status -->
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
/* Prevents layout shift */
.sm\:space-x-8 {
  display: flex;
  align-items: center;
  gap: 1rem; /* Add a consistent gap between the nav items */
}

nav {
  height: 64px; /* Fix height of the navbar */
}

nav .flex {
  align-items: center;
}

nav a {
  display: block;
  padding: 8px 16px; /* Ensure consistent padding around links */
}
</style>
