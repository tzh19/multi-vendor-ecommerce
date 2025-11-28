<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { type BreadcrumbItem } from '@/types';
import { money } from "@/utils/money.js";

const props = defineProps({
  orders: {
    type: Object,
    required: true,
  },
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
];
</script>

<template>
  <Head title="Vendor Orders" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-bold">Orders</h2>
            </div>

            <!-- Table Wrapper Card -->
            <div class="rounded-xl border bg-card text-card-foreground shadow-sm p-6">
              <table class="w-full text-sm">
                <thead>
                  <tr class="border-b">
                    <th class="pb-3 w-16 text-left font-semibold">ID</th>
                    <th class="pb-3 w-32 text-left font-semibold">Customer</th>
                    <th class="pb-3 w-1/4 text-left font-semibold">Total Items</th>
                    <th class="pb-3 w-1/4 text-left font-semibold">Total</th>
                    <th class="pb-3 w-32 text-left font-semibold">Status</th>
                    <th class="pb-3 w-32 text-left font-semibold">Created</th>
                    <th class="pb-3 text-right w-24 font-semibold">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="order in orders.data" :key="order.id" class="border-b">
                    <td class="py-3">{{ order.id }}</td>
                    <td class="py-3">{{ order.customer }}</td>
                    <td class="py-3">{{ order.total_items }}</td>
                    <td class="py-3">{{ money(order.subtotal ?? 0) }}</td>
                    <td class="py-3">
                      <span
                        class="px-2 py-1 rounded-full text-xs font-semibold"
                        :class="{
                          'bg-blue-900 text-white': order.status === 'pending',
                          'bg-blue-700 text-white': order.status === 'processing',
                          'bg-green-900 text-white': order.status === 'completed',
                          'bg-red-900 text-white': order.status === 'canceled',
                        }"
                      >
                        {{ order.status }}
                      </span>
                    </td>
                    <td class="py-3">{{ order.created_at }}</td>
                    <td class="py-3 text-right">
                      <div class="flex justify-end gap-2">
                        <Link
                          :href="route('vendor.orders.show', order.id)"
                          class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                        >
                          Show
                        </Link>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- Pagination -->
            <div class="mt-4 flex gap-2">
              <button
                v-if="orders.prev_page_url"
                @click="$inertia.visit(orders.prev_page_url)"
                class="px-3 py-1 border rounded"
              >
                Previous
              </button>
              <!-- Page Numbers -->
              <button
                v-for="page in orders.last_page"
                :key="page"
                @click="$inertia.visit(`/orders?page=${page}`)"
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
        </div>
      </div>
    </div>
    <ConfirmModal
      :show="showModal"
      message="Are you sure you want to delete this order?"
      @confirm="handleConfirm"
      @cancel="handleCancel"
    />
  </AppLayout>
</template>
