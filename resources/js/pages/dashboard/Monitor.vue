<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { monitor } from '@/routes/dashboard';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Monitor',
                href: monitor(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Monitor" />

    <div class="flex h-full flex-1 flex-col items-center justify-center gap-4 rounded-xl p-4">
        <h1 class="text-xl font-semibold">SSE Display Monitor</h1>
        <p class="text-sm text-muted-foreground">Layar monitor antrian real-time (SSE menyusul).</p>
        <div class="relative aspect-video w-full max-w-2xl overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <PlaceholderPattern />
        </div>
    </div>
</template>