<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Create Product" />

    <div class="flex flex-col gap-5">
      <div class="shadow rounded-lg p-6 bg-card text-card-foreground">
        <h2 class="text-xl font-bold mb-6">Edit Product</h2>

        <ProductForm
          :form="form"
          :onSubmit="submit"
          submitText="Update Product"
          :vendors="vendors"
          :categories="categories"
          :product="props.product"
        />
      </div>
    </div>
    <ConfirmModal
      :show="showModal"
      message="Are you sure you want to delete this vendor?"
      @confirm="handleConfirm"
      @cancel="handleCancel"
    />
  </AppLayout>
</template>

<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { Head, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Label from "@/Components/ui/label/Label.vue";
import Input from "@/Components/ui/input/Input.vue";
import InputError from "@/Components/InputError.vue";
import Button from "@/Components/ui/button/Button.vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";
import { ref } from "vue";
import ProductForm from "@/Components/product/ProductForm.vue";

const showModal = ref(false);
const deletingId = ref(null);

function confirmDelete(id: number) {
  deletingId.value = id;
  showModal.value = true;
}
const breadcrumbs = [
  { title: "Products", href: route("admin.products.index") },
  { title: "Update" },
];

function handleConfirm() {
  if (deletingId.value) {
    router.delete(route("products.destroy", deletingId.value), {
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

const props = defineProps({
  product: Object,
  vendors: Array,
  categories: Array,
});

const form = useForm({
  vendor_id: props.product.vendor_id,
  category_id: props.product.category_id,
  name: props.product.name,
  description: props.product.description,
  price: props.product.price, // default
  stock: props.product.stock,
  image: null,
});

function submit() {
  form.put(route("admin.products.update", props.product.id));
}
</script>
