<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as mapIndex } from '@/routes/map';
import { index as postsIndex } from '@/routes/posts';
import { index as productsIndex } from '@/routes/products';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Clapperboard, CloudSun, Folder, LayoutGrid, MapPinned, MessageSquare, Shield, ShoppingBag, Plane } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const page = usePage();

const mainNavItems = computed((): NavItem[] => {
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
        {
            title: 'Markerite kaart',
            href: mapIndex(),
            icon: MapPinned,
        },
        {
            title: 'Ilmateade',
            href: '/weather',
            icon: CloudSun,
        },
        {
            title: 'Blogi',
            href: postsIndex(),
            icon: BookOpen,
        },
        {
            title: 'E-pood',
            href: productsIndex(),
            icon: ShoppingBag,
        },
        {
            title: 'Reisisihtkohad',
            href: '/travel-destinations',
            icon: Plane,
        },
        {
            title: 'Filmid (välis-API)',
            href: '/travel-destinations/movies',
            icon: Clapperboard,
        },
    ];

    const user = page.props.auth?.user as { is_admin?: boolean } | undefined;
    if (user?.is_admin) {
        items.push(
            {
                title: 'Kasutajad (admin)',
                href: '/admin/users',
                icon: Shield,
            },
            {
                title: 'Kommentaarid (admin)',
                href: '/admin/comments',
                icon: MessageSquare,
            },
        );
    }

    return items;
});

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
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
                        <Link :href="dashboard()">
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
