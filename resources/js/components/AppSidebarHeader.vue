<script setup lang="ts">
import Breadcrumbs from "@/components/Breadcrumbs.vue";
import { SidebarTrigger } from "@/components/ui/sidebar";
import { ShoppingCart } from "lucide-vue-next";
import { Link } from "@inertiajs/vue3";
import { cartCount } from "@/stores/cart"; // import storeimport { route } from "ziggy-js";
import { route } from "ziggy-js";

import type { BreadcrumbItemType } from "@/types";

withDefaults(defineProps<{ breadcrumbs?: BreadcrumbItemType[] }>(), {
  breadcrumbs: () => [],
});
</script>

<template>
  <header
    class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
  >
    <!-- Left: sidebar toggle + breadcrumbs -->
    <div class="flex items-center gap-2">
      <SidebarTrigger class="-ml-1" />
      <template v-if="breadcrumbs && breadcrumbs.length > 0">
        <Breadcrumbs :breadcrumbs="breadcrumbs" />
      </template>
    </div>

    <!-- Right: Cart icon -->
    <div class="flex items-center gap-4">
      <Link :href="route('cart.index')" class="relative text-gray-200 hover:text-white">
        <ShoppingCart class="w-6 h-6" />
        <span
          v-if="cartCount > 0"
          class="absolute -top-1 -right-2 bg-red-600 text-xs text-white rounded-full w-5 h-5 flex items-center justify-center"
        >
          {{ cartCount }}
        </span>
      </Link>
    </div>
  </header>
</template>
