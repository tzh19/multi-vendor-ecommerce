<script setup>
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import { Link } from "@inertiajs/vue3";
import { money } from "@/utils/money.js";
import { route } from "ziggy-js";

const props = defineProps({
  order: Object,
});

const subtotal = Number(props.order.subtotal);
const shipping = Number(props.order.shipping_fee);
const total = Number(props.order.total_price);

// Calculate total price from items if needed
const items = props.order.items;
</script>

<template>
  <CustomerLayout
    :breadcrumbs="[
      { title: 'Home', href: route('customer.home.index') },
      {
        title: 'Checkout',
        href: route('checkout.index'),
      },
    ]"
  >
    <div class="max-w-2xl mx-auto p-6 bg-gray-900 text-gray-100 rounded shadow-md">
      <!-- Success Message -->
      <h1 class="text-2xl font-bold mb-4 text-green-600">Order Placed Successfully!</h1>
      <p class="mb-4">
        Thank you for your order. Your order number is
        <span class="font-semibold">{{ props.order.order_number }}</span
        >.
      </p>

      <!-- Order Details -->
      <div class="mb-6">
        <h2 class="font-semibold text-lg mb-2">Order Summary</h2>
        <ul class="space-y-2">
          <li
            v-for="item in items"
            :key="item.id"
            class="border-b border-gray-700 p-2 flex items-center gap-4"
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

      <!-- Products List / Continue Shopping -->
      <div class="mt-6">
        <h2 class="font-semibold text-lg mb-2">Products in this Order</h2>
        <ul class="space-y-2">
          <li
            v-for="item in items"
            :key="item.id"
            class="border p-2 rounded flex items-center gap-4"
          >
            <img :src="item.product.image_url" class="w-16 h-16 object-cover rounded" />
            <div>
              <h3 class="font-semibold">{{ item.product.name }}</h3>
              <p class="text-gray-600">Quantity: {{ item.quantity }}</p>
              <p class="font-bold">{{ money(item.price * item.quantity) }}</p>
            </div>
          </li>
        </ul>
      </div>

      <div class="mt-6">
        <Link
          href="/products"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Continue Shopping
        </Link>
      </div>
    </div>
  </CustomerLayout>
</template>
