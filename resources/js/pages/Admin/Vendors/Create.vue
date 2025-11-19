<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Create Vendor" />

    <div class="flex flex-col gap-5">
      <div class="shadow rounded-lg p-6 bg-card text-card-foreground">
        <h2 class="text-xl font-bold mb-6">Add New Vendor</h2>

        <VendorForm
          :form="form"
          :onSubmit="submit"
          submitText="Save Vendor"
          :vendor-users="vendorUsers"
        />
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { useForm, Head, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import AppLayout from "@/Layouts/AppLayout.vue";
import VendorForm from "@/Components/vendor/VendorForm.vue";

const breadcrumbs = [
  { title: "Vendors", href: route("vendors.index") },
  { title: "Add New" },
];

// Get Inertia props
const props = defineProps({
  vendorUsers: Array,
});

const form = useForm({
  store_name: "",
  store_description: "",
  active: true, // default
  user_id: null,
});

function submit() {
  form.post(route("vendors.store"));
}
</script>
