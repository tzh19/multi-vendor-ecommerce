<script setup>
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { money } from "@/utils/money.js";

const props = defineProps({
  orders: Array,
});
</script>

<template>
  <CustomerLayout
    :breadcrumbs="[
      { title: 'Home', href: route('customer.home.index') },
      { title: 'My Orders', href: route('customer.orders.index') },
    ]"
  >
    <div class="p-6 text-gray-200">
      <h1 class="text-2xl font-bold mb-6">My Orders</h1>

      <div v-if="!props.orders.length" class="text-gray-400">You have no orders yet.</div>

      <div class="space-y-4">
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
                <span class="capitalize">{{ order.status }}</span>
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
  </CustomerLayout>
</template>
