<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Create Category" />

    <div class="flex flex-col gap-5">
      <div class="shadow rounded-lg p-6 bg-card text-card-foreground">
        <h2 class="text-xl font-bold mb-6">Add New Category</h2>

        <CategoryForm :form="form" :onSubmit="submit" submitText="Save Category" />
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { useForm, Head } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import AppLayout from "@/layouts/AppLayout.vue";
import CategoryForm from "@/Components/category/CategoryForm.vue";

const breadcrumbs = [
  { title: "Categories", href: route("categories.index") },
  { title: "Add New" },
];

const form = useForm({
  name: "",
  description: "",
});

function submit() {
  form.post(route("categories.store"));
}
</script>
