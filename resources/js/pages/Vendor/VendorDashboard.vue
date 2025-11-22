<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { route } from 'ziggy-js';
import ApexCharts from 'vue3-apexcharts';

const props = defineProps({
  salesPerProduct: {
    type: Array,
    required: true,
  },
});

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Vendor Dashboard',
    href: route('vendor.dashboard'),
  },
];

// Chart options
const chartOptions = {
  chart: { id: 'sales-per-product' },
  xaxis: { categories: props.salesPerProduct.map(p => p.name) },
  title: { text: 'Revenue per Product' },
};

const chartSeries = [
  {
    name: 'Revenue',
    data: props.salesPerProduct.map(p => p.revenue),
  },
];
</script>

<template>
  <Head title="Vendor Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-6 p-4">
      <!-- Check if there is data -->
      <template v-if="salesPerProduct.length > 0">
        <!-- Sales Table -->
        <div
          class="overflow-x-auto rounded-xl border border-sidebar-border/70 dark:border-sidebar-border shadow-sm"
        >
          <table
            class="min-w-full table-auto divide-y divide-gray-200 dark:divide-gray-700"
          >
            <thead class="bg-gray-100 dark:bg-gray-800">
              <tr>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Product
                </th>
                <th
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Quantity Sold
                </th>
                <th
                  class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Revenue
                </th>
              </tr>
            </thead>
            <tbody
              class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700"
            >
              <tr
                v-for="product in salesPerProduct"
                :key="product.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
              >
                <td
                  class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100"
                >
                  {{ product.name }}
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-center text-gray-700 dark:text-gray-300"
                >
                  {{ product.quantity_sold }}
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-right text-gray-700 dark:text-gray-300"
                >
                  ${{ Number(product.revenue ?? 0).toFixed(2) }}
                </td>
              </tr>
              <tr v-if="salesPerProduct.length === 0">
                <td
                  colspan="3"
                  class="px-6 py-4 text-center text-gray-500 dark:text-gray-400"
                >
                  No sales data available.
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Chart -->
        <div
          class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
        >
          <apexchart
            type="bar"
            :options="chartOptions"
            :series="chartSeries"
            height="350"
          />
        </div>
      </template>

      <!-- Placeholder if no data -->
      <template v-else>
        <div
          class="flex flex-col items-center justify-center gap-4 rounded-xl border border-sidebar-border/70 p-10 text-gray-500 dark:text-gray-400"
        >
          <svg
            class="h-12 w-12 text-gray-300"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 3v18h18V3H3z"
            />
          </svg>
          <p>No sales data available yet.</p>
          <p>
            Once your products start selling, you will see the sales per product here.
          </p>
        </div>
      </template>
    </div>
  </AppLayout>
</template>

<script lang="ts">
export default {
  components: { apexchart: ApexCharts },
};
</script>
