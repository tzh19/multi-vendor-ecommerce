<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Create Category" />

    <div class="flex flex-col gap-5">
      <div class="shadow rounded-lg p-6 bg-card text-card-foreground">
        <h2 class="text-xl font-bold mb-6">Add New Category</h2>

        <form @submit.prevent="submit" class="space-y-6">
          <!-- Name Field -->
          <div class="grid gap-2">
            <Label for="name">Category Name</Label>
            <Input
              id="name"
              name="name"
              placeholder="Enter category name"
              v-model="form.name"
              required
              class="mt-1 block w-full h-12 px-3 text-lg rounded border"
            />
            <InputError :message="form.errors.name" />
          </div>

          <!-- Description Field -->
          <div class="grid gap-2">
            <Label for="description">Description</Label>
            <textarea
              id="description"
              name="description"
              placeholder="Enter description"
              v-model="form.description"
              class="mt-1 block w-full h-32 px-3 py-2 text-lg rounded border"
            ></textarea>
            <InputError :message="form.errors.description" />
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end">
            <Button
              type="submit"
              :disabled="form.processing"
              class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"
            >
              Save Category
            </Button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Label from "@/Components/ui/label/Label.vue";
import Input from "@/Components/ui/input/Input.vue";
import InputError from "@/Components/InputError.vue";
import Button from "@/Components/ui/button/Button.vue";

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
