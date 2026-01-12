<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <h2 class="text-xl font-bold mb-4">Orders</h2>
      <table class="w-full text-sm border rounded">
        <thead class="bg-gray-100 dark:bg-gray-800">
          <tr>
            <th class="p-2 text-left">ID</th>
            <th class="p-2 text-left">Customer</th>
            <th class="p-2 text-left">Items</th>
            <th class="p-2 text-left">Subtotal</th>
            <th class="p-2 text-left">Status</th>
            <th class="p-2 text-left">Created</th>
            <th class="p-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in orders.data" :key="order.id" class="border-b">
            <td class="p-2">{{ order.id }}</td>
            <td class="p-2">{{ order.customer }}</td>
            <td class="p-2">{{ order.total_items }}</td>
            <td class="p-2">${{ order.subtotal }}</td>
            <td class="p-2">
              <span
                class="px-2 py-1 rounded-full text-xs font-semibold"
                :class="{
                  'bg-yellow-100 text-yellow-700': order.status === 'processing',
                  'bg-blue-100 text-blue-700': order.status === 'confirmed',
                  'bg-purple-100 text-purple-700': order.status === 'shipped',
                  'bg-green-100 text-green-700': order.status === 'completed',
                  'bg-red-100 text-red-700': order.status === 'cancelled',
                }"
              >
                {{ order.status }}
              </span>
            </td>
            <td class="p-2">{{ order.created_at }}</td>
            <td class="p-2 text-right">
              <Link
                :href="route('admin.orders.show', order.id)"
                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
              >
                Show
              </Link>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- Pagination -->
      <div class="mt-4 flex gap-2">
        <button
          v-if="orders.prev_page_url"
          @click="$inertia.visit(orders.prev_page_url)"
          class="px-3 py-1 border rounded"
        >
          Previous
        </button>

        <button
          v-for="page in orders.last_page"
          :key="page"
          @click="$inertia.visit(`/admin/orders?page=${page}`)"
          :class="[
            'px-3 py-1 border rounded',
            { 'bg-blue-500 text-white': page === orders.current_page },
          ]"
        >
          {{ page }}
        </button>

        <button
          v-if="orders.next_page_url"
          @click="$inertia.visit(orders.next_page_url)"
          class="px-3 py-1 border rounded"
        >
          Next
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";

const props = defineProps({
  orders: Object,
});

const breadcrumbs = [
  { title: "Admin Dashboard", href: route("dashboard") },
  { title: "Orders" },
];
</script>
