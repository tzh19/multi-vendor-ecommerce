<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, Link, router } from '@inertiajs/vue3';
import PlaceholderPattern from '../Components/PlaceholderPattern.vue';
import { ref } from "vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";

const { products } = usePage().props;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: route('admin.products.index'),
    },
];

const showModal = ref(false);
const deletingId = ref(null);

function confirmDelete(id: number) {
  deletingId.value = id;
  showModal.value = true;
}

function handleConfirm() {
  if (deletingId.value) {
    router.delete(route("admin.products.destroy", deletingId.value), {
      onSuccess: (page) => {
        products.data = products.data.filter((cat) => cat.id !== deletingId.value);
      },
      preserveScroll: true,
    });
    showModal.value = false;
  }
}

function handleCancel() {
  showModal.value = false;
}
</script>
<template>
  <Head title="Products" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-bold">Products</h2>
              <!-- Add Product Button -->
              <Link
                href="/admin/products/create"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
              >
                + Add Product
              </Link>
            </div>

            <!-- Table Wrapper Card -->
            <div class="rounded-xl border bg-card text-card-foreground shadow-sm p-6">
              <table class="w-full text-sm">
                <thead>
                  <tr>
                    <th class="px-4 py-2 w-16">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2 w-20 text-center">Price</th>
                    <th class="px-4 py-2 w-32">Stock</th>
                    <th class="px-4 py-2 w-32">Created</th>
                    <th class="px-4 py-2 w-32 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="product in products.data"
                    :key="product.id"
                    class="border-b hover:bg-gray-50 dark:hover:bg-gray-700"
                  >
                    <td class="px-4 py-2 w-16">{{ product.id }}</td>
                    <td class="px-4 py-2">{{ product.name }}</td>
                    <td class="px-4 py-2">{{ product.description }}</td>
                    <td class="px-4 py-2 w-20 text-center">{{ product.price }}</td>
                    <td class="px-4 py-2 w-32">{{ product.stock }}</td>
                    <td class="px-4 py-2 w-32">{{ product.created_at }}</td>
                    <td class="px-4 py-2 w-32 text-right">
                      <div class="flex justify-end gap-2">
                        <Link
                          :href="`/admin/products/${product.id}/edit`"
                          class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600"
                        >
                          Edit
                        </Link>
                        <button
                          @click="confirmDelete(product.id)"
                          class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
                        >
                          Delete
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- Pagination -->
            <div class="mt-4 flex gap-2">
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
                @click="$inertia.visit(`/admin/products?page=${page}`)"
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
          </div>
        </div>
      </div>
    </div>
    <ConfirmModal
      :show="showModal"
      message="Are you sure you want to delete this product?"
      @confirm="handleConfirm"
      @cancel="handleCancel"
    />
  </AppLayout>
</template>
