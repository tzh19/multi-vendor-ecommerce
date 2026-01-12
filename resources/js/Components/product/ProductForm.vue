<template>
  <form @submit.prevent="onSubmit" class="space-y-6">
    <!-- Category -->
    <div class="grid gap-2">
      <Label>Category</Label>

      <DropdownMenu>
        <DropdownMenuTrigger
          class="w-full h-12 px-3 flex items-center justify-between rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <span>
            {{
              form.category_id
                ? categories.find((c) => c.id === form.category_id)?.name
                : "Select category"
            }}
          </span>
          <span class="text-gray-400 text-xs">▼</span>
        </DropdownMenuTrigger>

        <DropdownMenuContent
          class="w-[var(--radix-dropdown-menu-trigger-width)] mt-1 rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-lg p-1"
        >
          <DropdownMenuItem
            class="px-3 py-2 rounded cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 focus:bg-gray-100 dark:focus:bg-gray-700"
          >
            {{ category.name }}
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>

      <InputError :message="form.errors.category_id" />
    </div>

    <!-- Vendor (Admin only) -->
    <div v-if="!isVendor" class="grid gap-2">
      <Label>Vendor</Label>

      <DropdownMenu>
        <DropdownMenuTrigger
          class="w-full h-12 px-3 flex items-center justify-between rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <span>
            {{
              form.vendor_id
                ? vendors.find((v) => v.id === form.vendor_id)?.store_name
                : "Select vendor"
            }}
          </span>
          <span class="text-gray-400 text-xs">▼</span>
        </DropdownMenuTrigger>

        <DropdownMenuContent
          class="w-[var(--radix-dropdown-menu-trigger-width)] mt-1 rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-lg p-1"
        >
          <DropdownMenuItem
            v-for="vendor in vendors"
            :key="vendor.id"
            @click="form.vendor_id = vendor.id"
            class="px-3 py-2 rounded cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 focus:bg-gray-100 dark:focus:bg-gray-700"
          >
            {{ vendor.store_name }}
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>

      <InputError :message="form.errors.vendor_id" />
    </div>

    <!-- Hidden vendor_id for vendor role -->
    <input v-if="isVendor" type="hidden" name="vendor_id" v-model="form.vendor_id" />

    <!-- Product Name -->
    <div class="grid gap-2">
      <Label for="name">Product Name</Label>
      <Input
        id="name"
        name="name"
        v-model="form.name"
        placeholder="Enter product name"
        class="mt-1 block w-full h-12 px-3 text-lg rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500"
        required
      />
      <InputError :message="form.errors.name" />
    </div>

    <!-- Description -->
    <div class="grid gap-2">
      <Label for="description">Description</Label>
      <textarea
        id="description"
        name="description"
        v-model="form.description"
        placeholder="Enter description"
        class="mt-1 block w-full h-32 px-3 py-2 text-lg rounded border"
      ></textarea>
      <InputError :message="form.errors.description" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full">
      <!-- Stock -->
      <div class="flex flex-col">
        <Label for="stock">Stock</Label>
        <Input
          id="stock"
          type="number"
          v-model.number="form.stock"
          min="0"
          placeholder="Enter stock"
          class="mt-1"
        />
        <InputError :message="form.errors.stock" />
      </div>

      <!-- Price -->
      <div class="flex flex-col">
        <Label for="price">Price</Label>
        <Input
          id="price"
          type="number"
          v-model.number="form.price"
          min="0"
          step="0.01"
          placeholder="Enter price"
          class="mt-1"
        />
        <InputError :message="form.errors.price" />
      </div>
    </div>

    <div class="grid gap-2">
      <Label for="image">Product Image</Label>

      <!-- New Image Upload -->
      <input
        id="image"
        type="file"
        accept="image/*"
        @change="previewImage"
        class="border p-2 rounded"
      />
      <InputError :message="form.errors.image" />

      <!-- Preview of New Image -->
      <img
        v-if="imagePreview"
        :src="imagePreview"
        class="w-32 h-32 object-cover rounded mt-2"
      />

      <!-- Existing Image (Edit Page) -->
      <img
        v-else-if="props.product?.image"
        :src="props.product.image_url || `/storage/${props.product.image}`"
        class="w-32 h-32 object-cover rounded mt-2"
        alt="Current image"
      />
    </div>

    <!-- Submit -->
    <div class="flex justify-end">
      <Button
        type="submit"
        :disabled="form.processing"
        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"
      >
        {{ submitText }}
      </Button>
    </div>
  </form>
</template>
<script setup lang="ts">
import { ref, computed, watchEffect } from "vue";
import { usePage } from "@inertiajs/vue3";

import Label from "@/Components/ui/label/Label.vue";
import Input from "@/Components/ui/input/Input.vue";
import InputError from "@/Components/InputError.vue";
import Button from "@/Components/ui/button/Button.vue";
import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
  DropdownMenuItem,
} from "@/Components/ui/dropdown-menu";

const props = defineProps({
  form: Object,
  onSubmit: Function,
  submitText: String,
  vendors: Array,
  categories: Array,
  product: Object,
});

/* ---------- AUTH ---------- */
const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);
const isVendor = computed(() => user.value?.role === "vendor");

/* ---------- FORCE vendor_id ---------- */
watchEffect(() => {
  if (isVendor.value && user.value?.id) {
    props.form.vendor_id = user.value.id;
  }
});

/* ---------- Image preview ---------- */
const imagePreview = ref<string | null>(null);

function previewImage(event: Event) {
  const file = (event.target as HTMLInputElement).files?.[0];
  if (!file) return;

  imagePreview.value = URL.createObjectURL(file);
  props.form.image = file;
}
</script>
