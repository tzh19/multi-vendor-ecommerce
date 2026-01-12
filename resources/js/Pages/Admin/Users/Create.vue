<template>
  <AppLayout>
    <Head title="Create User" />
    <div class="p-6 bg-card shadow rounded">
      <h2 class="text-xl font-bold mb-4">Create User</h2>
      <UserForm :submitText="'Create User'" :onSubmit="submit" />
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import UserForm from "@/Components/User/UserForm.vue";

function submit(form: any) {
  form.post(route("users.store"), {
    onSuccess: () => console.log("User created!"),
  });
}
</script>
