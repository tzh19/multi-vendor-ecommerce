<script setup lang="ts">
import { computed } from 'vue';
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
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Users, Box, Store  } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { route } from 'ziggy-js';

const { props } = usePage<PageProps>();

const role = props?.auth?.user?.role ?? 'guest';

const isAdmin = role === 'admin';
const isVendor = role === 'vendor';
const isCustomer = role === 'customer';

const mainNavItems: NavItem[] = [
  ...(isAdmin
    ? [
  { title: 'Dashboard', href: dashboard(), icon: LayoutGrid },
   ]
    : []),
     ...(isVendor
    ? [
        {
          title: 'Dashboard',
          href: route('vendor.dashboard'),
          icon: LayoutGrid,
        },
      ]
    : []),
  ...(isAdmin
    ? [
        {
          title: 'User',
          href: route('users.index'),
          icon: Users,
        },
      ]
    : []),
  { title: 'Category', href: route('categories.index'), icon: Folder },
  {
        title: 'Vendor',
        icon: null,
        href: null,
  },
  { title: 'Management', href: route('vendors.index'), icon: Store },

  {
        title: 'Product',
        icon: null,
        href: null,
  },
  { title: 'Management', href: route('admin.products.index'), icon: Box },
  ...(isVendor
    ? [
  {
        title: 'Order',
        icon: null,
        href: null,
  },
   ]
    : []),
   ...(isVendor
    ? [
        {
          title: 'Management',
          href: route('vendor.orders.index'),
          icon: LayoutGrid,
        },
      ]
    : []),
];

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
