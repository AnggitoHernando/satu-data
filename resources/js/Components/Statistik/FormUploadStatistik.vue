<script setup>
import { ref, watch } from "vue";
import {
    XIcon,
    DownloadIcon,
    UploadIcon,
    FileSpreadsheetIcon,
    Loader2Icon,
    AlertCircleIcon,
    CheckCircleIcon,
    CheckSquareIcon,
    SquareIcon,
} from "lucide-vue-next";
import axios from "axios";
import ComboboxSearch from "@/Components/ComboBox.vue";

const emit = defineEmits(["update:modelValue", "uploaded"]);
const close = () => {
    emit("update:modelValue", false);
    resetAll();
};

const activeTab = ref("download");
const selectedKategori = ref(null);
const groups = ref([]);
const selectedGroupIds = ref([]);
const loadingGroups = ref(false);
const downloading = ref(false);
const uploadKategori = ref(null);
const uploadFile = ref(null);
const uploading = ref(false);
const uploadResult = ref(null);
const isDragOver = ref(false);

const resetAll = () => {
    activeTab.value = "download";
    selectedKategori.value = null;
    groups.value = [];
    selectedGroupIds.value = [];
    uploadKategori.value = null;
    uploadFile.value = null;
    uploadResult.value = null;
};

watch(
    () => activeTab.value,
    (val) => {
        if (!val) resetAll();
    },
);

const fetchGroups = async (obj) => {
    if (!obj) {
        groups.value = [];
        selectedGroupIds.value = [];
        return;
    }
    loadingGroups.value = true;
    try {
        const res = await axios.get(
            route("admin.statistik.isi-statistik.getGroupKategori", {
                kategoriDataId: obj.id,
            }),
        );
        console.log("fetched groups", res.data);
        groups.value = Array.isArray(res.data)
            ? res.data
            : (res.data?.data ?? []);
        selectedGroupIds.value = groups.value.map((g) => g.id);
    } catch (e) {
        console.error(e);
    } finally {
        loadingGroups.value = false;
    }
};

const onKategoriDownloadSelected = (obj) => {
    selectedKategori.value = obj;
    fetchGroups(obj);
};
const onKategoriUploadSelected = (obj) => {
    uploadKategori.value = obj;
};

const toggleGroup = (id) => {
    selectedGroupIds.value = selectedGroupIds.value.includes(id)
        ? selectedGroupIds.value.filter((i) => i !== id)
        : [...selectedGroupIds.value, id];
};

const allSelected = () => selectedGroupIds.value.length === groups.value.length;
const toggleAll = () => {
    selectedGroupIds.value = allSelected() ? [] : groups.value.map((g) => g.id);
};

const doDownload = () => {
    if (!selectedKategori.value || !selectedGroupIds.value.length) return;
    downloading.value = true;
    const params = new URLSearchParams();
    params.append("kategori_id", selectedKategori.value.id);
    selectedGroupIds.value.forEach((id) => params.append("group_ids[]", id));
    window.location.href =
        route("admin.statistik.excel.download-template") +
        "?" +
        params.toString();
    setTimeout(() => {
        downloading.value = false;
    }, 2000);
};

const onFileChange = (e) => {
    if (e.target.files[0]) setFile(e.target.files[0]);
};
const setFile = (file) => {
    uploadFile.value = file;
    uploadResult.value = null;
};
const removeFile = () => {
    uploadFile.value = null;
    uploadResult.value = null;
};
const onDragOver = (e) => {
    e.preventDefault();
    isDragOver.value = true;
};
const onDragLeave = () => {
    isDragOver.value = false;
};
const onDrop = (e) => {
    e.preventDefault();
    isDragOver.value = false;
    if (e.dataTransfer.files[0]) setFile(e.dataTransfer.files[0]);
};

const doUpload = async () => {
    if (!uploadFile.value || !uploadKategori.value) return;
    uploading.value = true;
    uploadResult.value = null;
    const fd = new FormData();
    fd.append("file", uploadFile.value);
    fd.append("kategori_id", uploadKategori.value.id);
    try {
        const res = await axios.post(
            route("admin.statistik.excel.upload"),
            fd,
            {
                headers: { "Content-Type": "multipart/form-data" },
            },
        );
        uploadResult.value = { ...res.data, success: true };
        emit("uploaded");
    } catch (e) {
        uploadResult.value = {
            success: false,
            message: e.response?.data?.message ?? "Upload gagal.",
            errors: e.response?.data?.errors ?? [],
            inserted: e.response?.data?.inserted ?? 0,
            updated: e.response?.data?.updated ?? 0,
            results: e.response?.data?.results ?? [],
        };
    } finally {
        uploading.value = false;
    }
};
</script>

