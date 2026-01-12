<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';
import { reactive,computed,ref } from 'vue';

const props = defineProps({
  order: Object,
});

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: route('dashboard'),
  },
  {
    title: 'Orders',
    href: route('admin.orders.index'),
  },
  {
    title: `Order #${props.order.id}`,
    href: route('admin.orders.show', props.order.id),
  },
];

const selectedStatus = ref('');

const allowedTransitions: Record<string, string[]> = {
  processing: ['confirmed', 'cancelled'],
  confirmed: ['shipped'],
  shipped: ['completed'],
  completed: [],
  cancelled: [],
};

const order = reactive({ ...props.order }); // make a reactive copy

const availableStatuses = computed(() => {
  return allowedTransitions[order.status] ?? [];
});

function updateStatus(status: string) {
  if (!status) return;

  if (status === 'cancelled' && !confirm('Are you sure you want to cancel this order?')) return;

  router.patch(
    route('admin.orders.update-status', order.id),
    { status },
    {
      preserveScroll: true,
      onSuccess: () => {
        order.status = status; // update badge
        selectedStatus.value = ''; // reset the select
      },
    }
  );
}
</script>

<template>
  <Head title="Order Details" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-6 p-4">
      <!-- Header -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold">Order #{{ order.id }}</h1>
          <p class="text-gray-500 dark:text-gray-400 text-sm">
            {{ order.created_at }}
          </p>
        </div>

        <div class="flex items-center gap-3">
          <!-- Status Badge -->
          <span
            class="px-3 py-1 rounded-full text-xs font-semibold capitalize"
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

          <!-- Change Status -->
          <select
            v-model="selectedStatus"
            class="h-9 rounded-md border px-2 text-sm bg-background capitalize"
            :disabled="availableStatuses.length === 0"
            @change="updateStatus(selectedStatus)"
          >
            <option value="" disabled>Change status</option>
            <option v-for="status in availableStatuses" :key="status" :value="status">
              {{ status }}
            </option>
          </select>
        </div>
      </div>

      <!-- Customer Info Card -->
      <div class="rounded-xl border bg-card text-card-foreground shadow-sm p-6">
        <h2 class="text-lg font-semibold mb-3">Customer Details</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
          <p><span class="text-gray-400">Name:</span> {{ order.customer }}</p>
          <p><span class="text-gray-400">Email:</span> {{ order.email }}</p>
          <p><span class="text-gray-400">Total Items:</span> {{ order.total_items }}</p>
          <p><span class="text-gray-400">Total Amount:</span> ${{ order.total }}</p>
        </div>
      </div>

      <!-- Items Table -->
      <div class="rounded-xl border bg-card text-card-foreground shadow-sm p-6">
        <h2 class="text-lg font-semibold mb-4">Order Items</h2>

        <table class="w-full text-sm">
          <thead class="border-b text-gray-500 dark:text-gray-400">
            <tr>
              <th class="pb-3 text-left">Product</th>
              <th class="pb-3 w-24 text-left">Price</th>
              <th class="pb-3 w-20 text-left">Qty</th>
              <th class="pb-3 w-32 text-left">Subtotal</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="item in order.items"
              :key="item.id"
              class="border-b dark:border-gray-700"
            >
              <td class="py-3">{{ item.product_name }}</td>
              <td class="py-3">${{ item.price }}</td>
              <td class="py-3">{{ item.quantity }}</td>
              <td class="py-3">${{ item.subtotal }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>
