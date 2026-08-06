<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import ServiceController from '@/actions/App/Http/Controllers/Admin/ServiceController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { index } from '@/routes/admin/services';

defineProps<{
    service: {
        id: number;
        name: string;
        prefix: string;
        digit_number: number;
        start_number: number;
        reset_number: boolean;
        is_active: boolean;
        service_category_id: number;
    };
    serviceCategories: { id: number; name: string }[];
}>();
</script>

<template>
    <Head title="Edit Layanan" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <Heading
                title="Edit Layanan"
                description="Perbarui konfigurasi layanan"
            />
            <Button variant="outline" as-child>
                <Link :href="index()">Kembali</Link>
            </Button>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Edit Layanan</CardTitle>
            </CardHeader>
            <CardContent>
                <Form
                    v-bind="ServiceController.update.form(service)"
                    class="space-y-6"
                    v-slot="{ errors, processing }"
                >
                    <div class="grid gap-2">
                        <Label for="name">Nama Layanan</Label>
                        <Input
                            id="name"
                            name="name"
                            type="text"
                            :default-value="service.name"
                            required
                            autocomplete="name"
                        />
                        <InputError :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="service_category_id">Kategori</Label>
                        <Select
                            name="service_category_id"
                            :default-value="String(service.service_category_id)"
                            required
                        >
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Pilih kategori" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="category in serviceCategories"
                                    :key="category.id"
                                    :value="String(category.id)"
                                >
                                    {{ category.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="errors.service_category_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="prefix">Prefix (Kode)</Label>
                        <Input
                            id="prefix"
                            name="prefix"
                            type="text"
                            :default-value="service.prefix"
                            required
                            autocomplete="prefix"
                        />
                        <InputError :message="errors.prefix" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label for="digit_number">Jumlah Digit</Label>
                            <Input
                                id="digit_number"
                                name="digit_number"
                                type="number"
                                min="1"
                                max="10"
                                :default-value="service.digit_number"
                                required
                            />
                            <InputError :message="errors.digit_number" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="start_number">Nomor Awal</Label>
                            <Input
                                id="start_number"
                                name="start_number"
                                type="number"
                                min="1"
                                :default-value="service.start_number"
                                required
                            />
                            <InputError :message="errors.start_number" />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="reset_number">Reset Nomor</Label>
                        <div class="flex items-center space-x-3 rounded-md border p-3">
                            <Checkbox
                                id="reset_number"
                                name="reset_number"
                                :default-checked="service.reset_number"
                                value="1"
                            />
                            <Label for="reset_number">Reset nomor setiap hari</Label>
                        </div>
                        <InputError :message="errors.reset_number" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="is_active">Status</Label>
                        <div class="flex items-center space-x-3 rounded-md border p-3">
                            <Checkbox
                                id="is_active"
                                name="is_active"
                                :default-checked="service.is_active"
                                value="1"
                            />
                            <Label for="is_active">Aktif (tampil di antrean)</Label>
                        </div>
                        <InputError :message="errors.is_active" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="processing">Simpan</Button>
                        <Button type="button" variant="outline" as-child>
                            <Link :href="index()">Batal</Link>
                        </Button>
                    </div>
                </Form>
            </CardContent>
        </Card>
    </div>
</template>
