<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import SalesChart from "@/Components/charts/SalesChart.vue";
import { Head, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { money } from "@/utils/money";

const page = usePage();

const stats = computed(() => page.props.stats);
const chart = computed(() => page.props.chart);

const breadcrumbs = [{ title: "Dashboard" }];
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-1 flex-col gap-4 p-4">
      <!-- Stats -->
      <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div class="rounded-xl border bg-card p-4">
          <p class="text-sm text-muted-foreground">Total Sales</p>
          <h3 class="text-2xl font-bold">
            {{ money(stats.totalSales) }}
          </h3>
        </div>

        <div class="rounded-xl border bg-card p-4">
          <p class="text-sm text-muted-foreground">Orders</p>
          <h3 class="text-2xl font-bold">
            {{ stats.orders }}
          </h3>
        </div>

        <div class="rounded-xl border bg-card p-4">
          <p class="text-sm text-muted-foreground">Active Vendors</p>
          <h3 class="text-2xl font-bold">
            {{ stats.activeVendors }}
          </h3>
        </div>
      </div>

      <!-- Chart -->
      <div class="rounded-xl border bg-card p-4">
        <h2 class="mb-4 text-lg font-semibold">Weekly Sales</h2>
        <SalesChart :labels="chart.labels" :data="chart.data" />
      </div>
    </div>
  </AppLayout>
</template>
