<script setup>
import SidebarLink from "./SidebarLink.vue";
import {
    LayoutDashboard,
    Database,
    Users,
    MessageSquare,
    ChartArea,
} from "lucide-vue-next";
import { usePage } from "@inertiajs/vue3";
import SidebarItem from "@/Components/SidebarItem.vue";

const page = usePage();
const roleUser = page.props.auth.user.role ?? null;
const allowedUser = ["super-admin", "admin"];
const menuStatistik = [
    { label: "Kategori Data", route: "admin.statistik.kategori-data" },
    { label: "Isi Statistik", route: "admin.statistik.isi-statistik" },
];
</script>
<template>
    <aside
        id="sidebar-multi-level-sidebar"
        class="fixed top-0 dark:top-0 left-0 z-40 w-64 h-screen mt-16 transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar"
    >
        <div
            class="h-full px-3 py-4 overflow-y-auto bg-green-800 dark:bg-green-900"
        >
            <ul class="space-y-2 font-medium">
                <li>
                    <SidebarLink
                        :href="route('dashboard')"
                        :logo="LayoutDashboard"
                        value="Dashboard"
                        :active="$page.url === '/dashboard'"
                    >
                    </SidebarLink>
                </li>
                <li>
                    <SidebarLink
                        :href="route('jenis_data.show')"
                        :logo="Database"
                        value="Jenis Data"
                        :active="$page.url === '/jenis-data'"
                    ></SidebarLink>
                </li>
                <li v-if="allowedUser.includes(roleUser)">
                    <SidebarLink
                        :href="route('admin.kritik.index')"
                        :logo="MessageSquare"
                        value="Kritik dan Saran"
                        :active="$page.url === '/kritik-saran'"
                    ></SidebarLink>
                </li>
                <li v-if="allowedUser.includes(roleUser)">
                    <SidebarLink
                        :href="route('users.show')"
                        :logo="Users"
                        value="Users"
                        :active="$page.url === '/users'"
                    ></SidebarLink>
                </li>
                <li>
                    <SidebarItem
                        title="Data Statistik"
                        :icon="ChartArea"
                        :items="menuStatistik"
                    />
                </li>
            </ul>
        </div>
    </aside>
</template>
