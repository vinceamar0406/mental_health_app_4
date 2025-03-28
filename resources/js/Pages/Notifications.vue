<template>
    <div class="space-y-6">
      <h1 class="text-3xl font-bold">Notifications</h1>

      <!-- Loading Indicator -->
      <div v-if="isLoading" class="text-gray-500 text-xl">Loading notifications...</div>

      <!-- No Notifications Message -->
      <div v-if="!isLoading && notifications.length === 0" class="text-gray-500 text-xl">
        You have no notifications.
      </div>

      <!-- Notifications List -->
      <ul v-if="!isLoading && notifications.length > 0" class="space-y-4">
        <li v-for="notification in notifications" :key="notification.id" class="flex justify-between items-center p-4 bg-white border border-gray-300 rounded-lg shadow-md hover:bg-gray-50">
          <div>
            <span :class="{ 'text-gray-500': notification.read_at }" class="text-lg">
              {{ notification.data.message }}
            </span>
            <div class="text-sm text-gray-400 mt-1">
              {{ new Date(notification.created_at).toLocaleString() }}
            </div>
          </div>

          <button
            @click="markAsRead(notification.id)"
            :disabled="notification.read_at"
            class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-all duration-200"
          >
            Mark as read
          </button>
        </li>
      </ul>

      <!-- Error Message -->
      <div v-if="error" class="text-red-500 text-lg">
        Error loading notifications. Please try again later.
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import { usePage } from '@inertiajs/vue3';

  const { notifications: initialNotifications } = usePage().props;

  // Ensure notifications is always an array
  const notifications = ref(Array.isArray(initialNotifications) ? initialNotifications : []);
  const isLoading = ref(false);
  const error = ref(null);

  // Fetch notifications function
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

  // Mark a notification as read
  const markAsRead = async (notificationId) => {
    try {
      await axios.post(`/admin/notifications/${notificationId}/read`);
      const notification = notifications.value.find(n => n.id === notificationId);
      if (notification) {
        notification.read_at = true; // Optimistic UI update
      }
    } catch (err) {
      console.error('Error marking notification as read:', err);
    }
  };

  // Initial load of notifications
  onMounted(() => {
    if (notifications.value.length === 0) {
      fetchNotifications(); // If no notifications are passed from the backend, fetch them
    }
  });
  </script>

  <style scoped>
  /* Additional custom styles */
  ul {
    list-style-type: none;
    padding: 0;
  }

  li {
    transition: background-color 0.3s ease;
  }

  li:hover {
    background-color: #f0f4f8;
  }

  button:disabled {
    background-color: #d1d5db; /* Gray background when disabled */
  }
  </style>
