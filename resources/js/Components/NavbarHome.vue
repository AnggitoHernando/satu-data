<script setup>
import NavLink from "./NavLink.vue";
import PrimaryButtonAdmin from "./PrimaryButtonAdmin.vue";
import { Link } from "@inertiajs/vue3";
</script>
<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <!-- navbar atas -->
            <nav
                class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100 border-none"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <span class="ml-2">SIDAK Gresik</span>
                            </div>
                        </div>
                        <div id="nav-menu" class="flex items-center gap-8">
                            <NavLink
                                :href="route('Home')"
                                :active="route().current('Home')"
                            >
                                Home
                            </NavLink>
                            <NavLink
                                :href="route('Home')"
                                :active="route().current('PortalData')"
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
