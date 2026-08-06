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
    serviceCategories: { id: number; name: string }[];
}>();
</script>

<template>
    <Head title="Tambah Layanan" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <Heading
                title="Tambah Layanan"
                description="Konfigurasi layanan dan nomor antrian"
            />
            <Button variant="outline" as-child>
                <Link :href="index()">Kembali</Link>
            </Button>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Layanan Baru</CardTitle>
            </CardHeader>
            <CardContent>
                <Form
                    v-bind="ServiceController.store.form()"
                    class="space-y-6"
                    v-slot="{ errors, processing }"
                >
                    <div class="grid gap-2">
                        <Label for="name">Nama Layanan</Label>
                        <Input
                            id="name"
                            name="name"
                            type="text"
                            required
                            autocomplete="name"
                            placeholder="e.g. Registrasi"
                        />
                        <InputError :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="service_category_id">Kategori</Label>
                        <Select
                            name="service_category_id"
                            :default-value="serviceCategories[0]?.id"
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
                            required
                            autocomplete="prefix"
                            placeholder="e.g. REG"
                            pattern="[A-Z]+"
                        />
                        <InputError :message="errors.prefix" />
                        <p class="text-xs text-muted-foreground">
                            Masukkan kode prefix (huruf besar, misal: REG, CS, POL)
                        </p>
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
                                default-value="3"
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
                                default-value="1"
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
                            value="1"
                            :default-value="false"
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
                            value="1"
                            :default-value="true"
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
