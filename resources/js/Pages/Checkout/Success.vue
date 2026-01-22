<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { money } from "@/utils/money.js";
import { route } from "ziggy-js";

const props = defineProps<{
  order: {
    order_number: string;
    subtotal: number;
    shipping_fee: number;
    total_price: number;
    items: Array<{
      id: number;
      quantity: number;
      price: number;
      product: {
        name: string;
        image_url: string;
      };
    }>;
  };
}>();

const subtotal = Number(props.order.subtotal);
const shipping = Number(props.order.shipping_fee);
const total = Number(props.order.total_price);
const items = props.order.items;
</script>

<template>
  <AppLayout
    :breadcrumbs="[
      { title: 'Home', href: route('customer.home.index') },
      { title: 'Checkout', href: route('checkout.index') },
    ]"
  >
    <div
      class="max-w-2xl mx-auto p-6 rounded shadow-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
    >
      <!-- Success Message -->
      <h1 class="text-2xl font-bold mb-4 text-green-600 dark:text-green-400">
        Order Placed Successfully!
      </h1>
      <p class="mb-4">
        Thank you for your order. Your order number is
        <span class="font-semibold">{{ props.order.order_number }}</span
        >.
      </p>

      <!-- Order Summary -->
      <div class="mb-6">
        <h2 class="font-semibold text-lg mb-2">Order Summary</h2>
        <ul class="space-y-2">
          <li
            v-for="item in items"
            :key="item.id"
            class="border-b border-gray-200 dark:border-gray-700 p-2 flex justify-between"
          >
            <span>{{ item.product.name }} x {{ item.quantity }}</span>
            <span>{{ money(item.price * item.quantity) }}</span>
          </li>
        </ul>

        <div class="flex justify-between mt-2 font-bold">
          <span>Subtotal:</span>
          <span>{{ money(subtotal) }}</span>
        </div>
        <div class="flex justify-between font-bold">
          <span>Shipping:</span>
          <span>{{ money(shipping) }}</span>
        </div>
        <div class="flex justify-between font-bold text-xl mt-1">
          <span>Total:</span>
          <span>{{ money(total) }}</span>
        </div>
      </div>

      <!-- Products List -->
      <div class="mt-6">
        <h2 class="font-semibold text-lg mb-2">Products in this Order</h2>
        <ul class="space-y-2">
          <li
            v-for="item in items"
            :key="item.id"
            class="border p-2 rounded flex items-center gap-4 bg-gray-50 dark:bg-gray-900"
          >
            <img :src="item.product.image_url" class="w-16 h-16 object-cover rounded" />
            <div class="flex-1">
              <h3 class="font-semibold truncate">{{ item.product.name }}</h3>
              <p class="text-gray-600 dark:text-gray-400">
                Quantity: {{ item.quantity }}
              </p>
              <p class="font-bold">{{ money(item.price * item.quantity) }}</p>
            </div>
          </li>
        </ul>
      </div>

      <div class="mt-6 text-center">
        <Link
          href="/products"
          class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
        >
          Continue Shopping
        </Link>
      </div>
    </div>
  </AppLayout>
</template>
