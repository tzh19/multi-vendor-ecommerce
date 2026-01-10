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
    class="group rounded-xl border overflow-hidden transition border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/10 flex flex-col"
  >
    <!-- Image -->
    <img
      :src="product.image_url || dummyImage"
      alt="Product"
      class="h-44 w-full object-cover transition-transform duration-300 group-hover:scale-105"
    />

    <!-- Content -->
    <div class="p-4 flex flex-col justify-between flex-1">
      <div>
        <!-- Product Name -->
        <h2
          class="text-lg font-semibold text-gray-900 dark:text-gray-100 line-clamp-2 mb-1"
        >
          {{ product.name }}
        </h2>

        <!-- Vendor Name -->
        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-1">
          {{ product.vendor?.name || "Unknown vendor" }}
        </p>

        <!-- Price -->
        <p class="text-xl font-bold text-gray-900 dark:text-gray-100 mt-2">
          {{ money(product.price) }}
        </p>
      </div>

      <!-- Add to Cart Button -->
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
