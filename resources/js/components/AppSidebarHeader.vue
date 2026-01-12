<script setup lang="ts">
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import { SidebarTrigger } from "@/Components/ui/sidebar";
import { ShoppingCart } from "lucide-vue-next";
import { Link } from "@inertiajs/vue3";
import { cartCount } from "@/stores/cart";
import { route } from "ziggy-js";
import { computed } from "vue";

import type { BreadcrumbItemType } from "@/types";

withDefaults(defineProps<{ breadcrumbs?: BreadcrumbItemType[] }>(), {
  breadcrumbs: () => [],
});

// local computed to safely use in template
const cartCountValue = computed(() => cartCount.value);
</script>

<template>
  <header
    class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-sidebar-border/70 px-6 md:px-4"
  >
    <!-- Left -->
    <div class="flex items-center gap-2">
      <SidebarTrigger class="-ml-1" />
      <template v-if="breadcrumbs && breadcrumbs.length > 0">
        <Breadcrumbs :breadcrumbs="breadcrumbs" />
      </template>
    </div>

    <!-- Right: Cart icon -->
    <div class="flex items-center gap-4">
      <Link
        :href="route('cart.index')"
        class="relative text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400"
      >
        <ShoppingCart class="w-6 h-6" />

        <span
          v-if="cartCountValue > 0"
          class="absolute -top-1 -right-2 w-5 h-5 flex items-center justify-center rounded-full bg-red-600 text-xs font-semibold text-white"
        >
          {{ cartCountValue }}
        </span>
      </Link>
    </div>
  </header>
</template>
