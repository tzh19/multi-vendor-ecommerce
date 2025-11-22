<template>
  <form @submit.prevent="onSubmit" class="space-y-6">
    <!-- Category -->
    <div class="grid gap-2">
      <Label for="category_id">Category</Label>

      <DropdownMenu>
        <DropdownMenuTrigger
          class="w-full h-12 px-3 flex items-center justify-between rounded border border-gray-700 bg-gray-900 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <span>
            {{
              form.category_id
                ? categories.find((u) => u.id === form.category_id)?.name
                : "Select category"
            }}
          </span>

          <span class="text-gray-400 text-xs">▼</span>
        </DropdownMenuTrigger>

        <DropdownMenuContent
          :side-offset="4"
          :align="'start'"
          :avoid-collisions="false"
          class="w-[var(--radix-dropdown-menu-trigger-width)] mt-1 rounded-lg border border-gray-700 bg-gray-900 text-gray-100 shadow-xl p-1"
        >
          <DropdownMenuItem
            v-for="category in categories"
            :key="category.id"
            @click="form.category_id = category.id"
            class="px-3 py-2 rounded cursor-pointer hover:bg-gray-800 focus:bg-gray-800"
          >
            {{ category.name }}
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>

      <InputError :message="form.errors.category_id" />
    </div>

    <!-- Vendor -->
    <div class="grid gap-2">
      <Label for="category_id">Vendor</Label>

      <DropdownMenu>
        <DropdownMenuTrigger
          class="w-full h-12 px-3 flex items-center justify-between rounded border border-gray-700 bg-gray-900 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <span>
            {{
              form.vendor_id
                ? vendors.find((u) => u.id === form.vendor_id)?.store_name
                : "Select vendor"
            }}
          </span>

          <span class="text-gray-400 text-xs">▼</span>
        </DropdownMenuTrigger>

        <DropdownMenuContent
          :side-offset="4"
          :align="'start'"
          :avoid-collisions="false"
          class="w-[var(--radix-dropdown-menu-trigger-width)] mt-1 rounded-lg border border-gray-700 bg-gray-900 text-gray-100 shadow-xl p-1"
        >
          <DropdownMenuItem
            v-for="vendor in vendors"
            :key="vendor.id"
            @click="form.vendor_id = vendor.id"
            class="px-3 py-2 rounded cursor-pointer hover:bg-gray-800 focus:bg-gray-800"
          >
            {{ vendor.store_name }}
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>

      <InputError :message="form.errors.vendor_id" />
    </div>

    <!-- Product Name -->
    <div class="grid gap-2">
      <Label for="name">Product Name</Label>
      <Input
        id="name"
        name="name"
        v-model="form.name"
        placeholder="Enter product name"
        class="mt-1 block w-full h-12 px-3 text-lg rounded border"
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
  vendors: {
    type: Array,
    required: true,
  },
  categories: {
    type: Array,
    required: true,
  },
  product: {
    type: Object,
    default: null,
  },
});

import { ref } from "vue";

const imagePreview = ref(null);

function previewImage(event) {
  const file = event.target.files?.[0];

  if (!file) {
    imagePreview.value = null;
    return;
  }

  imagePreview.value = URL.createObjectURL(file);
  props.form.image = file;
}
</script>
