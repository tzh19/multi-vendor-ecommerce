<template>
  <form @submit.prevent="submitForm" class="space-y-6">
    <div class="grid gap-2">
      <Label for="name">Name</Label>
      <Input v-model="form.name" id="name" placeholder="Enter name" />
      <InputError :message="form.errors.name" />
    </div>

    <div class="grid gap-2">
      <Label for="email">Email</Label>
      <Input v-model="form.email" id="email" placeholder="Enter email" />
      <InputError :message="form.errors.email" />
    </div>

    <div class="grid gap-2">
      <Label for="role">Role</Label>
      <select v-model="form.role" id="role" class="border rounded px-2 py-1">
        <option value="admin">Admin</option>
        <option value="vendor">Vendor</option>
        <option value="customer">Customer</option>
      </select>
      <InputError :message="form.errors.role" />
    </div>

    <div class="grid gap-2">
      <Label for="password">Password</Label>
      <Input
        v-model="form.password"
        id="password"
        type="password"
        placeholder="Enter password"
      />
      <InputError :message="form.errors.password" />
    </div>

    <div class="grid gap-2">
      <Label for="password_confirmation">Confirm Password</Label>
      <Input
        v-model="form.password_confirmation"
        id="password_confirmation"
        type="password"
        placeholder="Confirm password"
      />
    </div>

    <div class="flex items-center gap-3 mt-4">
      <button
        type="button"
        @click="form.is_active = !form.is_active"
        class="relative inline-flex h-6 w-12 items-center rounded-full transition"
        :class="form.is_active ? 'bg-green-600' : 'bg-gray-300'"
      >
        <span
          class="inline-block h-5 w-5 transform rounded-full bg-white transition"
          :class="form.is_active ? 'translate-x-6' : 'translate-x-1'"
        />
      </button>

      <span class="text-sm">
        {{ form.is_active ? "Active" : "Inactive" }}
      </span>
    </div>

    <div class="flex justify-end">
      <Button type="submit" class="bg-blue-600 text-white">{{ submitText }}</Button>
    </div>
  </form>
</template>

<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import Label from "@/Components/ui/label/Label.vue";
import Input from "@/Components/ui/input/Input.vue";
import InputError from "@/Components/InputError.vue";
import Button from "@/Components/ui/button/Button.vue";

const props = defineProps({
  user: Object,
  submitText: String,
  onSubmit: Function,
});

const form = useForm({
  name: props.user?.name || "",
  email: props.user?.email || "",
  role: props.user?.role || "customer",
  password: "",
  password_confirmation: "",
  is_active: props.user?.is_active ?? true,
});

function submitForm() {
  props.onSubmit(form);
}
</script>
