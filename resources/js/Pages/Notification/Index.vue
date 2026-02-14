<script setup lang="ts">
import { usePage, Link } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();

const notifications = computed(() => page.props.notifications);
</script>

<template>
  <div class="max-w-3xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-semibold mb-6">Notifications</h1>

    <!-- Empty State -->
    <div v-if="notifications.data.length === 0" class="text-center text-gray-500 py-10">
      No notifications yet.
    </div>

    <!-- Notification List -->
    <div v-else class="space-y-4">
      <div
        v-for="notification in notifications.data"
        :key="notification.id"
        class="p-4 rounded-xl border transition"
        :class="
          notification.read_at
            ? 'bg-white dark:bg-gray-800'
            : 'bg-blue-50 dark:bg-blue-900/30'
        "
      >
        <div class="flex justify-between items-start">
          <div>
            <p class="font-medium">
              {{ notification.data.title ?? "Notification" }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300">
              {{ notification.data.message }}
            </p>
            <p class="text-xs text-gray-400 mt-2">
              {{ notification.created_at }}
            </p>
          </div>

          <button
            v-if="!notification.read_at"
            class="text-sm text-blue-600 hover:underline"
          >
            Mark as read
          </button>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center gap-2">
      <Link
        v-for="link in notifications.links"
        :key="link.label"
        :href="link.url || ''"
        v-html="link.label"
        class="px-3 py-1 border rounded text-sm"
        :class="{ 'bg-gray-200 dark:bg-gray-700': link.active }"
      />
    </div>
  </div>
</template>
