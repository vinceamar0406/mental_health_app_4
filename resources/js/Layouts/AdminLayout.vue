<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { ref } from 'vue';

// Get current URL to manage active link state
const { url } = usePage();

// Manage mobile menu state
const isMobileMenuOpen = ref(false);
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

            <!-- Admin Navigation Links (Hidden on Mobile) -->
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

          <!-- Hamburger Icon (Visible only on mobile) -->
          <div class="sm:hidden flex items-center">
            <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="text-gray-800 dark:text-white">
              <span class="sr-only">Open menu</span>
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
            </button>
          </div>

          <!-- User Profile Dropdown (Hidden on Mobile) -->
          <div class="hidden sm:flex sm:items-center">
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

    <!-- Mobile Menu (Visible when isMobileMenuOpen is true) -->
    <div v-show="isMobileMenuOpen" class="sm:hidden">
      <div class="bg-blue-200 dark:bg-blue-300 space-y-4 p-4">
        <NavLink
          :href="route('admin.home')"
          :active="route().current('admin.home')"
          class="block px-4 py-2 rounded-md hover:bg-blue-300 transition-all duration-200"
        >
          Dashboard
        </NavLink>
        <NavLink
          :href="route('admin.users')"
          :active="route().current('admin.users')"
          class="block px-4 py-2 rounded-md hover:bg-blue-300 transition-all duration-200"
        >
          Users
        </NavLink>
        <NavLink
          :href="route('admin.assessments')"
          :active="route().current('admin.assessments')"
          class="block px-4 py-2 rounded-md hover:bg-blue-300 transition-all duration-200"
        >
          Assessments
        </NavLink>
        <NavLink
          :href="route('admin.appointments')"
          :active="route().current('admin.appointments')"
          class="block px-4 py-2 rounded-md hover:bg-blue-300 transition-all duration-200"
        >
          Appointments
        </NavLink>
      </div>
    </div>

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

@media (max-width: 640px) {
  .sm\:hidden {
    display: none;
  }
}
</style>
