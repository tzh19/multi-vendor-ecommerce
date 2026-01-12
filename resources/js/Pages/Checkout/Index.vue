<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { money } from "@/utils/money.js";
import { route } from "ziggy-js";
import { reactive, computed } from "vue";
import { cartCount } from "@/stores/cart"; // reactive cart store

// Props from backend
const props = defineProps<{
  cartItems: Array<{
    id: number;
    quantity: number;
    product: {
      name: string;
      price: number;
    };
  }>;
}>();

// Reactive object to track cart items locally
const localCartItems = reactive([...props.cartItems]);

// Form for checkout
const form = useForm({
  payment_method: "cod",
  address: "",
});

// Computed subtotal
const subtotal = computed(() =>
  localCartItems.reduce((sum, i) => sum + i.product.price * i.quantity, 0)
);

// Remove item function
function removeItem(id: number) {
  const index = localCartItems.findIndex((i) => i.id === id);
  if (index !== -1) {
    cartCount.value -= localCartItems[index].quantity; // update cart badge
    localCartItems.splice(index, 1); // remove locally
  }
}

// Handle checkout submission
function submitCheckout() {
  form.post(route("checkout.store"), {
    data: {
      cartItems: localCartItems,
    },
  });
}
</script>

<template>
  <AppLayout :breadcrumbs="[{ title: 'Home', href: route('customer.home.index') }]">
    <div
      class="mt-6 px-4 sm:px-6 lg:px-8 py-6 max-w-4xl mx-auto rounded shadow-md bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100"
    >
      <h1 class="text-xl font-bold mb-4">Checkout</h1>

      <!-- Cart Summary -->
      <div class="mb-6">
        <h2 class="font-semibold mb-2">Your Cart</h2>

        <ul>
          <li
            v-for="item in localCartItems"
            :key="item.id"
            class="flex justify-between items-center mb-2"
          >
            <span> {{ item.product.name }} x {{ item.quantity }} </span>

            <span>
              {{ money(item.product.price * item.quantity) }}
            </span>

            <button
              @click="removeItem(item.id)"
              class="ml-4 px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm"
            >
              Remove
            </button>
          </li>
        </ul>

        <div class="mt-2 font-bold">Subtotal: {{ money(subtotal) }}</div>
      </div>

      <!-- Checkout Form -->
      <form v-if="localCartItems.length > 0" @submit.prevent="submitCheckout">
        <!-- Address -->
        <div class="mb-4">
          <label class="font-semibold block mb-1">Shipping Address</label>

          <textarea
            v-model="form.address"
            class="w-full p-2 rounded border bg-white text-gray-900 border-gray-300 dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700"
          ></textarea>

          <div
            v-if="form.errors.address"
            class="mt-1 p-2 rounded border bg-white text-red-600 border-red-300 dark:bg-gray-900 dark:text-red-400 dark:border-red-600"
          >
            {{ form.errors.address }}
          </div>
        </div>

        <!-- Payment -->
        <div class="mb-4">
          <label class="font-semibold block mb-1">Payment Method</label>

          <select
            v-model="form.payment_method"
            class="w-full p-2 rounded border bg-white text-gray-900 border-gray-300 dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700"
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

      <!-- Empty Cart -->
      <div v-else class="mt-6 text-center text-gray-600 dark:text-gray-400">
        Your cart is empty 😢

        <div class="mt-2">
          <Link
            :href="route('products.index')"
            class="text-blue-600 dark:text-blue-400 hover:underline"
          >
            Continue shopping →
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
