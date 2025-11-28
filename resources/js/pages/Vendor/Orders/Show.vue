<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { route } from 'ziggy-js';

const props = defineProps({
  order: Object,
});

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Vendor Dashboard',
    href: route('vendor.dashboard'),
  },
  {
    title: 'Orders',
    href: route('vendor.orders.index'),
  },
  {
    title: `Order #${props.order.id}`,
    href: route('vendor.orders.show', props.order.id),
  },
];
</script>

<template>
  <Head title="Order Details" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-6 p-4">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Order #{{ order.id }}</h1>
        <p class="text-gray-500 dark:text-gray-400">{{ order.created_at }}</p>
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
