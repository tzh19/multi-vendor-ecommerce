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
import { LayoutDashboard, BookOpen, Folder, LayoutGrid, Users, Box, Store, ShoppingBag, Home, Package   } from 'lucide-vue-next';
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
  { title: 'Dashboard', href: dashboard(), icon: LayoutDashboard },
   ]
    : []),
     ...(isVendor
    ? [
        {
          title: 'Dashboard',
          href: route('vendor.dashboard'),
          icon: LayoutDashboard,
        },
      ]
    : []),
     ...(isAdmin
    ? [
  {
        title: 'User',
        icon: null,
        href: null,
  }, ]
    : []),
  ...(isAdmin
    ? [
        {
          title: 'Management',
          href: route('users.index'),
          icon: Users,
        },
      ]
    : []),
     ...(isAdmin
    ? [
  {
        title: 'Category',
        icon: null,
        href: null,
  }, ]
    : []),
  ...(isAdmin
    ? [
      { title: 'Management', href: route('categories.index'), icon: Folder },
      ]
    : []),
    ...(isAdmin
    ? [
  {
        title: 'Vendor',
        icon: null,
        href: null,
  }, ]
    : []),
    ...(isAdmin
    ? [
  { title: 'Management', href: route('vendors.index'), icon: Store },
   ]
    : []),
...(isAdmin || isVendor
    ? [
  {
        title: 'Product',
        icon: null,
        href: null,
  },]
    : []),
    ...(isAdmin || isVendor
    ? [
  { title: 'Management', href: route('admin.products.index'), icon: Box },]
    : []),
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
    ...(isCustomer
    ? [
        {
          title: 'Home',
          href: route('customer.home.index'),
          icon: Home,
        },
      ]
    : []),
    ...(isCustomer
    ? [
        {
          title: 'Products',
          href: route('products.index'),
          icon: ShoppingBag,
        },
      ]
    : []),
    ...(isCustomer
    ? [
        {
          title: 'My Orders',
          href: route('customer.orders.index'),
          icon: Package,
        },
      ]
    : []),
];

const footerNavItems: NavItem[] = [

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
