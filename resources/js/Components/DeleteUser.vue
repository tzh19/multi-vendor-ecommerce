<script setup lang="ts">
import { ref } from "vue";
import { Form } from "@inertiajs/vue3";

// Components
import HeadingSmall from "@/Components/HeadingSmall.vue";
import InputError from "@/Components/InputError.vue";
import { Button } from "@/Components/ui/button";
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/Components/ui/dialog";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { route } from "ziggy-js";

const passwordInput = ref<HTMLInputElement | null>(null);

// Reactive form
const form = Form({
  password: "",
});

const submit = () => {
  form.post(route("profile.destroy"), {
    preserveScroll: true,
    onError: () => passwordInput.value?.focus(),
  });
};
</script>

<template>
  <div class="space-y-6">
    <HeadingSmall
      title="Delete account"
      description="Delete your account and all of its resources"
    />
    <div
      class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10"
    >
      <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
        <p class="font-medium">Warning</p>
        <p class="text-sm">Please proceed with caution, this cannot be undone.</p>
      </div>

      <Dialog>
        <DialogTrigger as-child>
          <Button variant="destructive" data-test="delete-user-button">
            Delete account
          </Button>
        </DialogTrigger>

        <DialogContent>
          <DialogHeader class="space-y-3">
            <DialogTitle> Are you sure you want to delete your account? </DialogTitle>
            <DialogDescription>
              Once your account is deleted, all of its resources and data will also be
              permanently deleted. Please enter your password to confirm you would like to
              permanently delete your account.
            </DialogDescription>
          </DialogHeader>

          <div class="grid gap-2">
            <Label for="password" class="sr-only">Password</Label>
            <Input
              id="password"
              type="password"
              v-model="form.password"
              ref="passwordInput"
              placeholder="Password"
            />
            <InputError :message="form.errors.password" />
          </div>

          <DialogFooter class="gap-2">
            <DialogClose as-child>
              <Button variant="secondary" @click="form.reset('password')">
                Cancel
              </Button>
            </DialogClose>

            <Button
              type="button"
              variant="destructive"
              :disabled="form.processing"
              @click="submit"
              data-test="confirm-delete-user-button"
            >
              Delete account
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  </div>
</template>
