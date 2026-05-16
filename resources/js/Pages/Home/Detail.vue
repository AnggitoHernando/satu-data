<script setup>
import HomeLayout from "@/Layouts/HomeLayout.vue";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { ref, computed, onMounted, onUnmounted, watch, watchEffect } from "vue";
import { Search, Download, DownloadCloud, ArrowLeft } from "lucide-vue-next";

const page = usePage();
const headerPage = ref(page.props?.data.judul_data ?? "Detail");
const data = ref(page.props?.data);
const loadingDetail = ref(false);
const dataDetail = ref([]);
const fieldsDetail = ref([]);
const total = ref(0);
const pageTable = ref(1);
const perPage = ref(10);
const sortBy = ref("jenis_data.id");
const sortDir = ref("desc");
const searchDetail = ref("");
const apiUrl = ref(page.props?.api_url ?? "");
let debounceTimer = null;
const qrCode = page.props.qr;

const capitalizeEachWord = (str) => {
    return str
        ? str
              .toLowerCase()
              .split(" ")
              .map((s) => s.charAt(0).toUpperCase() + s.slice(1))
              .join(" ")
        : "";
};
const formatDate = (dateStr) => {
    if (!dateStr) return "-";
    const date = new Date(dateStr);
    return date.toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "long",
        year: "numeric",
    });
};

const copyLink = async () => {
    try {
        await navigator.clipboard.writeText(window.location.href);
        toast("Link Berhasil Disalin", "success");
    } catch {
        toast("Link Gagal disalin", "error");
    }
};

const copyApi = async () => {
    try {
        await navigator.clipboard.writeText(apiUrl.value);
        toast("Link Berhasil Disalin", "success");
    } catch {
        toast("Link Gagal disalin", "error");
    }
};

const downloadFile = (id) => {
    const url = route("download.file", { id });
    window.open(url, "_blank");

    toast("Download Dimulai", "success");
};

onMounted(async () => {
    if (
        ["xls", "xlsx", "csv"].includes(
            data.value.extension_file?.toLowerCase(),
        )
    ) {
        await loadDetailData(data.value.id);
    }
});
async function loadDetailData(id) {
    try {
        loadingDetail.value = true;
        const res = await axios.get("/api/api-detail-data", {
            params: {
                id: id,
                search: searchDetail.value,
                page: pageTable.value,
                perPage: perPage.value,
                sortBy: sortBy.value,
                sortDir: sortDir.value,
            },
        });
        fieldsDetail.value = res.data.fields;
        dataDetail.value = [...res.data.records.data.map((r) => r.data_json)];
        total.value = res.data.records.total;
    } catch (err) {
        console.error("Gagal memuat detail data:", err);
    } finally {
        loadingDetail.value = false;
    }
}

function handleSearchInput(e) {
    const value = e.target.value;
    searchDetail.value = value;

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        pageTable.value = 1;
        loadDetailData(data.value.id);
    }, 500);
}
const downloadQR = () => {
    const link = document.createElement("a");
    link.href = qrCode;
    link.download = `${data.value.judul_data}-qr-code.png`;
    link.click();
    console.log(data.judul_data);
};

