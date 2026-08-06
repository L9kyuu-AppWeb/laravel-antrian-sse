<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { create, destroy, edit, index } from '@/routes/admin/services';

defineProps<{
    services: {
        id: number;
        name: string;
        prefix: string;
        digit_number: number;
        start_number: number;
        reset_number: boolean;
        is_active: boolean;
        service_category: { id: number; name: string } | null;
    }[];
}>();

const deleteForm = useForm({});

const remove = (id: number) => {
    if (!confirm('Apakah Anda yakin ingin menghapus layanan ini?')) {
        return;
    }

    deleteForm.delete(destroy.url(id));
};
</script>

<template>
    <Head title="Layanan" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <Heading
                title="Layanan"
                description="Kelola layanan dan konfigurasi nomor antrian"
            />
            <Button as-child>
                <Link :href="create()">Tambah Layanan</Link>
            </Button>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Daftar Layanan</CardTitle>
            </CardHeader>
            <CardContent>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Kategori</TableHead>
                            <TableHead>Prefix</TableHead>
                            <TableHead>Digit</TableHead>
                            <TableHead>Nomor Awal</TableHead>
                            <TableHead>Reset</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="service in services"
                            :key="service.id"
                        >
                            <TableCell>{{ service.id }}</TableCell>
                            <TableCell class="font-medium">{{ service.name }}</TableCell>
                            <TableCell>{{ service.service_category?.name ?? '-' }}</TableCell>
                            <TableCell>
                                <Badge variant="secondary">{{ service.prefix }}</Badge>
                            </TableCell>
                            <TableCell>{{ service.digit_number }}</TableCell>
                            <TableCell>{{ service.start_number }}</TableCell>
                            <TableCell>{{ service.reset_number ? 'Ya' : 'Tidak' }}</TableCell>
                            <TableCell>
                                <span
                                    :class="
                                        service.is_active
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    "
                                >
                                    {{ service.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </TableCell>
                            <TableCell class="text-right">
                                <Button variant="outline" size="sm" as-child class="mr-2">
                                    <Link :href="edit(service.id)">Edit</Link>
                                </Button>
                                <Button
                                    variant="destructive"
                                    size="sm"
                                    :disabled="deleteForm.processing"
                                    @click="remove(service.id)"
                                >
                                    Delete
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </CardContent>
        </Card>
    </div>
</template>
