<script setup>
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

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
</script>

<template>
  <CustomerLayout>
    <h1 class="text-2xl font-bold mb-6">Products</h1>

    <!-- Search Bar -->
    <div class="mb-5">
      <input
        v-model="searchTerm"
        @input="search"
        type="text"
        placeholder="Search products..."
        class="w-full border rounded px-3 py-2"
      />
    </div>
    <!-- No Products Found -->
    <div v-if="products.data.length === 0" class="text-center text-gray-500 py-10">
      No products found.
    </div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div
        v-for="product in products.data"
        :key="product.id"
        class="p-4 border rounded shadow bg-grey"
      >
        <img :src="product.image_url" class="w-full h-40 object-cover rounded" />

        <h2 class="text-lg font-semibold mt-3">{{ product.name }}</h2>
        <p class="text-gray-500">{{ product.vendor?.name }}</p>

        <p class="text-xl font-bold mt-2">$ {{ product.price }}</p>

        <button
          @click="addToCart(product.id)"
          class="mt-3 w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
        >
          Add to Cart
        </button>
      </div>
    </div>
    <!-- Pagination -->
    <div v-if="products.data.length > 0" class="mt-4 flex gap-2">
      <button
        v-if="products.prev_page_url"
        @click="$inertia.visit(products.prev_page_url)"
        class="px-3 py-1 border rounded"
      >
        Previous
      </button>
      <!-- Page Numbers -->
      <button
        v-for="page in products.last_page"
        :key="page"
        @click="$inertia.visit(`/products?page=${page}`)"
        :class="[
          'px-3 py-1 border rounded',
          { 'bg-blue-500 text-white': page === products.current_page },
        ]"
      >
        {{ page }}
      </button>
      <button
        v-if="products.next_page_url"
        @click="$inertia.visit(products.next_page_url)"
        class="px-3 py-1 border rounded"
      >
        Next
      </button>
    </div>
  </CustomerLayout>
</template>
