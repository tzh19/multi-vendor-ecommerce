<script setup lang="ts">
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import { router } from "@inertiajs/vue3";
import type { BreadcrumbItemType } from "@/types";
import { money } from "@/utils/money.js";
import { route } from "ziggy-js";

const props = defineProps<{
  category: { id: number; name: string; description?: string };
  products: {
    data: Array<{
      id: number;
      name: string;
      price: number;
      image_url?: string;
    }>;
    current_page: number;
    last_page: number;
    prev_page_url?: string;
    next_page_url?: string;
  };
}>();

// Add to Cart function
function addToCart(productId: number) {
  router.post(route("cart.store"), { product_id: productId, quantity: 1 });
}
</script>

<template>
  <CustomerLayout
    :breadcrumbs="[
      { title: 'Home', href: route('customer.home.index') },
      { title: props.category.name },
    ]"
  >
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-2">{{ props.category.name }}</h2>
        <p class="text-gray-500 mb-6">
          {{ props.category.description ?? "Browse products in this category" }}
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          <div
            v-for="product in props.products.data"
            :key="product.id"
            class="border rounded-lg p-4 hover:shadow flex flex-col"
          >
            <img
              v-if="product.image_url"
              :src="product.image_url"
              class="w-full h-40 object-cover rounded mb-2"
            />
            <div
              v-else
              class="w-full h-40 flex items-center justify-center bg-gray-100 rounded mb-2 text-gray-400"
            >
              No Image
            </div>

            <h3 class="font-semibold text-lg mt-2">{{ product.name }}</h3>
            <p class="text-sm text-gray-500 mt-1">{{ money(product.price) }}</p>

            <button
              @click="addToCart(product.id)"
              class="mt-auto w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 mt-4"
            >
              Add to Cart
            </button>
          </div>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>
