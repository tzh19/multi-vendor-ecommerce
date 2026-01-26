<script setup lang="ts">
import { reactive } from "vue";
import InputError from "@/Components/InputError.vue";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Spinner } from "@/Components/ui/spinner";
import AuthLayout from "@/layouts/AuthLayout.vue";
import { Form, Head } from "@inertiajs/vue3";

// Reactive form data
const form = reactive({
  password: "",
});
</script>

<template>
  <AuthLayout
    title="Confirm your password"
    description="This is a secure area of the application. Please confirm your password before continuing."
  >
    <Head title="Confirm password" />

    <Form
      :action="'/user/confirm-password'"
      method="post"
      v-model="form"
      reset-on-success
      v-slot="{ errors, processing }"
    >
      <div class="space-y-6">
        <div class="grid gap-2">
          <Label for="password">Password</Label>
          <Input
            id="password"
            type="password"
            name="password"
            class="mt-1 block w-full"
            required
            autocomplete="current-password"
            autofocus
          />
          <InputError :message="errors.password" />
        </div>

        <div class="flex items-center">
          <Button
            class="w-full"
            :disabled="processing"
            data-test="confirm-password-button"
          >
            <Spinner v-if="processing" />
            Confirm Password
          </Button>
        </div>
      </div>
    </Form>
  </AuthLayout>
</template>
