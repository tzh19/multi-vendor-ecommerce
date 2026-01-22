<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Create Category" />

    <div class="flex flex-col gap-5">
      <div class="shadow rounded-lg p-6 bg-card text-card-foreground">
        <h2 class="text-xl font-bold mb-6">Edit Category</h2>

        <CategoryForm :form="form" :onSubmit="submit" submitText="Update Category" />
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

<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { Head, router } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import Label from "@/Components/ui/label/Label.vue";
import Input from "@/Components/ui/input/Input.vue";
import InputError from "@/Components/InputError.vue";
import Button from "@/Components/ui/button/Button.vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";
import { ref } from "vue";
import CategoryForm from "@/Components/category/CategoryForm.vue";

const showModal = ref(false);
const deletingId = ref(null);

function confirmDelete(id: number) {
  deletingId.value = id;
  showModal.value = true;
}
const breadcrumbs = [
  { title: "Categories", href: route("categories.index") },
  { title: "Update" },
];

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

const props = defineProps({
  category: Object,
});

const form = useForm({
  name: props.category.name,
  description: props.category.description,
});

function submit() {
  form.put(route("categories.update", props.category.id));
}
</script>
