<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import type { BreadcrumbItemType, User } from "@/types";
import { route } from "ziggy-js";
import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
} from "@/Components/ui/dropdown-menu";
import UserMenu from "@/Components/UserMenuContent.vue";

interface Props {
  breadcrumbs?: BreadcrumbItemType[];
}

defineProps<Props>();

const page = usePage();
const user = page.props.auth.user as User;
</script>

<template>
  <div class="flex h-screen bg-gray-100 dark:bg-gray-900">
    <!-- Sidebar (exact same as AppLayout style) -->
    <aside class="w-64 bg-gray-800 text-gray-200 p-6 space-y-6 shadow-xl flex flex-col">
      <h2 class="text-2xl font-bold mb-6 text-white">Shop</h2>

      <nav class="flex-1 space-y-3">
        <Link
          :href="route('customer.home.index')"
          class="block px-3 py-2 rounded hover:bg-gray-700 transition"
          >Home</Link
        >
        <Link
          :href="route('products.index')"
          class="block px-3 py-2 rounded hover:bg-gray-700 transition"
          >Products</Link
        >

        <Link
          :href="route('customer.orders.index')"
          class="block px-3 py-2 rounded hover:bg-gray-700 transition"
          >My Orders</Link
        >
      </nav>

      <!-- Optional Footer/Logout -->
      <div class="mt-auto">
        <slot name="logout"></slot>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col">
      <!-- Top Bar -->
      <header
        class="flex items-center justify-between bg-gray-850 px-6 py-4 shadow text-gray-200"
        style="background: #2d2d2d"
      >
        <!-- Breadcrumbs -->
        <nav
          v-if="breadcrumbs?.length"
          class="flex items-center gap-2 text-sm text-gray-400"
        >
          <template v-for="(item, index) in breadcrumbs" :key="index">
            <Link
              v-if="item.href"
              :href="item.href"
              class="hover:underline text-gray-300"
            >
              {{ item.title }}
            </Link>

            <span v-else class="text-gray-200 font-medium">
              {{ item.title }}
            </span>

            <!-- Separator -->
            <span v-if="index < breadcrumbs.length - 1" class="text-gray-500"> / </span>
          </template>
        </nav>

        <!-- Cart Icon -->
        <div class="flex items-center gap-6">
          <!-- Cart -->
          <Link :href="route('cart.index')" class="relative text-white text-xl">
            🛒
            <span
              v-if="page.props.cartCount > 0"
              class="absolute -top-2 -right-2 bg-red-600 text-white text-xs px-2 rounded-full"
            >
              {{ page.props.cartCount }}
            </span>
          </Link>

          <!-- User Menu -->
          <DropdownMenu>
            <DropdownMenuTrigger as-child>
              <button
                class="flex items-center gap-2 rounded-full px-2 py-1 hover:bg-gray-700 transition"
              >
                <img :src="user.avatar ?? '/avatar.png'" class="h-8 w-8 rounded-full" />
                <span class="hidden sm:block text-sm text-white">
                  {{ user.name }}
                </span>
              </button>
            </DropdownMenuTrigger>

            <DropdownMenuContent
              align="end"
              class="w-56 bg-gray-800 border-gray-700 text-gray-200"
            >
              <UserMenu :user="user" />
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-6 overflow-y-auto bg-gray-100 dark:bg-gray-900 text-gray-200">
        <slot />
      </main>
    </div>
  </div>
</template>
