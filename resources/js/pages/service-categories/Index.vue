<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
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
import { create, destroy, edit, index } from '@/routes/admin/service-categories';

defineProps<{
    serviceCategories: {
        id: number;
        name: string;
        description: string | null;
        is_active: boolean;
        created_at: string;
    }[];
}>();

const deleteForm = useForm({});

const remove = (id: number) => {
    if (!confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
        return;
    }

    deleteForm.delete(destroy.url(id));
};
</script>

<template>
    <Head title="Service Categories" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div class="flex items-center justify-between">
            <Heading
                title="Service Categories"
                description="Kelola kategori layanan"
            />
            <Button as-child>
                <Link :href="create()">Add Category</Link>
            </Button>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Daftar Kategori</CardTitle>
            </CardHeader>
            <CardContent>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Description</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="category in serviceCategories"
                            :key="category.id"
                        >
                            <TableCell>{{ category.id }}</TableCell>
                            <TableCell class="font-medium">{{ category.name }}</TableCell>
                            <TableCell>{{ category.description || '-' }}</TableCell>
                            <TableCell>
                                <span
                                    :class="
                                        category.is_active
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    "
                                >
                                    {{ category.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </TableCell>
                            <TableCell class="text-right">
                                <Button variant="outline" size="sm" as-child class="mr-2">
                                    <Link :href="edit(category.id)">Edit</Link>
                                </Button>
                                <Button
                                    variant="destructive"
                                    size="sm"
                                    :disabled="deleteForm.processing"
                                    @click="remove(category.id)"
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