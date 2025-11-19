<template>
  <form @submit.prevent="onSubmit" class="space-y-6">
    <!-- Vendor User -->
    <div class="grid gap-2">
      <Label for="user_id">Assign Vendor User</Label>

      <DropdownMenu>
        <DropdownMenuTrigger
          class="w-full h-12 px-3 flex items-center justify-between rounded border border-gray-700 bg-gray-900 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <span>
            {{
              form.user_id
                ? vendorUsers.find((u) => u.id === form.user_id)?.name
                : "Select vendor user"
            }}
          </span>

          <span class="text-gray-400 text-xs">▼</span>
        </DropdownMenuTrigger>

        <DropdownMenuContent
          :side-offset="4"
          :align="'start'"
          :avoid-collisions="false"
          class="w-[var(--radix-dropdown-menu-trigger-width)] mt-1 rounded-lg border border-gray-700 bg-gray-900 text-gray-100 shadow-xl p-1"
        >
          <DropdownMenuItem
            v-for="user in vendorUsers"
            :key="user.id"
            @select="form.user_id = user.id"
            class="px-3 py-2 rounded cursor-pointer hover:bg-gray-800 focus:bg-gray-800"
          >
            {{ user.name }}
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>

      <InputError :message="form.errors.user_id" />
    </div>

    <!-- Store Name -->
    <div class="grid gap-2">
      <Label for="name">Vendor Name</Label>
      <Input
        id="store_name"
        name="store_name"
        v-model="form.store_name"
        placeholder="Enter vendor name"
        class="mt-1 block w-full h-12 px-3 text-lg rounded border"
        required
      />
      <InputError :message="form.errors.store_name" />
    </div>

    <!-- Description -->
    <div class="grid gap-2">
      <Label for="description">Description</Label>
      <textarea
        id="description"
        name="stoce_description"
        v-model="form.store_description"
        placeholder="Enter vendor description"
        class="mt-1 block w-full h-32 px-3 py-2 text-lg rounded border"
      ></textarea>
      <InputError :message="form.errors.store_description" />
    </div>

    <!-- Active Toggle -->
    <div class="flex items-center gap-3">
      <button
        type="button"
        @click="form.active = !form.active"
        class="relative inline-flex h-6 w-12 items-center rounded-full transition"
        :class="form.active ? 'bg-green-600' : 'bg-gray-300'"
      >
        <span
          class="inline-block h-5 w-5 transform rounded-full bg-white transition"
          :class="form.active ? 'translate-x-6' : 'translate-x-1'"
        />
      </button>

      <span class="text-sm text-white-700">
        {{ form.active ? "Active" : "Inactive" }}
      </span>

      <InputError :message="form.errors.active" />
    </div>

    <InputError :message="form.errors.active" />

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
import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
  DropdownMenuItem,
} from "@/Components/ui/dropdown-menu";

const props = defineProps({
  form: Object,
  onSubmit: Function,
  submitText: String,
  vendorUsers: {
    type: Array,
    required: true,
  },
});
</script>
