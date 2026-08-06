<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import DisplayMonitorController from '@/actions/App/Http/Controllers/Admin/DisplayMonitorController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { index } from '@/routes/admin/display-monitors';

defineProps<{
    services: { id: number; name: string; prefix: string }[];
}>();
</script>

<template>
    <Head title="Tambah Display Monitor" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <Heading
                title="Tambah Display Monitor"
                description="Konfigurasi layar tampilan baru"
            />
            <Button variant="outline" as-child>
                <Link :href="index()">Kembali</Link>
            </Button>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Display Monitor Baru</CardTitle>
            </CardHeader>
            <CardContent>
                <Form
                    v-bind="DisplayMonitorController.store.form()"
                    class="space-y-6"
                    v-slot="{ errors, processing }"
                >
                    <div class="grid gap-2">
                        <Label for="name">Nama Display</Label>
                        <Input
                            id="name"
                            name="name"
                            type="text"
                            required
                            autocomplete="name"
                            placeholder="e.g. TV Lobby"
                        />
                        <InputError :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="location">Lokasi Display</Label>
                        <Input
                            id="location"
                            name="location"
                            type="text"
                            required
                            autocomplete="location"
                            placeholder="e.g. Lobby Utama"
                        />
                        <InputError :message="errors.location" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="mode">Mode Display</Label>
                        <Select
                            name="mode"
                            :default-value="'queue_calling'"
                            required
                        >
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Pilih mode" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="queue_calling">
                                    Panggilan Antrian
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="errors.mode" />
                    </div>

                    <div class="grid gap-2">
                        <Label>Layanan yang Ditampilkan</Label>
                        <div class="grid gap-2 rounded-md border p-4">
                            <div class="flex flex-col gap-3">
                                <div
                                    v-for="service in services"
                                    :key="service.id"
                                    class="flex items-center space-x-3"
                                >
                                    <Checkbox
                                        :id="`service-${service.id}`"
                                        name="services[]"
                                        :value="String(service.id)"
                                    />
                                    <Label :for="`service-${service.id}`" class="font-normal">
                                        {{ service.name }}
                                        <span class="text-muted-foreground">
                                            ({{ service.prefix }})
                                        </span>
                                    </Label>
                                </div>
                            </div>
                        </div>
                        <InputError :message="errors.services" />
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
                            <Label for="is_active">Aktif (tampil di monitor)</Label>
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