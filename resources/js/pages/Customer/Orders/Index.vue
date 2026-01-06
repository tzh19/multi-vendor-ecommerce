<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, Head } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { money } from "@/utils/money";

const props = defineProps<{
  orders: Array<any>;
}>();
</script>

<template>
  <Head title="My Orders" />

  <AppLayout
    :breadcrumbs="[
      { title: 'Home', href: route('customer.home.index') },
      { title: 'My Orders', href: route('customer.orders.index') },
    ]"
  >
    <!-- Page padding wrapper -->
    <div class="px-4 sm:px-6 lg:px-8 py-6 text-gray-200">
      <h1 class="text-2xl font-bold mb-6">My Orders</h1>

      <div v-if="!props.orders.length" class="text-gray-400">You have no orders yet.</div>

      <div v-else class="space-y-4">
        <div
          v-for="order in props.orders"
          :key="order.id"
          class="p-4 rounded-lg bg-gray-800/60 border border-gray-700"
        >
          <div class="flex justify-between items-center">
            <div>
              <p class="font-semibold">Order #{{ order.id }}</p>

              <p class="text-sm text-gray-400">Total: {{ money(order.total_price) }}</p>

              <p class="text-sm text-gray-400">
                Status:
                <span class="capitalize">
                  {{ order.status }}
                </span>
              </p>
            </div>

            <Link
              :href="route('customer.orders.show', order.id)"
              class="text-blue-400 hover:underline"
            >
              View
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
