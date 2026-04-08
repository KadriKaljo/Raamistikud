<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as cartIndex } from '@/routes/cart';
import { index as mapIndex } from '@/routes/map';
import { index as postsIndex } from '@/routes/posts';
import { index as productsIndex } from '@/routes/products';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, MapPinned, MessageSquare, Shield, ShoppingBag, ShoppingCart } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const page = usePage();

const mainNavItems = computed((): NavItem[] => {
    const cartCount = Number((page.props as { cartCount?: number }).cartCount ?? 0);
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
            title: 'Blogi',
            href: postsIndex(),
            icon: BookOpen,
        },
        {
            title: 'Tooted',
            href: productsIndex(),
            icon: ShoppingBag,
        },
        {
            title: cartCount > 0 ? `Ostukorv (${cartCount})` : 'Ostukorv',
            href: cartIndex(),
            icon: ShoppingCart,
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
