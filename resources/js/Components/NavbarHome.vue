<script setup>
import NavLink from "./NavLink.vue";
import PrimaryButtonAdmin from "./PrimaryButtonAdmin.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { Menu, X } from "lucide-vue-next";

const isOpen = ref(false);
function toggleMenu() {
    isOpen.value = !isOpen.value;
}

const page = usePage();
const user = page.props?.auth?.user?.name;
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <!-- navbar atas -->
        <nav
            class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <!-- Logo -->
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-gray-700 tracking-wide">
                            MANDAT GRESIK
                        </span>
                    </div>

                    <!-- Menu Desktop -->
                    <div class="hidden md:flex items-center gap-8">
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

                        <!-- User / Login -->
                        <div class="ml-4">
                            <Link :href="route('login')" v-if="!user">
                                <PrimaryButtonAdmin>Login</PrimaryButtonAdmin>
                            </Link>

                            <Dropdown v-else align="right" width="48">
                                <template #trigger>
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium bg-green-800 text-white hover:bg-green-700 transition"
                                    >
                                        {{ user }}
                                        <svg
                                            class="ms-2 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">
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

                    <!-- Mobile burger -->
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

            <!-- Mobile Menu -->
            <div
                v-show="isOpen"
                class="md:hidden bg-white border-t border-gray-100"
            >
                <div class="px-4 py-3 space-y-3">
                    <!-- Menu Row -->
                    <div
                        class="flex flex-col gap-2 text-gray-700 text-[15px] font-medium"
                    >
                        <NavLink
                            :href="route('Beranda')"
                            :active="route().current('Beranda')"
                        >
                            Beranda
                        </NavLink>

                        <NavLink
                            :href="route('PortalData')"
                            :active="route().current('PortalData')"
                        >
                            Portal Data
                        </NavLink>

                        <NavLink v-if="user" :href="route('dashboard')">
                            Dashboard Admin
                        </NavLink>
                    </div>

                    <!-- Jika BELUM LOGIN -->
                    <div v-if="!user" class="flex">
                        <Link :href="route('login')" class="w-full">
                            <PrimaryButtonAdmin class="w-full text-center">
                                Login
                            </PrimaryButtonAdmin>
                        </Link>
                    </div>

                    <!-- Jika SUDAH LOGIN -->
                    <div v-else class="flex flex-col gap-2">
                        <Link
                            :href="route('profile.edit')"
                            class="py-2 px-3 bg-green-800 text-white rounded-md text-center"
                        >
                            Profile
                        </Link>

                        <form
                            method="POST"
                            :action="route('logout')"
                            class="w-full"
                        >
                            <button
                                type="submit"
                                class="w-full py-2 px-3 bg-green-800 text-white rounded-md text-center"
                            >
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Konten -->
        <div class="mt-16 md:ml-8 ml-0 px-3 md:px-0">
            <main :key="$page.url">
                <slot />
            </main>
        </div>
    </div>
</template>
