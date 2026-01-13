<template>
  <AppLayout>
    <Head title="Edit User" />
    <div class="p-6 bg-card shadow rounded">
      <h2 class="text-xl font-bold mb-4">Edit User</h2>
      <UserForm :user="user" :submitText="'Update User'" :onSubmit="submit" />
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import UserForm from "@/Components/user/UserForm.vue";

const props = defineProps({
  user: Object,
});

function submit(form: any) {
  form.put(route("users.update", props.user.id), {
    preserveScroll: true,
    onSuccess: () => console.log("User updated!"),
  });
}
</script>
