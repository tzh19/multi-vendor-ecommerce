<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, Link, router } from '@inertiajs/vue3';
import PlaceholderPattern from '../Components/PlaceholderPattern.vue';
import { ref } from "vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";

const { categories } = usePage().props;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: route('categories.index'),
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
    router.delete(route("categories.destroy", deletingId.value), {
      onSuccess: (page) => {
        categories.data = categories.data.filter((cat) => cat.id !== deletingId.value);
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
  <Head title="Categories" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-bold">Categories</h2>
              <!-- Add Category Button -->
              <Link
                href="/categories/create"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
              >
                + Add Category
              </Link>
            </div>

            <!-- Table Wrapper Card -->
            <div class="rounded-xl border bg-card text-card-foreground shadow-sm p-6">
              <table class="w-full text-sm">
                <thead class="text-left border-b">
                  <tr>
                    <th class="pb-3 w-16">ID</th>
                    <th class="pb-3 w-1/3">Name</th>
                    <th class="pb-3 w-1/3">Description</th>
                    <th class="pb-3 w-32">Created</th>
                    <th class="pb-3 text-right w-24">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="category in categories.data"
                    :key="category.id"
                    class="border-b"
                  >
                    <td class="py-3">{{ category.id }}</td>
                    <td class="py-3">{{ category.name }}</td>
                    <td class="py-3">{{ category.description }}</td>
                    <td class="py-3">{{ category.created_at }}</td>
                    <td class="py-3 text-right">
                      <div class="flex justify-end gap-2">
                        <Link
                          :href="`categories/${category.id}/edit`"
                          class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600"
                        >
                          Edit
                        </Link>
                        <button
                          @click="confirmDelete(category.id)"
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
                v-if="categories.prev_page_url"
                @click="$inertia.visit(categories.prev_page_url)"
                class="px-3 py-1 border rounded"
              >
                Previous
              </button>
              <!-- Page Numbers -->
              <button
                v-for="page in categories.last_page"
                :key="page"
                @click="$inertia.visit(`/categories?page=${page}`)"
                :class="[
                  'px-3 py-1 border rounded',
                  { 'bg-blue-500 text-white': page === categories.current_page },
                ]"
              >
                {{ page }}
              </button>
              <button
                v-if="categories.next_page_url"
                @click="$inertia.visit(categories.next_page_url)"
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
      message="Are you sure you want to delete this category?"
      @confirm="handleConfirm"
      @cancel="handleCancel"
    />
  </AppLayout>
</template>
