<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { login } from '@/routes';
</script>

<template>
    <Head title="Register" />

    <div class="flex flex-col gap-6 text-center py-8">
        <h1 class="text-xl font-semibold">Registration disabled</h1>
        <p class="text-sm text-muted-foreground">Register a new account is not available.</p>
        <p class="text-sm">
            Already have an account?
            <a :href="login().url" class="underline underline-offset-4">Log in</a>
        </p>
    </div>
</template>