<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Create User" />

    <div class="flex flex-col gap-5">
      <div class="shadow rounded-lg p-6 bg-card text-card-foreground">
        <h2 class="text-xl font-bold mb-6">Edit User</h2>

        <UserForm :form="form" :onSubmit="submit" submitText="Update User" />
      </div>
    </div>
    <ConfirmModal
      :show="showModal"
      message="Are you sure you want to delete this user?"
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
import UserForm from "@/Components/user/UserForm.vue";

const showModal = ref(false);
const deletingId = ref(null);

function confirmDelete(id: number) {
  deletingId.value = id;
  showModal.value = true;
}
const breadcrumbs = [{ title: "Users", href: route("users.index") }, { title: "Update" }];

function handleConfirm() {
  if (deletingId.value) {
    router.delete(route("users.destroy", deletingId.value), {
      onSuccess: (page) => {
        users.data = users.data.filter((cat) => cat.id !== deletingId.value);
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
  user: Object,
});

const form = useForm({
  name: props.user.name,
  description: props.user.description,
});

function submit() {
  form.put(route("users.update", props.user.id));
}
</script>
