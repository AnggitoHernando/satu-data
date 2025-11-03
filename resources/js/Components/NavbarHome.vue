<script setup>
import NavLink from "./NavLink.vue";
import PrimaryButtonAdmin from "./PrimaryButtonAdmin.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
const isOpen = ref(false);
import { Menu, X } from "lucide-vue-next";
function toggleMenu() {
    isOpen.value = !isOpen.value;
}
const page = usePage();
const user = page.props?.auth?.user?.name;
</script>
<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <!-- navbar atas -->
            <nav
                class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100 border-none"
            >
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <div class="flex shrink-0 items-center">
                                <span class="ml-2">MANDAT GRESIK</span>
                            </div>
                            <!-- Mobile -->
                        </div>
                        <div
                            id="nav-menu"
                            class="hidden md:flex items-center gap-8"
                        >
                            <NavLink
                                :href="route('Beranda')"
                                :active="route().current('Beranda')"
                            >
                                Beranda
                            </NavLink>
                            <NavLink
                                :href="route('PortalData')"
                                :active="
                                    route().current()?.startsWith('PortalData')
                                "
                            >
                                Portal Data
                            </NavLink>
                            <NavLink v-if="user" :href="route('dashboard')">
                                Dashboard Admin
                            </NavLink>

                            <div class="lg:ml-8 md:ml-4">
                                <Link :href="route('login')" v-if="!user">
                                    <PrimaryButtonAdmin>
                                        Login</PrimaryButtonAdmin
                                    >
                                </Link>
                                <Dropdown align="right" width="48" v-else>
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-800 hover:text-white-400 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ user }}

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Mobile Menu -->
                        <div class="md:hidden flex items-center">
                            <button
                                @click="toggleMenu"
                                class="p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <component
                                    :is="isOpen ? X : Menu"
                                    class="w-6 h-6"
                                />
                            </button>
                        </div>
                    </div>
                </div>
                <div
                    v-show="isOpen"
                    class="md:hidden bg-white border-t border-gray-100"
                >
                    <div class="px-2 pt-2 pb-3 space-y-1">
                        <NavLink
                            :href="route('Beranda')"
                            :active="route().current('Beranda')"
                            >Beranda</NavLink
                        >
                        <NavLink
                            :href="route('PortalData')"
                            :active="route().current('PortalData')"
                            >Portal Data</NavLink
                        >
                        <Link :href="route('login')">
                            <PrimaryButtonAdmin class="w-full text-center"
                                >Login</PrimaryButtonAdmin
                            >
                        </Link>
                    </div>
                </div>
            </nav>
            <div class="mt-16 ml-8">
                <main :key="$page.url">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
