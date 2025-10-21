<script setup>
import { Link } from "@inertiajs/vue3";
</script>
<template>
    <footer class="bg-green-800 text-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="grid grid-cols-1 sm:grid-cols-3 gap-8 border-b border-green-700 pb-6"
            >
                <div>
                    <h2 class="text-xl font-bold tracking-wide text-white">
                        MANDAT GRESIK
                    </h2>
                    <p class="text-sm text-gray-300 mt-2">
                        Manajemen Data Terpadu Kemenag Gresik
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-3 text-white">
                        Navigasi
                    </h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>
                            <Link
                                :href="route('Beranda')"
                                class="hover:text-white transition"
                                >Beranda</Link
                            >
                        </li>
                        <li>
                            <Link
                                :href="route('Beranda')"
                                class="hover:text-white transition"
                                >Portal Data</Link
                            >
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-3 text-white">
                        Kontak
                    </h3>
                    <ul class="text-gray-300 text-sm space-y-1">
                        <li>Kantor Kemenag Kabupaten Gresik</li>
                        <li>Jl. Dr. Wahidin Sudiro Husodo No. 245, Gresik</li>
                        <li>
                            Email:
                            <a
                                href="mailto:kemenag.gresik@kemenag.go.id"
                                class="text-gray-100 hover:text-white"
                                >kemenag.gresik@kemenag.go.id</a
                            >
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="flex flex-col sm:flex-row justify-between text-center items-center mt-6 text-sm text-gray-400"
            >
                <p>
                    © {{ new Date().getFullYear() }} Kementerian Agama Kabupaten
                    Gresik. Semua hak dilindungi.
                </p>
            </div>
        </div>
    </footer>
</template>
