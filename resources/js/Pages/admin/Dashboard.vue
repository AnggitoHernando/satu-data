<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";
import DashboardPieChart from "@/Components/DashboardPieChart.vue";

const page = usePage();
const result = page.props;
const jumlah_data_all = result.jumlah_data_all;
const persentase_all = result.persentase_all;
const persentase_per_seksi = result.persentase_per_seksi;
const labels = persentase_all.map((item) => item.nama_seksi);
const data = persentase_all.map((item) => item.persentase);
// console.log(labels);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <!-- ===== GRID ATAS ===== -->
                    <div
                        class="p-4 sm:p-6 text-gray-900 grid grid-cols-1 md:grid-cols-2 gap-6"
                    >
                        <!-- Box 1 -->
                        <div
                            class="p-4 sm:p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100 flex flex-col space-y-5"
                        >
                            <div
                                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                            >
                                <Link
                                    :href="route('Beranda')"
                                    class="w-full sm:w-auto"
                                >
                                    <PrimaryButtonAdmin
                                        class="w-full sm:w-auto"
                                    >
                                        Beranda
                                    </PrimaryButtonAdmin>
                                </Link>

                                <div
                                    class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 text-center shadow-inner w-full sm:w-60"
                                >
                                    <p
                                        class="text-xs sm:text-sm text-gray-500 mb-1"
                                    >
                                        Jumlah Data Keseluruhan
                                    </p>
                                    <p
                                        class="text-2xl sm:text-3xl font-bold text-indigo-600"
                                    >
                                        {{ jumlah_data_all }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <h3
                                    class="text-base sm:text-lg font-semibold mb-2 text-gray-800"
                                >
                                    Selamat Datang
                                </h3>
                                <p
                                    class="text-gray-600 mb-4 leading-relaxed text-sm sm:text-base"
                                >
                                    Halo {{ $page.props.auth.user.name }}, kamu
                                    sudah login!
                                </p>
                            </div>
                        </div>

                        <!-- Chart utama -->
                        <div class="w-full">
                            <DashboardPieChart
                                title="Persentase Semua Seksi"
                                :labels="labels"
                                :data="data"
                            />
                        </div>
                    </div>

                    <!-- ===== GRID BAWAH ===== -->
                    <div
                        class="p-4 sm:p-6 bg-gray-50 rounded-2xl shadow-sm hover:shadow-md transition-all duration-200"
                    >
                        <h3
                            class="text-base sm:text-lg font-semibold text-gray-800 mb-6 text-center"
                        >
                            Persentase Data per Seksi
                        </h3>

                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6"
                        >
                            <div
                                v-for="(item, i) in persentase_per_seksi"
                                :key="i"
                                class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col items-center justify-center"
                            >
                                <DashboardPieChart
                                    :title="item.nama_seksi"
                                    :labels="['Publik', 'Private']"
                                    :data="[
                                        item.persentase_publik,
                                        item.persentase_private,
                                    ]"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
