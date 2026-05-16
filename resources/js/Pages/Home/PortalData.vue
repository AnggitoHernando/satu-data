<script setup>
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { Search, Database, ChevronDown, X, BarChart2 } from "lucide-vue-next";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref, computed, onMounted, onUnmounted, watch, watchEffect } from "vue";
import ModalHeadnessUI from "@/Components/ModalHeadnessUI.vue";

const data_controller = usePage();
const list_seksi = computed(() => data_controller.props?.list_seksi ?? []);
const list_data = computed(() => data_controller.props?.list_data?.data ?? []);
const list_statistik = computed(
    () => data_controller.props?.list_statistik ?? [],
);

const nextPageUrl = ref(data_controller.props.list_data.next_page_url);
const loading = ref(false);
const observer = ref(null);
const sentinel = ref(null);
const openModal = ref(false);
const dataDetail = ref([]);
const fieldsDetail = ref([]);
const loadingDetail = ref(false);
const inputSearch = ref(null);

const search = ref(data_controller.props.filters.q || "");
const selectedFilter = ref(data_controller.props.filters.seksi || "semua");
const activeTab = ref("semua"); // "semua" | "portal" | "statistik"
const hasSearched = ref(false);
const total = ref(0);
const page = ref(1);
const perPage = ref(10);
const sortBy = ref("jenis_data.id");
const sortDir = ref("desc");
const selectedId = ref(null);

// ─── Dropdown seksi ───────────────────────────────────────────────
const showSeksiDropdown = ref(false);

const selectedSeksiLabel = computed(() => {
    if (selectedFilter.value === "semua") return "Semua Seksi";
    const found = list_seksi.value.find((s) => s.slug === selectedFilter.value);
    return found ? found.nama_seksi : "Semua Seksi";
});

const toggleSeksiDropdown = () => {
    showSeksiDropdown.value = !showSeksiDropdown.value;
};

const pilihSeksi = (slug) => {
    selectedFilter.value = slug;
    showSeksiDropdown.value = false;
    handleSearch();
};

const resetSeksi = () => {
    selectedFilter.value = "semua";
    handleSearch();
};

// Tutup dropdown saat klik di luar
const handleClickOutside = (e) => {
    if (!e.target.closest("#seksi-wrap")) {
        showSeksiDropdown.value = false;
    }
};

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
        total.value = res.data.records.total;
    } catch (error) {
        console.error(error);
        Swal.fire("Error", "Gagal load detail data", "error");
    } finally {
        loadingDetail.value = false;
    }
};

// ─── Search & filter ──────────────────────────────────────────────
function handleSearch() {
    const params = {};
    if (search.value.trim() !== "") params.q = search.value.trim();
    params.seksi = selectedFilter.value;
    if (activeTab.value !== "semua") params.tab = activeTab.value;

    router.visit(route("PortalData.search"), {
        method: "get",
        data: params,
        preserveState: true,
        replace: true,
        onSuccess: (res) => {
            hasSearched.value = !!data_controller.props.filters.q;
            nextPageUrl.value = res.props.list_data.next_page_url;
            inputSearch.value?.focus();
        },
    });
}

function selectTab(tab) {
    activeTab.value = tab;
    handleSearch();
}

const gotoDetail = (row) => {
    router.get(
        route("PortalData.detail", { slug: `${row.slug}-${row.id}` }),
        {},
        { preserveScroll: false, preserveState: true },
    );
};

const gotoDetailStatistik = (row) => {
    router.get(
        route("PortalData.statistik.detail", {
            slug: `${row.nama_kategori}-${row.id}`,
        }),
        {},
        { preserveScroll: false, preserveState: true },
    );
};

