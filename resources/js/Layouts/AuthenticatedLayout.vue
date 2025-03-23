<script setup>
import { ref, onMounted } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2'; // Import SweetAlert2
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';


// ✅ Reactive state for active link tracking
const activeLink = ref(route().current());

// Reactive state
const showingNavigationDropdown = ref(false);

// Get user role from Inertia props
const userRole = usePage().props.auth.user.role;

// Automatically detect login from another tab
onMounted(() => {
    window.addEventListener('storage', (event) => {
        if (event.key === 'isLoggedIn' && event.newValue === 'true') {
            window.location.reload(); // Reload to reflect login state
        }
    });
});

// ✅ Step 1: Confirm before screening
const confirmScreeningNavigation = (event) => {
    event.preventDefault(); // Prevent direct navigation

    Swal.fire({
        title: "Mental Health Screening",
        text: "Are you sure you want to proceed with the mental health screening?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, Proceed",
        cancelButtonText: "Cancel",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            showPrivacyPolicy(); // Proceed to Step 2
        }
    });
};

// ✅ Step 2: Show Data Privacy Notice
const showPrivacyPolicy = () => {
    Swal.fire({
        title: "Data Privacy Notice",
        html: `
            <p><strong>Republic Act No. 10173 - Data Privacy Act of 2012</strong></p>
            <p>This website collects, processes, and stores personal data in accordance with the Data Privacy Act of 2012 (RA 10173). By proceeding, you acknowledge and agree that:</p>
            <ul style="text-align:left;">
                <li>✅ Your responses will be collected solely for providing insights into your mental health.</li>
                <li>✅ Your data will be kept confidential and will not be shared without consent.</li>
                <li>✅ This screening is for informational purposes only and is not a medical diagnosis.</li>
                <li>✅ You have the right to access, correct, or request deletion of your personal data.</li>
            </ul>
            <p>For more details, visit the <a href="https://www.privacy.gov.ph" target="_blank" style="color:blue;">National Privacy Commission (NPC)</a>.</p>
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "I Accept",
        cancelButtonText: "Cancel",
        width: "600px",
    }).then((result) => {
        if (result.isConfirmed) {
            showLoadingAnimation(); // Proceed to Step 3
        }
    });
};

// ✅ Step 3: Show loading animation before navigating
const showLoadingAnimation = () => {
    Swal.fire({
        title: "Preparing Your Assessment...",
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
            setTimeout(() => {
                Swal.close(); // ✅ Close loading animation
                router.visit('/screening'); // ✅ Navigate to the screening page
            }, 1000);
        }
    });
};

</script>

<template>
  <div>
    <div class="min-h-screen bg-blue-100 dark:bg-gray-900">
      <nav class="border-b border-blue-100 bg-blue-100 dark:border-gray-700 dark:bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex h-16 justify-between">
            <div class="flex">
              <!-- Logo -->
              <div class="flex shrink-0 items-center">
                <Link :href="route('assessment.history')">
                    <img src="/images/counselcarelogo.png" alt="Counsel & Care Logo" class="h-9 w-auto">
                </Link>
              </div>

              <!-- ✅ Updated Navigation Links -->
              <div v-if="!route().current('admin.home')" class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <NavLink :href="route('assessment.history')" :active="route().current('assessment.history')">
                  Assessment History
                </NavLink>
                <NavLink href="#" @click="confirmScreeningNavigation":class="{ 'text-blue-600 font-bold border-b-2 border-blue-600': activeLink === 'screening' }">
                  Mental Health Screening
                </NavLink>
                <NavLink :href="route('assessment_hub')" :active="route().current('assessment_hub')">
                  Assessment Hub
                </NavLink>
              </div>
            </div>

            <div class="hidden sm:ms-6 sm:flex sm:items-center">
              <div class="relative ms-3">
                <Dropdown align="right" width="48">
                  <template #trigger>
                    <button type="button" class="flex items-center rounded-full bg-gray-800 text-sm focus:ring-2 focus:ring-white">
                      <span class="sr-only">Open user menu</span>
                      <img class="size-8 rounded-full"
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

      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
