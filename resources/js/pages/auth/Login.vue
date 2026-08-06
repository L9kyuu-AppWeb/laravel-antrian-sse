<script setup lang="ts">
import { Form, Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasskeyVerify from '@/components/PasskeyVerify.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

export interface DemoAccount {
    name: string;
    email: string;
    role: string;
}

defineOptions({
    layout: {
        title: 'Log in to your account',
        description: 'Enter your email and password below to log in',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
    demoAccounts?: DemoAccount[];
}>();

const demoForm = useForm({
    email: '',
    password: '',
    remember: false,
});

const fillDemo = (account: DemoAccount) => {
    demoForm.email = account.email;
    demoForm.password = 'password';
    demoForm.post(store.url());
};
</script>

<template>
    <Head title="Log in" />

    <div
        v-if="status"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <PasskeyVerify />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
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
                    :tabindex="1"
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
                        :href="request()"
                        class="text-sm"
                        :tabindex="5"
                    >
                        Forgot your password?
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="Password"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <Label for="remember" class="flex items-center space-x-3">
                    <Checkbox id="remember" name="remember" :tabindex="3" />
                    <span>Remember me</span>
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-4 w-full"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <Spinner v-if="processing" />
                Log in
            </Button>
        </div>
    </Form>

    <div v-if="demoAccounts && demoAccounts.length > 0" class="mt-8">
        <h3 class="mb-4 text-center text-sm font-medium text-muted-foreground">
            Demo accounts per role
        </h3>
        <div class="grid grid-cols-2 gap-4 max-w-md mx-auto">
            <button
                v-for="account in demoAccounts"
                :key="account.email"
                type="button"
                class="group relative flex flex-col items-center justify-center rounded-xl border bg-card p-4 text-left shadow-sm transition-all hover:bg-muted/50 hover:border-primary/50"
                :title="`Auto-fill ${account.name} (${account.role})`"
                :tabindex="-1"
                :disabled="demoForm.processing"
                @click="fillDemo(account)"
            >
                <div class="mb-2">
                    <Avatar class="h-10 w-10">
                        <AvatarFallback>
                            {{ account.name.charAt(0) }}
                        </AvatarFallback>
                    </Avatar>
                </div>
                <div class="text-center">
                    <div class="font-semibold text-sm">{{ account.name }}</div>
                    <div class="text-xs text-muted-foreground truncate w-full" :title="account.email">
                        {{ account.email }}
                    </div>
                    <Badge class="mt-2 text-[10px] uppercase" :variant="account.role === 'admin' ? 'default' : 'secondary'">
                        {{ account.role }}
                    </Badge>
                </div>
            </button>
        </div>
    </div>
</template>