// ─── Infinite scroll ──────────────────────────────────────────────
async function loadMore() {
    if (!nextPageUrl.value || loading.value) return;
    loading.value = true;
    try {
        const res = await axios.get(nextPageUrl.value);
        res.data.data.forEach((newItem) => {
            const index = list_data.value.findIndex(
                (item) => item.id === newItem.id,
            );
            if (index !== -1) list_data.value[index] = newItem;
            else list_data.value.push(newItem);
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
        if (entries[0].isIntersecting && !loading.value) await loadMore();
    });
    if (sentinel.value) observer.value.observe(sentinel.value);
}

onMounted(() => {
    createObserver();
    if (search.value !== "") hasSearched.value = true;
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    if (observer.value && sentinel.value)
        observer.value.unobserve(sentinel.value);
    document.removeEventListener("click", handleClickOutside);
});

watch([page, perPage, sortBy, sortDir], fetch_detail);

watchEffect(() => {
    if (search.value === "" && hasSearched.value === true) {
        hasSearched.value = false;
        handleSearch();
    }
    if (sentinel.value) {
        if (observer.value) observer.value.disconnect();
        createObserver();
    }
});

const filteredData = computed(() => {
    if (activeTab.value === "statistik") return [];
    return list_data.value;
});

const filteredStatistik = computed(() => {
    if (activeTab.value === "portal") return [];
    return list_statistik.value;
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
                    <div class="relative max-w-lg mx-auto" id="seksi-wrap">
                        <div
                            class="flex border border-gray-300 rounded-lg shadow-sm overflow-hidden focus-within:ring-2 focus-within:ring-green-700 focus-within:border-green-700"
                        >
                            <button
                                type="button"
                                class="flex items-center gap-1 px-2 sm:px-3 py-2 bg-gray-50 border-r border-gray-300 text-sm text-gray-600 hover:bg-gray-100 whitespace-nowrap transition-colors flex-shrink-0"
                                @click.stop="toggleSeksiDropdown"
                            >
                                <span
                                    class="max-w-[70px] sm:max-w-[110px] truncate text-xs"
                                    >{{ selectedSeksiLabel }}</span
                                >
                                <ChevronDown
                                    class="w-3 h-3 flex-shrink-0 transition-transform"
                                    :class="
                                        showSeksiDropdown ? 'rotate-180' : ''
                                    "
                                />
                            </button>

                            <div class="relative flex-1">
                                <Search
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"
                                />
                                <input
                                    ref="inputSearch"
                                    type="text"
                                    v-model="search"
                                    placeholder="Cari data atau statistik..."
                                    @keyup.enter="handleSearch"
                                    class="w-full pl-9 pr-3 py-2 text-sm focus:outline-none bg-white"
                                />
                            </div>

                            <PrimaryButton
                                @click="handleSearch"
                                class="rounded-none"
                            >
                                Cari
                            </PrimaryButton>
                        </div>

                        <Transition name="dropdown">
                            <div
                                v-if="showSeksiDropdown"
                                class="absolute left-0 top-full mt-1 w-full sm:w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-20 overflow-hidden"
                            >
                                <button
                                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm hover:bg-gray-50 transition-colors"
                                    :class="
                                        selectedFilter === 'semua'
                                            ? 'text-green-700 font-medium bg-green-50'
                                            : 'text-gray-700'
                                    "
                                    @click="pilihSeksi('semua')"
                                >
                                    Semua Seksi
                                    <span
                                        v-if="selectedFilter === 'semua'"
                                        class="text-green-600 text-xs"
                                        >✓</span
                                    >
                                </button>
                                <button
                                    v-for="s in list_seksi"
                                    :key="s.slug"
                                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm hover:bg-gray-50 transition-colors border-t border-gray-100 text-left"
                                    :class="
                                        selectedFilter === s.slug
                                            ? 'text-green-700 font-medium bg-green-50'
                                            : 'text-gray-700'
                                    "
                                    @click="pilihSeksi(s.slug)"
                                >
                                    <span class="truncate pr-2">{{
                                        s.nama_seksi
                                    }}</span>
                                    <span
                                        v-if="selectedFilter === s.slug"
                                        class="text-green-600 text-xs flex-shrink-0"
                                        >✓</span
                                    >
                                </button>
                            </div>
                        </Transition>
                    </div>

                    <div
                        v-if="selectedFilter !== 'semua'"
                        class="flex justify-center mt-3"
                    >
                        <span
                            class="inline-flex items-center gap-1.5 text-xs bg-green-50 text-green-700 border border-green-200 rounded-full px-3 py-1"
                        >
                            {{ selectedSeksiLabel }}
                            <button
                                @click="resetSeksi"
                                class="hover:text-green-900"
                                aria-label="Hapus filter"
                            >
                                <X class="w-3 h-3" />
                            </button>
                        </span>
                    </div>

                    <div class="flex justify-center mt-4 px-2">
                        <div
                            class="flex bg-white border border-gray-200 rounded-full p-1 gap-1 shadow-sm w-full max-w-xs sm:w-auto"
                        >
                            <button
                                v-for="tab in [
                                    { key: 'semua', label: 'Semua' },
                                    { key: 'portal', label: 'Portal Data' },
                                    { key: 'statistik', label: 'Statistik' },
                                ]"
                                :key="tab.key"
                                class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 text-xs sm:text-sm rounded-full transition-all"
                                :class="
                                    activeTab === tab.key
                                        ? 'bg-green-700 text-white font-medium shadow-sm'
                                        : 'text-gray-500 hover:text-green-700'
                                "
                                @click="selectTab(tab.key)"
                            >
                                {{ tab.label }}
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="max-w-7xl mx-auto px-4 sm:px-6 pb-8">
                <p
                    v-if="hasSearched"
                    class="text-gray-500 text-sm sm:text-base mb-4 text-center"
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
                        dalam seksi
                        <span class="text-green-700 font-medium">{{
                            selectedSeksiLabel
                        }}</span>
                    </span>
                </p>

                <template
                    v-if="
                        activeTab !== 'portal' && filteredStatistik.length > 0
                    "
                >
                    <div class="flex items-center justify-between mb-3">
                        <h2
                            class="text-base font-semibold text-gray-800 flex items-center gap-2"
                        >
                            <BarChart2 class="w-4 h-4 text-green-700" />
                            Statistik
                        </h2>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-8">
                        <div
                            v-for="stat in filteredStatistik"
                            :key="stat.id"
                            class="group bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all hover:-translate-y-1 cursor-pointer"
                            @click="gotoDetailStatistik(stat)"
                        >
                            <div class="flex items-start justify-between gap-2">
                                <div class="p-2 bg-green-50 rounded-lg">
                                    <BarChart2 class="w-5 h-5 text-green-700" />
                                </div>
                                <span
                                    class="text-xs font-medium text-green-700 bg-green-50 border border-green-100 px-2 py-0.5 rounded-md whitespace-nowrap"
                                >
                                    STATISTIK
                                </span>
                            </div>
                            <p
                                class="text-base font-semibold text-gray-800 mt-3 group-hover:text-green-700 transition leading-snug"
                            >
                                {{ stat.nama_kategori }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ stat.nama_seksi || stat.seksi?.nama_seksi }}
                            </p>
                            <p
                                class="text-sm font-medium text-green-700 mt-3 group-hover:underline"
                            >
                                Lihat Detail →
                            </p>
                        </div>
                    </div>
                </template>

                <template v-if="activeTab !== 'statistik'">
                    <div
                        class="flex items-center justify-between mb-3"
                        v-if="
                            activeTab === 'semua' &&
                            filteredStatistik.length > 0
                        "
                    >
                        <h2
                            class="text-base font-semibold text-gray-800 flex items-center gap-2"
                        >
                            <Database class="w-4 h-4 text-green-700" />
                            Portal Data
                        </h2>
                    </div>

                    <div
                        v-if="filteredData.length > 0"
                        class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3"
                    >
                        <div
                            v-for="data in filteredData"
                            :key="data.id"
                            class="group bg-white/90 backdrop-blur-sm border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-5 cursor-pointer hover:-translate-y-1"
                        >
                            <div
                                class="grid grid-cols-[auto_minmax(0,1fr)_auto] gap-4 w-full items-start"
                                @click="gotoDetail(data)"
                            >
                                <div class="p-2 bg-green-50 rounded-lg">
                                    <Database class="w-6 h-6 text-green-700" />
                                </div>
                                <div class="min-w-0">
                                    <h3
                                        class="capitalize font-semibold text-lg group-hover:text-green-700 transition leading-snug break-words"
                                    >
                                        {{ data.judul_data }}
                                    </h3>
                                    <p
                                        class="text-xs uppercase text-gray-400 font-medium mt-1 truncate"
                                    >
                                        {{
                                            data.nama_seksi ||
                                            data.seksi?.nama_seksi
                                        }}
                                        · {{ data.tahun }}
                                    </p>
                                </div>
                                <span
                                    class="text-xs font-semibold px-2 py-1 rounded-md uppercase bg-green-50 text-green-700 border border-green-200 whitespace-nowrap"
                                >
                                    {{ data.extension_file }}
                                </span>
                            </div>

                            <p
                                class="text-sm text-gray-600 line-clamp-3 leading-relaxed mt-3"
                            >
                                {{ data.deskripsi }}
                            </p>

                            <div class="mt-4 flex items-center justify-between">
                                <div
                                    v-if="data.jumlah_data > 0 && hasSearched"
                                    @click="openDetail(data)"
                                    class="flex items-center gap-2 bg-green-50 border border-green-100 text-green-700 px-3 py-1.5 rounded-lg text-xs sm:text-sm cursor-pointer"
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
                                    @click="gotoDetail(data)"
                                    class="text-sm font-medium text-green-700 hover:text-green-800"
                                >
                                    Lihat Detail →
                                </button>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else-if="
                            activeTab !== 'semua' ||
                            filteredStatistik.length === 0
                        "
                        class="text-center text-gray-400 mt-12"
                    >
                        <p class="text-lg font-medium">
                            Tidak ada hasil ditemukan.
                        </p>
                        <p class="text-sm text-gray-500">
                            Coba kata kunci lain atau ubah filter pencarian.
                        </p>
                    </div>
                </template>

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

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
    transition:
        opacity 0.15s,
        transform 0.15s;
}
.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
