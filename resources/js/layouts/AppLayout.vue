<script setup lang="ts">
import AppLayout from "@/layouts/app/AppSidebarLayout.vue";
import FlashMessage from "@/components/FlashMessage.vue";

import type { BreadcrumbItemType } from "@/types";
import { usePage } from "@inertiajs/vue3";
import { watch } from "vue";
import { cartCount } from "@/stores/cart";

interface Props {
  breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
  breadcrumbs: () => [],
});

const page = usePage();

watch(
  () => page.props.cartCount,
  (value) => {
    cartCount.value = value ?? 0;
  },
  { immediate: true }
);
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <FlashMessage />
    <slot />
  </AppLayout>
</template>
