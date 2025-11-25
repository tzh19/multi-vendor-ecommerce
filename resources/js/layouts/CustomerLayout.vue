<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
const page = usePage();
</script>

<template>
  <div class="flex h-screen bg-gray-100 dark:bg-gray-900">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-gray-200 p-6 space-y-6 shadow-xl">
      <h2 class="text-2xl font-bold mb-6 text-white">Shop</h2>

      <nav class="space-y-3">
        <Link
          :href="route('products.index')"
          class="block px-3 py-2 rounded hover:bg-gray-700 transition"
        >
          Products
        </Link>

        <Link
          :href="route('categories.index')"
          class="block px-3 py-2 rounded hover:bg-gray-700 transition"
        >
          Categories
        </Link>

        <Link class="block px-3 py-2 rounded hover:bg-gray-700 transition">
          My Orders
        </Link>
      </nav>
    </aside>

    <!-- Main Section -->
    <div class="flex-1 flex flex-col">
      <!-- Top Bar -->
      <header
        class="flex items-center justify-between bg-gray-850 px-6 py-4 shadow text-gray-200"
        style="background: #2d2d2d"
      >
        <h1 class="text-xl font-semibold"></h1>

        <div class="flex items-center gap-6">
          <!-- Cart -->
          <Link class="relative text-white text-xl">
            🛒
            <span
              v-if="page.props.cartCount > 0"
              class="absolute -top-2 -right-2 bg-red-600 text-white text-xs px-2 rounded-full"
            >
              {{ page.props.cartCount }}
            </span>
          </Link>

          <!-- Logout -->
          <form method="POST" :action="route('logout')">
            <button class="text-red-400 hover:text-red-300">Logout</button>
          </form>
        </div>
      </header>

      <!-- Content -->
      <main class="p-6 overflow-y-auto bg-gray-100 dark:bg-gray-900 text-gray-200">
        <slot />
      </main>
    </div>
  </div>
</template>
