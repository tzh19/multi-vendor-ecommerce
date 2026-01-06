<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import ConfirmModal from "@/Components/ConfirmModal.vue";
import { reactive } from "vue";
import { money } from "@/utils/money.js";
import { route } from "ziggy-js";
import { cartCount } from "@/stores/cart"; // reactive cart store

const props = defineProps<{
  cartItems: Array<{
    id: number;
    quantity: number;
    product: {
      name: string;
      price: number;
      image_url: string;
    };
  }>;
}>();

const showModal = reactive({}); // track modal for each item

function confirmDelete(id: number) {
  showModal[id] = true;
}

function handleConfirm(id: number) {
  router.delete(route("cart.destroy", id), {
    onSuccess: () => {
      const item = props.cartItems.find((i) => i.id === id);
      if (item) cartCount.value -= item.quantity;
    },
  });
  showModal[id] = false;
}

function handleCancel(id: number) {
  showModal[id] = false;
}
</script>

<template>
  <AppLayout :breadcrumbs="[{ title: 'Your Cart', href: route('cart.index') }]">
    <div class="px-4 sm:px-6 lg:px-8 py-6">
      <h1 class="text-2xl font-bold mb-6 text-gray-100">Your Cart</h1>

      <!-- Empty Cart -->
      <div v-if="!props.cartItems.length" class="text-gray-400">
        Your cart is empty 😢
      </div>

      <!-- Cart Items -->
      <div v-else class="space-y-4">
        <div
          v-for="item in props.cartItems"
          :key="item.id"
          class="flex items-center gap-4 border p-4 rounded bg-gray-800/50"
        >
          <img :src="item.product.image_url" class="w-20 h-20 object-cover rounded" />

          <div class="flex-1">
            <h2 class="font-semibold text-lg text-gray-100">{{ item.product.name }}</h2>
            <p class="text-gray-400">Quantity: {{ item.quantity }}</p>
            <p class="font-bold text-gray-100">{{ money(item.product.price) }}</p>
          </div>

          <button
            @click="confirmDelete(item.id)"
            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
          >
            Remove
          </button>

          <ConfirmModal
            :show="showModal[item.id]"
            message="Are you sure you want to remove this cart item?"
            @confirm="() => handleConfirm(item.id)"
            @cancel="() => handleCancel(item.id)"
          />
        </div>
      </div>

      <!-- Checkout Button -->
      <div v-if="props.cartItems.length" class="mt-6">
        <Link
          href="/checkout"
          class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
        >
          Proceed to Checkout
        </Link>
      </div>
    </div>
  </AppLayout>
</template>
