<script setup>
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { money } from "@/utils/money.js";
import { route } from "ziggy-js";

const props = defineProps({
  cartItems: Array, // passed from backend
});

const form = useForm({
  payment_method: "cod",
  address: "",
});
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
    <div class="max-w-xl mx-auto p-6 bg-gray-800 text-gray-100 rounded shadow-md">
      <h1 class="text-xl font-bold mb-4">Checkout</h1>

      <!-- Cart Summary -->
      <div class="mb-6">
        <h2 class="font-semibold mb-2">Your Cart</h2>
        <ul>
          <li v-for="item in cartItems" :key="item.id" class="flex justify-between">
            <span>{{ item.product.name }} x {{ item.quantity }}</span>
            <span>{{ money(item.product.price * item.quantity) }}</span>
          </li>
        </ul>
        <div class="mt-2 font-bold">
          Subtotal:
          {{ money(cartItems.reduce((sum, i) => sum + i.product.price * i.quantity, 0)) }}
        </div>
      </div>

      <!-- Checkout Form -->
      <form @submit.prevent="form.post('/checkout')">
        <div class="mb-4">
          <label class="font-semibold block mb-1">Shipping Address</label>
          <textarea
            v-model="form.address"
            class="w-full border border-gray-700 bg-gray-900 text-gray-100 p-2 rounded"
          ></textarea>
          <div
            v-if="form.errors.address"
            class="w-full border border-gray-700 bg-gray-900 text-gray-100 p-2 rounded"
          >
            {{ form.errors.address }}
          </div>
        </div>

        <div class="mb-4">
          <label class="font-semibold block mb-1">Payment Method</label>
          <select
            v-model="form.payment_method"
            class="w-full border border-gray-700 bg-gray-900 text-gray-100 p-2 rounded"
          >
            <option value="cod">Cash on Delivery</option>
            <option value="card">Card</option>
          </select>
        </div>

        <button
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
          :disabled="form.processing"
        >
          Place Order
        </button>
      </form>
    </div>
  </CustomerLayout>
</template>
