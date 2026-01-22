<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, usePage, Link } from "@inertiajs/vue3";
import type { BreadcrumbItem } from "@/types";
import { route } from "ziggy-js";

const { categories, auth } = usePage().props as {
  categories: Array<{
    id: number;
    name: string;
    description?: string;
    products_count: number;
  }>;
  auth: {
    user: null | {
      id: number;
      name: string;
      email: string;
    };
  };
};

const breadcrumbs: BreadcrumbItem[] = [
  { title: "Home", href: route("customer.home.index") },
];

const dummyImage =
  "https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=800&q=80";
</script>

<template>
  <Head title="Home" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="px-4 sm:px-6 lg:px-8 py-4">
      <!-- Guest Login/Register -->
      <div v-if="!auth.user" class="flex justify-end mb-4 gap-2">
        <Link
          :href="route('login')"
          class="px-4 py-2 rounded-md border border-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
        >
          Login
        </Link>

        <Link
          :href="route('register')"
          class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition"
        >
          Register
        </Link>
      </div>

      <!-- Categories Grid -->
      <div
        class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800"
      >
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
            <img
              :src="dummyImage"
              alt="Category"
              class="h-32 w-full object-cover group-hover:scale-105 transition"
            />
            <div class="p-4">
              <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">
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
    </div>
  </AppLayout>
</template>