<template>
    <div>
        <div class="flex border-b border-gray-100 flex-shrink-0">
            <button
                v-for="tab in [
                    {
                        key: 'download',
                        label: 'Download template',
                        icon: DownloadIcon,
                    },
                    {
                        key: 'upload',
                        label: 'Upload data',
                        icon: UploadIcon,
                    },
                ]"
                :key="tab.key"
                class="flex-1 flex items-center justify-center gap-2 py-2.5 text-xs transition-colors border-b-2"
                :class="
                    activeTab === tab.key
                        ? 'text-green-700 border-green-700 font-medium'
                        : 'text-gray-400 border-transparent hover:text-gray-600'
                "
                @click="activeTab = tab.key"
            >
                <component :is="tab.icon" class="w-3.5 h-3.5" />{{ tab.label }}
            </button>
        </div>

        <div class="overflow-y-auto flex-1">
            <!-- Download panel -->
            <div v-if="activeTab === 'download'" class="px-5 py-4 space-y-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-gray-500"
                        >Kategori data</label
                    >
                    <ComboboxSearch
                        v-model="selectedKategori"
                        :emit-object="true"
                        search-url="admin.statistik.isi-statistik.getKategoriData"
                        label-key="nama_kategori"
                        value-key="id"
                        placeholder="Cari kategori..."
                        @update:model-value="onKategoriDownloadSelected"
                    />
                </div>

                <div v-if="selectedKategori" class="flex flex-col gap-1.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-medium text-gray-500"
                            >Pilih group</label
                        >
                        <button
                            v-if="groups.length"
                            class="text-xs text-green-700 hover:underline"
                            @click="toggleAll"
                        >
                            {{ allSelected() ? "Batal semua" : "Pilih semua" }}
                        </button>
                    </div>
                    <div
                        v-if="loadingGroups"
                        class="flex items-center gap-2 py-2"
                    >
                        <Loader2Icon
                            class="w-3.5 h-3.5 animate-spin text-gray-300"
                        /><span class="text-xs text-gray-400"
                            >Memuat group...</span
                        >
                    </div>
                    <div
                        v-else
                        class="border border-gray-100 rounded-lg overflow-hidden"
                    >
                        <button
                            v-for="group in groups"
                            :key="group.id"
                            class="w-full flex items-center gap-3 px-3 py-2.5 text-xs hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 text-left"
                            @click="toggleGroup(group.id)"
                        >
                            <component
                                :is="
                                    selectedGroupIds.includes(group.id)
                                        ? CheckSquareIcon
                                        : SquareIcon
                                "
                                class="w-4 h-4 flex-shrink-0"
                                :class="
                                    selectedGroupIds.includes(group.id)
                                        ? 'text-green-700'
                                        : 'text-gray-300'
                                "
                            />
                            <span class="flex-1 text-gray-700">{{
                                group.nama_group
                            }}</span>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400">
                        {{ selectedGroupIds.length }} dari
                        {{ groups.length }} group dipilih
                    </p>
                </div>

                <button
                    class="w-full flex items-center justify-center gap-2 py-2.5 text-xs font-medium text-white bg-green-700 rounded-lg disabled:opacity-40 hover:bg-green-800 transition-colors"
                    :disabled="
                        !selectedKategori ||
                        !selectedGroupIds.length ||
                        downloading
                    "
                    @click="doDownload"
                >
                    <Loader2Icon
                        v-if="downloading"
                        class="w-3.5 h-3.5 animate-spin"
                    />
                    <DownloadIcon v-else class="w-3.5 h-3.5" />
                    Download template Excel
                    <span v-if="selectedGroupIds.length" class="opacity-75"
                        >({{ selectedGroupIds.length }} sheet)</span
                    >
                </button>
            </div>

            <!-- Upload panel -->
            <div v-else class="px-5 py-4 space-y-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-gray-500"
                        >Kategori data</label
                    >
                    <ComboboxSearch
                        v-model="uploadKategori"
                        :emit-object="true"
                        search-url="admin.statistik.isi-statistik.getKategoriData"
                        label-key="nama_kategori"
                        value-key="id"
                        placeholder="Cari kategori..."
                        @update:model-value="onKategoriUploadSelected"
                    />
                </div>

                <div
                    class="flex gap-2 bg-red-50 border border-red-100 rounded-lg px-3 py-2.5 text-xs text-red-600"
                >
                    <AlertCircleIcon class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" />
                    <span
                        >Gunakan file template yang sudah didownload. Sheet yang
                        tidak sesuai template akan error.</span
                    >
                </div>

                <div
                    v-if="!uploadFile"
                    class="border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition-colors"
                    :class="
                        isDragOver
                            ? 'border-green-400 bg-green-50'
                            : 'border-gray-200 hover:border-green-300 hover:bg-gray-50'
                    "
                    @dragover="onDragOver"
                    @dragleave="onDragLeave"
                    @drop="onDrop"
                    @click="$refs.fileInput.click()"
                >
                    <UploadIcon class="w-7 h-7 mx-auto mb-2 text-green-600" />
                    <p class="text-sm font-medium text-gray-700 mb-1">
                        Klik atau drag file ke sini
                    </p>
                    <p class="text-xs text-gray-400">
                        Format: .xlsx, .xls · Maks. 5MB
                    </p>
                    <input
                        ref="fileInput"
                        type="file"
                        accept=".xlsx,.xls"
                        class="hidden"
                        @change="onFileChange"
                    />
                </div>

                <div
                    v-else
                    class="flex items-center gap-3 bg-green-50 border border-green-100 rounded-lg px-3 py-2.5"
                >
                    <div
                        class="w-8 h-8 bg-green-700 rounded-lg flex items-center justify-center flex-shrink-0"
                    >
                        <FileSpreadsheetIcon class="w-4 h-4 text-white" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-800 truncate">
                            {{ uploadFile.name }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ (uploadFile.size / 1024).toFixed(1) }}
                            KB
                        </p>
                    </div>
                    <button
                        @click="removeFile"
                        class="text-gray-400 hover:text-gray-600 p-1"
                    >
                        <XIcon class="w-3.5 h-3.5" />
                    </button>
                </div>

                <div
                    v-if="uploadResult"
                    class="rounded-lg px-3 py-3 text-xs space-y-2"
                    :class="
                        uploadResult.success
                            ? 'bg-green-50 border border-green-100'
                            : 'bg-red-50 border border-red-100'
                    "
                >
                    <div
                        class="flex items-center gap-1.5 font-medium"
                        :class="
                            uploadResult.success
                                ? 'text-green-700'
                                : 'text-red-600'
                        "
                    >
                        <CheckCircleIcon
                            v-if="uploadResult.success"
                            class="w-3.5 h-3.5"
                        />
                        <AlertCircleIcon v-else class="w-3.5 h-3.5" />
                        {{ uploadResult.message }}
                    </div>
                    <p class="text-gray-500">
                        {{ uploadResult.inserted }} baris ditambah ·
                        {{ uploadResult.updated }} baris diupdate
                    </p>
                    <div v-if="uploadResult.results?.length" class="space-y-1">
                        <div
                            v-for="r in uploadResult.results"
                            :key="r.sheet"
                            class="flex justify-between text-gray-500"
                        >
                            <span>{{ r.sheet }}</span>
                            <span class="text-green-600"
                                >+{{ r.inserted }} · ↑{{ r.updated }}</span
                            >
                        </div>
                    </div>
                    <ul
                        v-if="uploadResult.errors?.length"
                        class="text-red-500 space-y-0.5 list-disc list-inside"
                    >
                        <li v-for="err in uploadResult.errors" :key="err">
                            {{ err }}
                        </li>
                    </ul>
                </div>

                <button
                    class="w-full flex items-center justify-center gap-2 py-2.5 text-xs font-medium text-white bg-green-800 rounded-lg disabled:opacity-40 hover:bg-green-800 transition-colors"
                    :disabled="!uploadFile || !uploadKategori || uploading"
                    @click="doUpload"
                >
                    <Loader2Icon
                        v-if="uploading"
                        class="w-3.5 h-3.5 animate-spin"
                    />
                    <UploadIcon v-else class="w-3.5 h-3.5" />
                    {{ uploading ? "Mengupload..." : "Upload data" }}
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
.slide-up-enter-active {
    transition:
        transform 0.2s ease,
        opacity 0.2s;
}
.slide-up-leave-active {
    transition:
        transform 0.15s ease,
        opacity 0.15s;
}
.slide-up-enter-from {
    transform: translateY(12px);
    opacity: 0;
}
.slide-up-leave-to {
    transform: translateY(8px);
    opacity: 0;
}
</style>
