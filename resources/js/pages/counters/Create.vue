<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import CounterController from '@/actions/App/Http/Controllers/Admin/CounterController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { index } from '@/routes/admin/counters';

defineProps<{
    services: { id: number; name: string }[];
}>();
</script>

<template>
    <Head title="Tambah Loket" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <Heading
                title="Tambah Loket"
                description="Konfigurasi loket baru"
            />
            <Button variant="outline" as-child>
                <Link :href="index()">Kembali</Link>
            </Button>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Loket Baru</CardTitle>
            </CardHeader>
            <CardContent>
                <Form
                    v-bind="CounterController.store.form()"
                    class="space-y-6"
                    v-slot="{ errors, processing }"
                >
                    <div class="grid gap-2">
                        <Label for="name">Nama Loket</Label>
                        <Input
                            id="name"
                            name="name"
                            type="text"
                            required
                            autocomplete="name"
                            placeholder="e.g. Loket 1"
                        />
                        <InputError :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="service_id">Layanan</Label>
                        <Select
                            name="service_id"
                            :default-value="String(services[0]?.id ?? '')"
                            required
                        >
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Pilih layanan" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="service in services"
                                    :key="service.id"
                                    :value="String(service.id)"
                                >
                                    {{ service.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="errors.service_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="is_active">Status</Label>
                        <div class="flex items-center space-x-3 rounded-md border p-3">
                            <Checkbox
                                id="is_active"
                                name="is_active"
                                value="1"
                                default-checked
                            />
                            <Label for="is_active">Aktif (terlihat di antrean)</Label>
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
