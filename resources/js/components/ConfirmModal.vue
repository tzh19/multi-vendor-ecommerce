<script setup lang="ts">
import { defineProps, defineEmit } from "vue";

const props = defineProps({
  title: { type: String, default: "Confirm" },
  message: { type: String, required: true },
  show: { type: Boolean, required: true },
});

const emit = defineEmits(["confirm", "cancel"]);

function handleConfirm() {
  emit("confirm");
}

function handleCancel() {
  emit("cancel");
}
</script>
<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black/20 backdrop-blur-[2px] flex items-center justify-center z-50"
  >
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96 shadow-lg">
      <h2 class="text-lg font-bold mb-4">{{ title }}</h2>
      <p class="mb-6">{{ message }}</p>
      <div class="flex justify-end gap-3">
        <button
          @click="handleCancel"
          class="px-4 py-2 rounded border hover:bg-gray-100 dark:hover:bg-gray-700"
        >
          Cancel
        </button>
        <button
          @click="handleConfirm"
          class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>
