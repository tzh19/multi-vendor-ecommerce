<script setup>
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import { money } from "@/utils/money.js";
import { route } from "ziggy-js";

const props = defineProps({
  order: Object,
});
</script>

<template>
  <CustomerLayout
    :breadcrumbs="[
      { title: 'Home', href: route('customer.home.index') },
      {
        title: `Order #${props.order.id}`,
        href: route('customer.orders.show', order.id),
      },
    ]"
  >
    <div class="p-6 text-gray-200">
      <h1 class="text-2xl font-bold mb-6">Order #{{ props.order.id }}</h1>

      <div class="p-4 bg-gray-800/60 border border-gray-700 rounded-lg mb-6">
        <p>Total: {{ money(props.order.total_price) }}</p>
        <p>
          Status: <span class="capitalize">{{ props.order.status }}</span>
        </p>
      </div>

      <div class="space-y-4">
        <h2 class="font-semibold mb-3">Items</h2>

        <div
          v-for="item in props.order.items"
          :key="item.id"
          class="p-4 bg-gray-800/40 rounded-lg border border-gray-700"
        >
          <div class="flex gap-4 items-center">
            <img :src="item.product.image" class="w-20 h-20 rounded object-cover" />

            <div>
              <p class="font-semibold">{{ item.product.name }}</p>
              <p class="text-gray-400">Qty: {{ item.quantity }}</p>
              <p class="text-gray-400">Price: {{ money(item.price) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>
