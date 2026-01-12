<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, Link, router } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { ref } from "vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";

const { users } = usePage().props;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: route('users.index'),
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
    router.delete(route("users.destroy", deletingId.value), {
      onSuccess: (page) => {
        users.data = users.data.filter((cat) => cat.id !== deletingId.value);
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
  <Head title="Users" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-bold">Users</h2>
              <!-- Add User Button -->
              <Link
                href="/users/create"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
              >
                + Add User
              </Link>
            </div>
            <!-- Table Wrapper Card -->
            <div class="rounded-xl border bg-card text-card-foreground shadow-sm p-6">
              <table class="w-full text-sm">
                <thead class="text-left border-b">
                  <tr>
                    <th class="pb-3">ID</th>
                    <th class="pb-3">Name</th>
                    <th class="pb-3">Email</th>
                    <th class="pb-3">Role</th>
                    <th class="pb-3">Active</th>
                    <th class="pb-3">Created</th>
                    <th class="pb-3">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="user in users.data" :key="user.id" class="border-b">
                    <td class="py-3">{{ user.id }}</td>
                    <td class="py-3">{{ user.name }}</td>
                    <td class="py-3">{{ user.email }}</td>
                    <td class="py-3">{{ user.role }}</td>
                    <td class="py-3">
                      <span
                        class="px-2 py-1 rounded text-xs font-semibold"
                        :class="
                          user.is_active
                            ? 'bg-green-600/20 text-green-400'
                            : 'bg-red-600/20 text-red-400'
                        "
                      >
                        {{ user.is_active ? "Active" : "Inactive" }}
                      </span>
                    </td>
                    <td class="py-3">{{ user.created_at }}</td>
                    <td class="py-3">
                      <div class="flex gap-2">
                        <Link
                          :href="`users/${user.id}/edit`"
                          class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600"
                        >
                          Edit
                        </Link>
                        <button
                          @click="confirmDelete(user.id)"
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
                v-if="users.prev_page_url"
                @click="$inertia.visit(users.prev_page_url)"
                class="px-3 py-1 border rounded"
              >
                Previous
              </button>
              <!-- Page Numbers -->
              <button
                v-for="page in users.last_page"
                :key="page"
                @click="$inertia.visit(`/users?page=${page}`)"
                :class="[
                  'px-3 py-1 border rounded',
                  { 'bg-blue-500 text-white': page === users.current_page },
                ]"
              >
                {{ page }}
              </button>
              <button
                v-if="users.next_page_url"
                @click="$inertia.visit(users.next_page_url)"
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
      message="Are you sure you want to delete this user?"
      @confirm="handleConfirm"
      @cancel="handleCancel"
    />
  </AppLayout>
</template>
