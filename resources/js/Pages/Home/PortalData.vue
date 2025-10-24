<script setup>
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { Search, Database } from "lucide-vue-next";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref, computed } from "vue";

const data_controller = usePage();
const list_seksi = computed(() => data_controller.props?.list_seksi ?? []);
const list_data = computed(() => data_controller.props?.list_data?.data ?? []);

const search = ref("");
const selectedFilter = ref("semua");
const isFilterOpen = ref(false);

const list_slug_seksi = ref([
    { nama_seksi: "Sub Bagian Tata Usaha", slug: "tata-usaha" },
    { nama_seksi: "Pendidikan Madrasah", slug: "pendidikan-madrasah" },
    { nama_seksi: "Bimbingan Masyarakat Islam", slug: "bimas-islam" },
    { nama_seksi: "Penyelenggara Haji dan Umroh", slug: "phu" },
    { nama_seksi: "Penyelenggara Zakat dan Wakaf", slug: "penzawa" },
    { nama_seksi: "Pendidikan Agama Islam", slug: "pais" },
    {
        nama_seksi: "Pendidikan Diniyah dan Pondok Pesantren",
        slug: "pd-pontren",
    },
    {
        nama_seksi: "semua",
        slug: "semua",
    },
]);

function handleSearch() {
    const params = {};

    if (search.value.trim() !== "") {
        params.q = search.value.trim();
    }
    if (selectedFilter.value) {
        const selected = list_slug_seksi.value.find(
            (f) => f.nama_seksi === selectedFilter.value
        );
        if (selected) params.seksi = selected.slug;
    }

    router.visit(route("PortalData.search"), {
        method: "get",
        data: params,
        preserveState: true,
        replace: true,
    });
}

function selectFilter(filter) {
    selectedFilter.value = filter;
    handleSearch();
}
</script>
<template>
    <Head title="Portal Data" />
    <HomeLayout>
        <div class="min-h-screen">
            <section
                class="max-w-7xl mx-auto px-4 sm:px-6 py-2 sm:py-4 text-center"
            >
                <h1
                    class="text-xl sm:text-3xl font-extrabold text-gray-800 leading-tight"
                >
                    <span class="text-green-800 block">Portal Data</span>
                </h1>
                <p
                    class="mt-1 sm:mt-2 text-gray-600 text-base sm:text-lg max-w-2xl mx-auto px-2"
                >
                    "Layanan informasi dan fitur satu data untuk meningkatkan
                    efektifitas pengambilan kebijakan dengan berdasarkan data
                    yang dapat dilihat oleh seluruh masyarakat, bersumber pada
                    Kantor Kementerian Agama Kabupaten Gresik"
                </p>
                <div class="mt-6 sm:mt-8">
                    <div class="relative max-w-md mx-auto">
                        <Search
                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"
                        />
                        <input
                            type="text"
                            v-model="search"
                            placeholder="Cari data..."
                            @keyup.enter="handleSearch"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-700 focus:border-green-700 focus:outline-none text-sm sm:text-base"
                        />
                        <PrimaryButton
                            @click="handleSearch"
                            class="absolute right-0 top-1/2 -translate-y-1/2"
                        >
                            Cari
                        </PrimaryButton>
                    </div>
                    <div class="mt-4 w-full overflow-x-auto scrollbar-hide">
                        <div class="flex gap-2 px-2 pb-2">
                            <button
                                @click="selectFilter('semua')"
                                class="px-4 py-2 text-sm font-medium rounded-full border border-gray-300 whitespace-nowrap transition-all duration-150"
                                :class="
                                    selectedFilter === 'semua'
                                        ? 'bg-green-700 text-white border-green-700 shadow-sm'
                                        : 'bg-white text-gray-600 hover:text-green-700 hover:border-green-700'
                                "
                            >
                                Semua
                            </button>
                            <button
                                v-for="f in list_seksi"
                                :key="f.nama_seksi"
                                @click="selectFilter(f.nama_seksi)"
                                class="px-4 py-2 text-sm font-medium rounded-full border border-gray-300 whitespace-nowrap transition-all duration-150"
                                :class="
                                    selectedFilter === f.nama_seksi
                                        ? 'bg-green-700 text-white border-green-700 shadow-sm'
                                        : 'bg-white text-gray-600 hover:text-green-700 hover:border-green-700'
                                "
                            >
                                {{ f.nama_seksi }}
                            </button>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Dibawah Filter  -->
            <section class="max-w-7xl">
                <p class="text-gray-500 text-sm sm:text-base mb-6 text-center">
                    Ditemukan
                    <span class="text-green-700 font-semibold">10 data</span>
                    data untuk
                    <span class="font-semibold">"AA"</span>
                    <span v-if="selectedFilter !== 'semua'">
                        dalam kategori
                        <span class="text-green-700 font-medium"> semua </span>
                    </span>
                </p>
                <div
                    v-if="list_data.length > 0"
                    class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8"
                >
                    <div
                        v-for="data in list_data"
                        :key="data.id"
                        class="group bg-white/90 backdrop-blur-sm border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-5 cursor-pointer hover:-translate-y-1"
                    >
                        <div class="flex items-start gap-3 mb-3">
                            <span
                                class="absolute top-3 right-3 text-xs font-semibold px-2 py-1 rounded-md uppercase bg-green-50 text-green-700 border border-green-200"
                            >
                                {{ data.extension_file }}
                            </span>
                            <div class="p-2 bg-green-50 rounded-lg">
                                <Database class="w-5 h-5 text-green-700" />
                            </div>
                            <div>
                                <h3
                                    class="capitalize font-semibold text-lg group-hover:text-green-700 transition"
                                >
                                    {{ data.judul_data }}
                                </h3>
                                <p
                                    class="text-xs uppercase text-gray-400 font-medium mt-1"
                                >
                                    {{ data.seksi?.nama_seksi }} ·
                                    <span class="normal-case text-gray-400">{{
                                        data.tahun
                                    }}</span>
                                </p>
                            </div>
                        </div>

                        <p
                            class="text-sm text-gray-600 line-clamp-3 leading-relaxed"
                        >
                            {{ data.deskripsi }}
                        </p>
                        <div class="mt-4 flex justify-end items-center">
                            <button
                                class="text-sm font-medium text-green-700 hover:text-green-800"
                            >
                                Lihat Detail →
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tidak ada hasil -->
                <div v-else class="text-center text-gray-400 mt-12">
                    <p class="text-lg font-medium">
                        Tidak ada hasil ditemukan.
                    </p>
                    <p class="text-sm text-gray-500">
                        Coba kata kunci lain atau ubah filter pencarian.
                    </p>
                </div>
            </section>
        </div>
    </HomeLayout>
</template>
