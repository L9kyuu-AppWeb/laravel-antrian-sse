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
import { create, destroy, edit, index } from '@/routes/admin/display-monitors';

defineProps<{
    displayMonitors: {
        id: number;
        name: string;
        location: string;
        mode: string;
        is_active: boolean;
        services: { id: number; name: string; prefix: string }[];
    }[];
}>();

const deleteForm = useForm({});

const remove = (id: number) => {
    if (!confirm('Apakah Anda yakin ingin menghapus display monitor ini?')) {
        return;
    }

    deleteForm.delete(destroy.url(id));
};
</script>

<template>
    <Head title="Display Monitor" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <Heading
                title="Display Monitor"
                description="Kelola layar tampilan nomor antrian"
            />
            <Button as-child>
                <Link :href="create()">Tambah Display</Link>
            </Button>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Daftar Display Monitor</CardTitle>
            </CardHeader>
            <CardContent>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Nama</TableHead>
                            <TableHead>Lokasi</TableHead>
                            <TableHead>Mode</TableHead>
                            <TableHead>Layanan</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="display in displayMonitors"
                            :key="display.id"
                        >
                            <TableCell>{{ display.id }}</TableCell>
                            <TableCell class="font-medium">{{ display.name }}</TableCell>
                            <TableCell>{{ display.location }}</TableCell>
                            <TableCell>{{ display.mode }}</TableCell>
                            <TableCell>
                                <div class="flex flex-wrap gap-1">
                                    <Badge
                                        v-for="service in display.services"
                                        :key="service.id"
                                        variant="secondary"
                                        class="text-[10px]"
                                    >
                                        {{ service.prefix }}
                                    </Badge>
                                </div>
                            </TableCell>
                            <TableCell>
                                <span
                                    :class="
                                        display.is_active
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    "
                                >
                                    {{ display.is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </TableCell>
                            <TableCell class="text-right">
                                <Button variant="outline" size="sm" as-child class="mr-2">
                                    <Link :href="edit(display.id)">Edit</Link>
                                </Button>
                                <Button
                                    variant="destructive"
                                    size="sm"
                                    :disabled="deleteForm.processing"
                                    @click="remove(display.id)"
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
