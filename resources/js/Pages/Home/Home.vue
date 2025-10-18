<script setup>
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Search } from "lucide-vue-next";
</script>
<template>
    <Head title="Home" />
    <HomeLayout>
        <div class="min-h-screen">
            <!-- Hero / Banner -->
            <section class="max-w-7xl mx-auto px-6 py-16 text-center">
                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-800">
                    <span class="text-green-800">SIDAK GRESIK</span><br />
                    Sistem Informasi Data Kemenag Gresik
                </h1>
                <p class="mt-4 text-gray-600 text-lg max-w-2xl mx-auto">
                    "Satu Portal Data Terpadu Kementerian Agama Kabupaten
                    Gresik."
                </p>
                <div class="mt-8">
                    <div class="relative max-w-md mx-auto">
                        <!-- Icon dari Lucide -->
                        <Search
                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"
                        />

                        <!-- Input search -->
                        <input
                            type="text"
                            v-model="search"
                            placeholder="Cari data..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-700 focus:border-green-700 focus:outline-none"
                        />
                    </div>
                </div>
            </section>
        </div>
    </HomeLayout>
</template>
