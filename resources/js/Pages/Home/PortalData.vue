<script setup>
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { Search, Database } from "lucide-vue-next";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref, computed, onMounted, onUnmounted, watch, watchEffect } from "vue";
import ModalHeadnessUI from "@/Components/ModalHeadnessUI.vue";

const data_controller = usePage();
const list_seksi = computed(() => data_controller.props?.list_seksi ?? []);
const list_data = computed(() => data_controller.props?.list_data?.data ?? []);
const nextPageUrl = ref(data_controller.props.list_data.next_page_url);
const loading = ref(false);
const observer = ref(null);
const sentinel = ref(null);
const openModal = ref(false);
const dataDetail = ref([]);
const fieldsDetail = ref([]);
const loadingDetail = ref(false);

const search = ref(data_controller.props.filters.q || "");
const selectedFilter = ref(data_controller.props.filters.seksi || "semua");
const hasSearched = ref(false);
const total = ref(0);
const page = ref(1);
const perPage = ref(10);
const sortBy = ref("jenis_data.id");
const sortDir = ref("desc");
const selectedId = ref(null);

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
const openDetail = async (data) => {
    selectedId.value = data.id;
    page.value = 1;
    openModal.value = true;
    fetch_detail();
};

const fetch_detail = async () => {
    if (!selectedId.value) return;
    loadingDetail.value = true;
    try {
        const res = await axios.get("/api/api-detail-data", {
            params: {
                id: selectedId.value,
                search: data_controller.props.filters.q,
                page: page.value,
                perPage: perPage.value,
                sortBy: sortBy.value,
                sortDir: sortDir.value,
            },
        });
        fieldsDetail.value = res.data.fields;
        dataDetail.value = [...res.data.records.data.map((r) => r.data_json)];
        loadingDetail.value = true;
        total.value = res.data.records.total;
    } catch (error) {
        console.error(error);
        Swal.fire("Error", "Gagal load detail data", "error");
    } finally {
        loadingDetail.value = false;
    }
};

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
        onSuccess: (res) => {
            if (
                data_controller.props.filters.q === "" ||
                data_controller.props.filters.q === null
            ) {
                hasSearched.value = false;
            } else {
                hasSearched.value = true;
            }
            nextPageUrl.value = res.props.list_data.next_page_url;
        },
    });
}

function selectFilter(filter) {
    selectedFilter.value = filter;
    handleSearch();
}

async function loadMore() {
    if (!nextPageUrl.value || loading.value) return;
    loading.value = true;
    try {
        const res = await axios.get(nextPageUrl.value);
        res.data.data.forEach((newItem) => {
            const index = list_data.value.findIndex(
                (item) => item.id === newItem.id
            );
            if (index !== -1) {
                list_data.value[index] = newItem; // update
            } else {
                list_data.value.push(newItem); // tambah
            }
        });
        nextPageUrl.value = res.data.next_page_url;
    } catch (err) {
        console.error("Gagal memuat data:", err);
    } finally {
        loading.value = false;
    }
}

function createObserver() {
    observer.value = new IntersectionObserver(async (entries) => {
        const entry = entries[0];
        if (entry.isIntersecting && !loading.value) {
            await loadMore();
        }
    });
    if (sentinel.value) observer.value.observe(sentinel.value);
}

onMounted(() => {
    createObserver();
});

onUnmounted(() => {
    if (observer.value && sentinel.value) {
        observer.value.unobserve(sentinel.value);
    }
});
watch([page, perPage, search, sortBy, sortDir], fetch_detail);
watchEffect(() => {
    if (search.value === "") {
        hasSearched.value = false;
        handleSearch();
    }
    if (sentinel.value) {
        if (observer.value) observer.value.disconnect();
        createObserver();
    }
});
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
            <section class="max-w-7xl pb-8">
                <p
                    v-if="hasSearched"
                    class="text-gray-500 text-sm sm:text-base mb-6 text-center"
                >
                    Ditemukan
                    <span class="text-green-700 font-semibold">{{
                        list_data.length
                    }}</span>
                    data untuk
                    <span class="font-semibold"
                        >"{{ data_controller.props.filters.q }}"</span
                    >
                    <span v-if="selectedFilter !== 'semua'">
                        dalam kategori
                        <span class="text-green-700 font-medium"
                            >{{ selectedFilter }}
                        </span>
                    </span>
                </p>
                <div
                    v-if="list_data.length > 0"
                    class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3"
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
                                    {{
                                        data.nama_seksi ||
                                        data.seksi?.nama_seksi
                                    }}
                                    ·
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
                        <div class="mt-4 flex items-center justify-between">
                            <div
                                v-if="data.jumlah_data > 0 && hasSearched"
                                @click="openDetail(data)"
                                class="flex items-center gap-2 bg-green-50 border border-green-100 text-green-700 px-3 py-1.5 rounded-lg text-xs sm:text-sm"
                            >
                                <Database class="w-4 h-4" />
                                <span class="font-medium">
                                    {{ data.jumlah_data }} hasil untuk
                                    <span class="italic text-green-800"
                                        >"{{
                                            data_controller.props.filters.q
                                        }}"</span
                                    >
                                </span>
                                <button
                                    class="text-green-700 hover:text-green-800 underline font-medium"
                                >
                                    Lihat
                                </button>
                            </div>

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
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-6"
                >
                    <div
                        class="w-6 h-6 border-4 border-green-800 border-t-transparent rounded-full animate-spin"
                    ></div>
                    <span class="ml-2 text-gray-500">Memuat data...</span>
                </div>
                <div
                    v-else-if="!nextPageUrl && list_data.length > 0"
                    class="text-center py-4 text-gray-400 italic"
                >
                    Semua data telah ditampilkan.
                </div>
                <div ref="sentinel" class="h-1"></div>
            </section>
            <ModalHeadnessUI :open-modal="openModal" @close="openModal = false">
                <div
                    v-if="loadingDetail"
                    class="flex flex-col items-center justify-center py-10 space-y-3"
                >
                    <div
                        class="w-10 h-10 border-4 border-green-600 border-t-transparent rounded-full animate-spin"
                    ></div>

                    <p class="text-gray-500 text-sm font-medium">
                        Sedang mengambil data dari server
                    </p>
                </div>
                <div class="w-full overflow-x-auto">
                    <table class="border border-gray-300 w-full">
                        <thead>
                            <tr>
                                <th
                                    v-for="(col, index) in fieldsDetail"
                                    :key="index"
                                    class="border px-4 py-2 capitalize"
                                >
                                    {{ col }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(row, index) in dataDetail"
                                :key="index"
                                class="hover:bg-gray-100"
                            >
                                <td
                                    v-for="(field, j) in fieldsDetail"
                                    :key="j"
                                    class="px-3 py-2 border border-gray-300 text-gray-600"
                                >
                                    {{ row[field] || "" }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div
                        class="ml-3 mr-3 mt-4 mb-3 flex justify-between items-center"
                    >
                        <button
                            @click="page > 1 ? page-- : null"
                            :disabled="page === 1"
                        >
                            Prev
                        </button>
                        <span
                            >Page {{ page }} of
                            {{ Math.ceil(total / perPage) }}</span
                        >
                        <button
                            @click="
                                page < Math.ceil(total / perPage)
                                    ? page++
                                    : null
                            "
                            :disabled="page >= Math.ceil(total / perPage)"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </ModalHeadnessUI>
        </div>
    </HomeLayout>
</template>
