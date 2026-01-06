<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { money } from "@/utils/money";
import { route } from "ziggy-js";
import { ShoppingCart } from "lucide-vue-next";
import { cartCount } from "@/stores/cart"; // import store

defineProps<{
  product: {
    id: number;
    name: string;
    price: number;
    image_url?: string;
    vendor?: { name: string };
  };
}>();

const dummyImage =
  "https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80";

function addToCart(productId: number) {
  router.post(
    route("cart.store"),
    { product_id: productId },
    {
      onSuccess: () => {
        cartCount.value++; // increment badge count
      },
    }
  );
}
</script>

<template>
  <div
    class="group rounded-xl border border-gray-700 bg-gray-800 overflow-hidden hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/10 transition"
  >
    <!-- Image -->
    <img
      :src="product.image_url || dummyImage"
      alt="Product"
      class="h-44 w-full object-cover transition-transform duration-300 group-hover:scale-105"
    />

    <!-- Content -->
    <div class="p-4">
      <h2 class="text-lg font-semibold text-gray-100">{{ product.name }}</h2>
      <p class="text-sm text-gray-400">{{ product.vendor?.name || "Unknown vendor" }}</p>
      <p class="text-xl font-bold text-gray-100 mt-2">{{ money(product.price) }}</p>

      <button
        @click="addToCart(product.id)"
        class="mt-4 w-full flex items-center justify-center gap-2 rounded-lg bg-blue-600 py-2 text-white hover:bg-blue-700 transition"
      >
        <ShoppingCart class="w-5 h-5" />
        Add to Cart
      </button>
    </div>
  </div>
</template>
