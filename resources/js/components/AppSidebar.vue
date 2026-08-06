<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, ClipboardList, DoorOpen, FolderGit2, LayoutGrid, Tag } from '@lucide/vue';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as countersRoute } from '@/routes/admin/counters';
import { index as serviceCategoriesRoute } from '@/routes/admin/service-categories';
import { index as servicesRoute } from '@/routes/admin/services';
import type { NavItem } from '@/types';

const dashboardRoute = () => dashboard();

const page = usePage();
const isAdmin = computed(() => page.props.auth.user.role === 'admin');

const mainNavItems = computed<NavItem[]>(() => [
    {
        title: 'Dashboard',
        href: dashboardRoute(),
        icon: LayoutGrid,
    },
    ...(isAdmin.value
        ? [
              {
                  title: 'Service Categories',
                  href: serviceCategoriesRoute(),
                  icon: Tag,
              },
              {
                  title: 'Layanan',
                  href: servicesRoute(),
                  icon: ClipboardList,
              },
              {
                  title: 'Loket',
                  href: countersRoute(),
                  icon: DoorOpen,
              },
          ]
        : []),
]);

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboardRoute()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>