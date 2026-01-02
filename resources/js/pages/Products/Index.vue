<script setup>
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import { money } from "@/utils/money.js";
import { route } from "ziggy-js";

const props = defineProps({
  products: Object,
  filters: Object,
});

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

function addToCart(productId) {
  router.post(route("cart.store"), { product_id: productId });
}

const dummyImage =
  "https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80";
</script>

<template>
  <CustomerLayout
    :breadcrumbs="[
      { title: 'Home', href: route('customer.home.index') },
      { title: 'Products', href: route('products.index') },
    ]"
  >
    <h1 class="text-2xl font-bold mb-6 text-gray-100">Products</h1>

    <!-- Search Bar -->
    <div class="mb-6">
      <input
        v-model="searchTerm"
        @input="search"
        type="text"
        placeholder="Search products..."
        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
    </div>

    <!-- No Products -->
    <div v-if="products.data.length === 0" class="text-center text-gray-400 py-12">
      No products found.
    </div>

    <!-- Product Grid -->
    <div
      v-else
      class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
    >
      <div
        v-for="product in products.data"
        :key="product.id"
        class="group rounded-xl border border-gray-700 bg-gray-800 overflow-hidden transition hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/10"
      >
        <!-- Image -->
        <div class="overflow-hidden">
          <img
            :src="product.image_url || dummyImage"
            alt="Product"
            class="h-44 w-full object-cover transition-transform duration-300 group-hover:scale-105"
          />
        </div>

        <!-- Content -->
        <div class="p-4">
          <h2 class="text-lg font-semibold text-gray-100">
            {{ product.name }}
          </h2>

          <p class="text-sm text-gray-400">
            {{ product.vendor?.name || "Unknown vendor" }}
          </p>

          <p class="text-xl font-bold text-gray-100 mt-2">
            {{ money(product.price) }}
          </p>

          <button
            @click="addToCart(product.id)"
            class="mt-4 w-full rounded-lg bg-blue-600 text-white py-2 transition hover:bg-blue-700"
          >
            Add to Cart
          </button>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="products.data.length > 0" class="mt-8 flex flex-wrap gap-2">
      <button
        v-if="products.prev_page_url"
        @click="$inertia.visit(products.prev_page_url)"
        class="px-3 py-1 rounded border border-gray-700 text-gray-300 hover:bg-gray-700"
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
            : 'border-gray-700 text-gray-300 hover:bg-gray-700',
        ]"
      >
        {{ page }}
      </button>

      <button
        v-if="products.next_page_url"
        @click="$inertia.visit(products.next_page_url)"
        class="px-3 py-1 rounded border border-gray-700 text-gray-300 hover:bg-gray-700"
      >
        Next
      </button>
    </div>
  </CustomerLayout>
</template>