watch([pageTable, perPage, sortBy, sortDir], () => {
    loadDetailData(data.value.id);
});
const kembali = () => router.visit(route("PortalData"));
</script>
<template>
    <Head :title="capitalizeEachWord(headerPage)" />
    <HomeLayout>
        <div class="min-h-screen">
            <div class="bg-white border-b border-gray-100">
                <div
                    class="max-w-7xl mx-auto py-3 flex items-center gap-2 text-xs text-gray-400"
                >
                    <button
                        @click="kembali"
                        class="flex items-center gap-1 hover:text-green-700 transition-colors"
                    >
                        <ArrowLeft class="w-3.5 h-3.5" />
                        Portal Data
                    </button>
                    <span>›</span>
                    <span>Data</span>
                    <span>›</span>
                    <span class="text-gray-700">{{ data.judul_data }}</span>
                </div>
            </div>
            <div className="max-w-7xl mx-auto space-y-8">
                <section id="header" className="space-y-4">
                    <header
                        className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6"
                    >
                        <div>
                            <h1
                                className="capitalize text-3xl font-bold text-slate-900 tracking-tight"
                            >
                                {{ data.judul_data }}
                            </h1>
                            <p
                                className="text-slate-600 mt-1 max-w-2xl text-sm leading-relaxed"
                            >
                                {{ data.deskripsi }}
                            </p>
                        </div>
                    </header>
                </section>

                <section
                    id="main-content"
                    className="grid grid-cols-1 lg:grid-cols-3 gap-8"
                >
                    <section id="content" className="lg:col-span-2 space-y-6">
                        <section
                            id="metadata"
                            className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition"
                        >
                            <h2
                                className="text-lg font-semibold text-slate-900 mb-4"
                            >
                                Metadata
                            </h2>
                            <div
                                className="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm"
                            >
                                <div className="flex flex-col">
                                    <span className="text-slate-400"
                                        >Sumber Data</span
                                    >
                                    <span
                                        className="font-medium text-slate-800"
                                        >{{ data.sumber_data }}</span
                                    >
                                </div>
                                <div className="flex flex-col">
                                    <span className="text-slate-400"
                                        >Tahun</span
                                    >
                                    <span
                                        className="font-medium text-slate-800"
                                        >{{ data.tahun }}</span
                                    >
                                </div>
                                <div className="flex flex-col">
                                    <span className="text-slate-400"
                                        >Dibuat</span
                                    >
                                    <span
                                        className="font-medium text-slate-800"
                                        >{{ formatDate(data.created_at) }}</span
                                    >
                                </div>
                                <div className="flex flex-col">
                                    <span className="text-slate-400"
                                        >Seksi</span
                                    >
                                    <span
                                        className="font-medium text-slate-800"
                                        >{{ data.seksi.nama_seksi }}</span
                                    >
                                </div>
                                <div className="flex flex-col">
                                    <span className="text-slate-400"
                                        >Penanggung Jawab</span
                                    >
                                    <span className="font-medium text-slate-800"
                                        >Kantor Kementerian Agama Kabupaten
                                        Gresik</span
                                    >
                                </div>
                                <div v-if="qrCode" className="flex flex-col">
                                    <div
                                        class="flex items-center gap-2 group mb-1"
                                    >
                                        <span class="text-slate-400"
                                            >Qr Code</span
                                        >

                                        <Download
                                            @click="downloadQR"
                                            class="w-5 h-5 text-slate-400 cursor-pointer motion-safe:transition-transform motion-safe:duration-300 group-focus-within:rotate-12"
                                        />
                                    </div>
                                    <img
                                        :src="qrCode"
                                        class="w-24 h-24 border"
                                    />
                                </div>
                            </div>
                        </section>
                    </section>
                    <aside id="sidebar" className="space-y-6">
                        <section
                            id="visual-summary"
                            className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition"
                        >
                            <h3
                                className="text-lg font-semibold text-slate-900 mb-3"
                                v-if="
                                    ['xls', 'xlsx', 'csv'].includes(
                                        data.extension_file?.toLowerCase(),
                                    )
                                "
                            >
                                API Services
                            </h3>
                            <h3
                                className="text-lg font-semibold text-slate-900 mb-3"
                                v-if="
                                    !['xls', 'xlsx', 'csv'].includes(
                                        data.extension_file?.toLowerCase(),
                                    )
                                "
                            >
                                Donwload & Salin
                            </h3>
                            <div
                                v-if="
                                    ['xls', 'xlsx', 'csv'].includes(
                                        data.extension_file?.toLowerCase(),
                                    )
                                "
                                @click="copyApi"
                                className="h-12 bg-gradient-to-r from-indigo-50 to-slate-100 rounded-xl flex items-center justify-center text-slate-400 text-[12px] font-medium cursor-pointer"
                                title="Click Untuk Salin Url"
                            >
                                {{ apiUrl }}
                            </div>
                            <div
                                className="grid grid-cols-1 md:grid-cols-2 mt-3 gap-4 text-sm"
                            >
                                <div className="flex flex-col">
                                    <span className="text-slate-400"
                                        >Download File</span
                                    >
                                    <button
                                        @click="downloadFile(data.id)"
                                        class="bg-green-800 text-white px-3 py-1 rounded hover:bg-green-400"
                                        title="Click Untuk Download File"
                                    >
                                        Download
                                    </button>
                                </div>
                                <div className="flex flex-col">
                                    <span className="text-slate-400"
                                        >Salin Link</span
                                    >
                                    <button
                                        @click="copyLink"
                                        class="bg-gray-200 text-black px-3 py-1 rounded hover:bg-green-400"
                                        title="Click Untuk Salin Url"
                                    >
                                        Salin Link
                                    </button>
                                </div>
                            </div>
                        </section>
                    </aside>
                    <section
                        v-if="
                            ['xls', 'xlsx', 'csv'].includes(
                                data.extension_file?.toLowerCase(),
                            )
                        "
                        id="dynamic-content"
                        className="lg:col-span-3 space-y-6"
                    >
                        <div
                            className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition mb-4"
                        >
                            <div
                                className="flex items-center justify-between mb-4"
                            >
                                <h2
                                    className="text-lg font-semibold text-slate-900"
                                >
                                    Preview Data (Excel)
                                </h2>
                                <div class="relative w-64 group">
                                    <input
                                        type="text"
                                        placeholder="Cari data..."
                                        class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 text-sm transition focus:shadow-[0_0_8px_rgba(34,197,94,0.3)]"
                                        @input="handleSearchInput"
                                    />
                                    <Search
                                        class="absolute left-3 top-2.5 w-5 h-5 text-slate-400 pointer-events-none motion-safe:transition-transform motion-safe:duration-300 group-focus-within:rotate-12"
                                    />
                                </div>
                            </div>
                            <div
                                class="animate-pulse space-y-4"
                                v-if="loadingDetail"
                            >
                                <div class="h-48 bg-gray-200 rounded-lg"></div>
                            </div>
                            <div class="w-full overflow-x-auto">
                                <table class="border border-gray-300 w-full">
                                    <thead>
                                        <tr>
                                            <th
                                                v-for="(
                                                    col, index
                                                ) in fieldsDetail"
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
                                                v-for="(
                                                    field, j
                                                ) in fieldsDetail"
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
                                        @click="
                                            pageTable > 1 ? pageTable-- : null
                                        "
                                        :disabled="pageTable === 1"
                                    >
                                        Prev
                                    </button>
                                    <span
                                        >Page {{ pageTable }} of
                                        {{ Math.ceil(total / perPage) }}</span
                                    >
                                    <button
                                        @click="
                                            pageTable <
                                            Math.ceil(total / perPage)
                                                ? pageTable++
                                                : null
                                        "
                                        :disabled="
                                            pageTable >=
                                            Math.ceil(total / perPage)
                                        "
                                    >
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>
                </section>
            </div>
        </div>
    </HomeLayout>
</template>
