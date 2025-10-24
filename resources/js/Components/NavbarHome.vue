<script setup>
import NavLink from "./NavLink.vue";
import PrimaryButtonAdmin from "./PrimaryButtonAdmin.vue";
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
const isOpen = ref(false);
import { Menu, X } from "lucide-vue-next";
function toggleMenu() {
    isOpen.value = !isOpen.value;
}
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

                            <div class="lg:ml-8 md:ml-4">
                                <Link :href="route('login')">
                                    <PrimaryButtonAdmin>
                                        Login</PrimaryButtonAdmin
                                    >
                                </Link>
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
