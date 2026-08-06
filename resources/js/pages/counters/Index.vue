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
import { create, destroy, edit, index } from '@/routes/admin/counters';

defineProps<{
    counters: {
        id: number;
        name: string;
        is_active: boolean;
        service: { id: number; name: string; prefix: string } | null;
    }[];
}>();

const deleteForm = useForm({});

const remove = (id: number) => {
    if (!confirm('Apakah Anda yakin ingin menghapus loket ini?')) {
        return;
    }

    deleteForm.delete(destroy.url(id));
};
</script>

<template>
    <Head title="Loket" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <Heading
                title="Loket"
                description="Kelola loket dan layanan yang dilayani"
            />
            <Button as-child>
                <Link :href="create()">Tambah Loket</Link>
            </Button>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Daftar Loket</CardTitle>
            </CardHeader>
            <CardContent>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Nama Loket</TableHead>
                            <TableHead>Layanan</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="counter in counters"
                            :key="counter.id"
                        >
                            <TableCell>{{ counter.id }}</TableCell>
                            <TableCell class="font-medium">{{ counter.name }}</TableCell>
                            <TableCell>
                                <Badge variant="secondary">
                                    {{ counter.service?.name ?? '-' }}
                                    <template v-if="counter.service">
                                        ({{ counter.service.prefix }})
                                    </template>
                                </Badge>
                            </TableCell>
                            <TableCell>
                                <span
                                    :class="
                                        counter.is_active
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    "
                                >
                                    {{ counter.is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </TableCell>
                            <TableCell class="text-right">
                                <Button variant="outline" size="sm" as-child class="mr-2">
                                    <Link :href="edit(counter.id)">Edit</Link>
                                </Button>
                                <Button
                                    variant="destructive"
                                    size="sm"
                                    :disabled="deleteForm.processing"
                                    @click="remove(counter.id)"
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
