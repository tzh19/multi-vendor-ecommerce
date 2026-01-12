<script setup>
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const message = ref(null);
const type = ref("success");

watch(
  () => page.props.flash,
  (flash) => {
    console.log(flash);
    if (flash?.success) {
      message.value = flash.success;
      type.value = "success";
    }

    if (flash?.error) {
      message.value = flash.error;
      type.value = "error";
    }

    if (message.value) {
      setTimeout(() => {
        message.value = null;
      }, 3000);
    }
  },
  { immediate: true }
);
</script>

<template>
  <transition name="fade">
    <div
      v-if="message"
      :class="[
        'fixed top-4 right-4 px-4 py-2 rounded shadow text-white z-50',
        type === 'success' ? 'bg-green-600' : 'bg-red-600',
      ]"
    >
      {{ message }}
    </div>
  </transition>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
