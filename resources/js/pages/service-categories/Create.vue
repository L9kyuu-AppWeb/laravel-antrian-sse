<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import ServiceCategoryController from '@/actions/App/Http/Controllers/Admin/ServiceCategoryController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index } from '@/routes/admin/service-categories';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Service Categories', href: index() },
            { title: 'Create' },
        ],
    },
});
</script>

<template>
    <Head title="Create Service Category" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <Heading
            title="Create Service Category"
            description="Tambahkan kategori layanan baru"
        />

        <Card>
            <CardHeader>
                <CardTitle>New Category</CardTitle>
            </CardHeader>
            <CardContent>
                <Form
                    v-bind="ServiceCategoryController.store.form()"
                    class="space-y-6"
                    v-slot="{ errors, processing }"
                >
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            name="name"
                            type="text"
                            required
                            autocomplete="name"
                            placeholder="e.g. Administrasi"
                        />
                        <InputError :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="description">Description</Label>
                        <Input
                            id="description"
                            name="description"
                            type="text"
                            placeholder="Deskripsi kategori (opsional)"
                        />
                        <InputError :message="errors.description" />
                    </div>

                    <div class="flex items-center space-x-3">
                        <Checkbox id="is_active" name="is_active" value="1" default-checked />
                        <Label for="is_active">Active</Label>
                    </div>
                    <InputError :message="errors.is_active" />

                    <div class="flex items-center gap-4">
                        <Button :disabled="processing">Save</Button>
                        <Button variant="outline" as-child>
                            <Link :href="index()">Cancel</Link>
                        </Button>
                    </div>
                </Form>
            </CardContent>
        </Card>
    </div>
</template>
