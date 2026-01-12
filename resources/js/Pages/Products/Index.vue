<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import ProductCard from "@/Components/ProductCard.vue";
import { money } from "@/utils/money";
import { route } from "ziggy-js";

const props = defineProps<{
  products: any;
  filters: {
    search?: string;
  };
}>();

const searchTerm = ref(props.filters?.search || "");

function search() {
  router.get(
    route("products.index"),
    { search: searchTerm.value },
    {
      preserveState: true,
      replace: true,
    }
  );
}

function addToCart(productId: number) {
  router.post(route("cart.store"), {
    product_id: productId,
  });
}

const dummyImage =
  "https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80";
</script>

<template>
  <Head title="Products" />

  <AppLayout :breadcrumbs="[{ title: 'Products', href: route('products.index') }]">
    <div class="px-4 sm:px-6 lg:px-8 py-4">
      <!-- Search Bar -->
      <div class="mb-6">
        <input
          v-model="searchTerm"
          @input="search"
          type="text"
          placeholder="Search products..."
          class="w-full rounded-lg px-4 py-2 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <!-- No Products -->
      <div
        v-if="products.data.length === 0"
        class="text-center text-gray-500 dark:text-gray-400 py-12"
      >
        No products found.
      </div>

      <!-- Product Grid -->
      <div
        v-else
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
      >
        <ProductCard
          v-for="product in products.data"
          :key="product.id"
          :product="product"
        />
      </div>

      <!-- Pagination -->
      <div v-if="products.data.length" class="mt-8 flex flex-wrap gap-2">
        <button
          v-if="products.prev_page_url"
          @click="$inertia.visit(products.prev_page_url)"
          class="px-3 py-1 rounded border border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700"
        >
          Previous
        </button>

        <button
          v-for="page in products.last_page"
          :key="page"
          @click="$inertia.visit(`/products?page=${page}`)"
          :class="[
            'px-3 py-1 rounded border transition',
            page === products.current_page
              ? 'bg-blue-600 border-blue-600 text-white'
              : 'border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700',
          ]"
        >
          {{ page }}
        </button>

        <button
          v-if="products.next_page_url"
          @click="$inertia.visit(products.next_page_url)"
          class="px-3 py-1 rounded border border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700"
        >
          Next
        </button>
      </div>
    </div>
  </AppLayout>
</template>
