<script setup lang="ts">
import { reactive } from "vue";
import InputError from "@/Components/InputError.vue";
import TextLink from "@/Components/TextLink.vue";
import { Button } from "@/Components/ui/button";
import { Checkbox } from "@/Components/ui/checkbox";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Spinner } from "@/Components/ui/spinner";
import AuthBase from "@/layouts/AuthLayout.vue";
import { Form, Head } from "@inertiajs/vue3";
import { route } from "ziggy-js";

defineProps<{
  status?: string;
  canResetPassword: boolean;
  canRegister: boolean;
}>();

// Reactive login form
const form = reactive({
  email: "",
  password: "",
  remember: false,
});
</script>

<template>
  <AuthBase
    title="Log in to your account"
    description="Enter your email and password below to log in"
  >
    <Head title="Log in" />

    <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
      {{ status }}
    </div>

    <Form
      :action="route('login')"
      method="post"
      v-model="form"
      reset-on-success
      v-slot="{ errors, processing }"
      class="flex flex-col gap-6"
    >
      <div class="grid gap-6">
        <div class="grid gap-2">
          <Label for="email">Email address</Label>
          <Input
            id="email"
            type="email"
            name="email"
            required
            autofocus
            autocomplete="email"
            placeholder="email@example.com"
          />
          <InputError :message="errors.email" />
        </div>

        <div class="grid gap-2">
          <div class="flex items-center justify-between">
            <Label for="password">Password</Label>
            <TextLink
              v-if="canResetPassword"
              :href="route('password.request')"
              class="text-sm"
            >
              Forgot password?
            </TextLink>
          </div>
          <Input
            id="password"
            type="password"
            name="password"
            required
            autocomplete="current-password"
            placeholder="Password"
          />
          <InputError :message="errors.password" />
        </div>

        <div class="flex items-center justify-between">
          <Label for="remember" class="flex items-center space-x-3">
            <Checkbox id="remember" name="remember" />
            <span>Remember me</span>
          </Label>
        </div>

        <Button type="submit" class="mt-4 w-full" :disabled="processing">
          <Spinner v-if="processing" />
          Log in
        </Button>
      </div>

      <div class="text-center text-sm text-muted-foreground" v-if="canRegister">
        Don't have an account?
        <TextLink :href="route('register')">Sign up</TextLink>
      </div>
    </Form>
  </AuthBase>
</template>
