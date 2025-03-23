<template>
    <div class="container mx-auto px-6 py-10 bg-blue-100 min-h-screen">
      <!-- Header Section with Back Button -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 border-b-4 border-blue-500 pb-1">
          Articles
        </h1>
        <button
          @click="goBack"
          class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg shadow-md hover:bg-blue-700 transition-all"
        >
          ← Back to Home
        </button>
      </div>

      <!-- Loading State -->
      <p v-if="loading" class="text-gray-600 text-lg">Loading articles...</p>

      <!-- Empty State -->
      <p v-else-if="articles.length === 0" class="text-gray-600 text-lg">
        No articles available.
      </p>

      <!-- Articles Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="article in articles"
          :key="article.id"
          class="p-6 bg-white border border-gray-300 rounded-2xl shadow-md hover:shadow-lg transition-all hover:scale-[1.02]"
        >
          <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ article.title }}</h2>
          <p class="text-gray-800 leading-relaxed mb-4 text-lg truncate-text">
            {{ article.content }}
          </p>
          <a
            :href="article.link"
            target="_blank"
            rel="noopener noreferrer"
            class="text-blue-600 font-medium hover:text-blue-800 hover:underline transition-all text-lg flex items-center"
          >
            Read more <span class="ml-2">🔗</span>
          </a>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from "vue";
  import { Inertia } from "@inertiajs/inertia"; // Import Inertia

  const loading = ref(false); // Simulating loading state

  defineProps({
    articles: {
      type: Array,
      default: () => [],
    },
  });

  // Navigation function for Back to Home button
  const goBack = () => {
  // Check if we have a previous page in history; if not, go to the homepage
  if (document.referrer) {
    Inertia.visit(document.referrer, { method: "get" });
  } else {
    // Explicitly navigate to the homepage if no referrer
    Inertia.visit("/");
  }
};
  </script>

  <style scoped>
  /* Background and Text */
  .bg-light {
    background-color: #f8f9fa;
  }
  .text-dark {
    color: #2d3748;
  }
  .border-gray-300 {
    border-color: #d1d5db;
  }

  /* ✅ Simple Text Truncation Without Errors */
  .truncate-text {
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
    white-space: nowrap;
    max-width: 100%;
  }
  </style>
