<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";

const page = usePage();

const notifications = computed(() => page.props.notifications);
</script>

<template>
  <Head title="Notifications" />

  <AppLayout
    :breadcrumbs="[{ title: 'Notifications', href: route('notification.index') }]"
  >
    <div class="px-4 sm:px-6 lg:px-8 py-4 max-w-3xl mx-auto">
      <h1 class="text-2xl font-semibold mb-6">Notifications</h1>

      <!-- Empty State -->
      <div
        v-if="notifications.data.length === 0"
        class="text-center text-gray-500 dark:text-gray-400 py-12"
      >
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
              ? 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700'
              : 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-700'
          "
        >
          <div class="flex justify-between items-start gap-4">
            <div>
              <p class="font-medium text-gray-900 dark:text-gray-100">
                {{ notification.data.title ?? "Notification" }}
              </p>

              <p class="text-sm text-gray-600 dark:text-gray-300">
                {{ notification.data.message }}
              </p>

              <p class="text-xs text-gray-400 mt-2">
                {{ notification.created_at }}
              </p>
            </div>

            <Link
              v-if="!notification.read_at"
              :href="route('notification.seen', { notification: notification.id })"
              method="put"
              as="button"
              class="text-sm text-blue-600 hover:underline"
            >
              Mark as read
            </Link>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div
        v-if="notifications.data.length"
        class="mt-8 flex flex-wrap gap-2 justify-center"
      >
        <Link
          v-for="link in notifications.links"
          :key="link.label"
          :href="link.url || ''"
          v-html="link.label"
          class="px-3 py-1 rounded border text-sm"
          :class="[
            link.active
              ? 'bg-blue-600 border-blue-600 text-white'
              : 'border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700',
          ]"
        />
      </div>
    </div>
  </AppLayout>
</template>
