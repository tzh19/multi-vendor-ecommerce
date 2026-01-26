<script setup lang="ts">
import { reactive } from "vue";
import { Form, Head } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import TextLink from "@/Components/TextLink.vue";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Spinner } from "@/Components/ui/spinner";
import AuthLayout from "@/layouts/AuthLayout.vue";

defineProps<{
  status?: string;
}>();

// Create a simple reactive form
const form = reactive({
  email: "",
});
</script>

<template>
  <AuthLayout
    title="Forgot password"
    description="Enter your email to receive a password reset link"
  >
    <Head title="Forgot password" />

    <!-- Success message -->
    <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
      {{ status }}
    </div>

    <div class="space-y-6">
      <!-- Form -->
      <Form
        :action="'/forgot-password'"
        method="post"
        v-model="form"
        v-slot="{ errors, processing }"
      >
        <div class="grid gap-2">
          <Label for="email">Email address</Label>
          <Input
            id="email"
            type="email"
            name="email"
            autocomplete="off"
            autofocus
            placeholder="email@example.com"
          />
          <InputError :message="errors.email" />
        </div>

        <div class="my-6 flex items-center justify-start">
          <Button
            class="w-full"
            :disabled="processing"
            data-test="email-password-reset-link-button"
          >
            <Spinner v-if="processing" />
            Email password reset link
          </Button>
        </div>
      </Form>

      <!-- Back to login -->
      <div class="space-x-1 text-center text-sm text-muted-foreground">
        <span>Or, return to</span>
        <TextLink href="/login">log in</TextLink>
      </div>
    </div>
  </AuthLayout>
</template>
