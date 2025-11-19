<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Create Vendor" />

    <div class="flex flex-col gap-5">
      <div class="shadow rounded-lg p-6 bg-card text-card-foreground">
        <h2 class="text-xl font-bold mb-6">Edit Vendor</h2>

        <VendorForm
          :form="form"
          :onSubmit="submit"
          submitText="Update Vendor"
          :vendorUsers="vendorUsers"
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
import VendorForm from "@/Components/vendor/VendorForm.vue";

const showModal = ref(false);
const deletingId = ref(null);

function confirmDelete(id: number) {
  deletingId.value = id;
  showModal.value = true;
}
const breadcrumbs = [
  { title: "Vendors", href: route("vendors.index") },
  { title: "Update" },
];

function handleConfirm() {
  if (deletingId.value) {
    router.delete(route("vendors.destroy", deletingId.value), {
      onSuccess: (page) => {
        vendors.data = vendors.data.filter((cat) => cat.id !== deletingId.value);
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
  vendor: Object,
  vendorUsers: Array,
});

const form = useForm({
  store_name: props.vendor.store_name,
  store_description: props.vendor.store_description,
  active: props.vendor.active, // default
  vendorUsers: props.vendorUsers,
  user_id: props.vendor.user_id,
});

function submit() {
  form.put(route("vendors.update", props.vendor.id));
}
</script>
