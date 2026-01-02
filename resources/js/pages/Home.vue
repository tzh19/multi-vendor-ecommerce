<script setup lang="ts">
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import { Head, usePage, Link } from "@inertiajs/vue3";
import type { BreadcrumbItem } from "@/types";
import { route } from "ziggy-js";

const { categories } = usePage().props as Array<{
  id: number;
  name: string;
  description?: string;
  products_count: number;
}>;

const breadcrumbs: BreadcrumbItem[] = [
  { title: "Home", href: route("customer.home.index") },
];
const dummyImage =
  "https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=800&q=80";
</script>

<template>
  <Head title="Home" />

  <CustomerLayout :breadcrumbs="breadcrumbs">
    <h1 class="text-2xl font-bold mb-6 text-gray-100">Shop by Category</h1>

    <div class="rounded-xl border border-gray-700 bg-gray-800 p-6">
      <div
        v-if="categories.length"
        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6"
      >
        <Link
          v-for="category in categories"
          :key="category.id"
          :href="route('categories.show', category.id)"
          class="group border border-gray-700 rounded-lg overflow-hidden hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/10 transition"
        >
          <!-- Dummy Image -->
          <img
            :src="dummyImage"
            alt="Category"
            class="h-32 w-full object-cover group-hover:scale-105 transition"
          />

          <!-- Content -->
          <div class="p-4">
            <h3 class="font-semibold text-lg text-gray-100">
              {{ category.name }}
            </h3>

            <p class="text-sm text-gray-400 mt-1">
              {{ category.description || "Browse products" }}
            </p>

            <p class="text-xs text-gray-500 mt-2">
              {{ category.products_count }} products
            </p>
          </div>
        </Link>
      </div>

      <div v-else class="text-center text-gray-400 py-10">
        No categories available yet.
      </div>
    </div>
  </CustomerLayout>
</template>
