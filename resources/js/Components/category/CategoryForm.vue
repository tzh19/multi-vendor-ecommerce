<template>
  <form @submit.prevent="onSubmit" class="space-y-6">
    <!-- Name -->
    <div class="grid gap-2">
      <Label for="name">Category Name</Label>
      <Input
        id="name"
        name="name"
        v-model="form.name"
        placeholder="Enter category name"
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
        placeholder="Enter category description"
        class="mt-1 block w-full h-32 px-3 py-2 text-lg rounded border"
      ></textarea>
      <InputError :message="form.errors.description" />
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

const props = defineProps({
  form: {
    type: Object,
    required: true,
  },
  onSubmit: {
    type: Function,
    required: true,
  },
  submitText: {
    type: String,
    default: "Save",
  },
});
</script>
