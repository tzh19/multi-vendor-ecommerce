<script setup>
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import ConfirmModal from "@/Components/ConfirmModal.vue";
import { reactive } from "vue";
import { money } from "@/utils/money.js";

const props = defineProps({
  cartItems: Array,
});
const showModal = reactive({}); // empty object

function confirmDelete(id) {
  showModal[id] = true; // works now
}

function handleConfirm(id) {
  router.delete(route("cart.destroy", id));
  showModal[id] = false;
}

function handleCancel(id) {
  showModal[id] = false;
}
</script>

<template>
  <CustomerLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Your Cart</h1>

      <div v-if="!props.cartItems.length" class="text-gray-500">
        Your cart is empty 😢
      </div>

      <div v-else class="space-y-4">
        <div
          v-for="item in cartItems"
          :key="item.id"
          class="flex items-center gap-4 border p-4 rounded"
        >
          <img :src="item.product.image_url" class="w-20 h-20 object-cover rounded" />

          <div class="flex-1">
            <h2 class="font-semibold text-lg">{{ item.product.name }}</h2>
            <p class="text-gray-600">Quantity: {{ item.quantity }}</p>
            <p class="font-bold">{{ money(item.product.price) }}</p>
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

      <div v-if="props.cartItems.length" class="mt-6">
        <Link
          href="/checkout"
          class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
        >
          Proceed to Checkout
        </Link>
      </div>
    </div>
  </CustomerLayout>
</template>
