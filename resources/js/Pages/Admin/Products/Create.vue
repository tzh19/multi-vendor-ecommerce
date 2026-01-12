<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Create Product" />

    <div class="flex flex-col gap-5">
      <div class="shadow rounded-lg p-6 bg-card text-card-foreground">
        <h2 class="text-xl font-bold mb-6">Add New Product</h2>

        <ProductForm
          :form="form"
          :onSubmit="submit"
          submitText="Save Product"
          :vendors="vendors"
          :categories="categories"
        />
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { useForm, Head, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import AppLayout from "@/Layouts/AppLayout.vue";
import ProductForm from "@/Components/product/ProductForm.vue";

const breadcrumbs = [
  { title: "Products", href: route("admin.products.index") },
  { title: "Add New" },
];

// Get Inertia props
const props = defineProps({
  vendors: Array,
  categories: Array,
});

const form = useForm({
  name: "",
  description: "",
  price: "",
  stock: "",
  vendor_id: null,
  category_id: null,
  image: null,
});

function submit() {
  form.post(route("admin.products.store"));
}
</script>
