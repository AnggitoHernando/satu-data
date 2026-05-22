<script setup>
import { Head } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import {
    Search,
    ChevronDown,
    CheckCircle,
    Loader,
    Circle,
} from "lucide-vue-next";
import ComboboxSearch from "@/Components/ComboBox.vue";
import axios from "axios";

const selectedKategori = ref(null);
const groups = ref([]);
const activeGroup = ref(null);
const tahun = ref(new Date().getFullYear());
const loadingGroups = ref(false);
const loadingItems = ref(false);

// values: { [item_id]: { value, status: 'idle'|'saving'|'saved'|'error' } }
const values = ref({});

// ─── Fetch groups saat kategori dipilih ───────────────────────────
const onKategoriSelected = async (obj) => {
    if (!obj) {
        groups.value = [];
        activeGroup.value = null;
        values.value = {};
        return;
    }
    selectedKategori.value = obj;
    activeGroup.value = null;
    values.value = {};
    loadingGroups.value = true;
    try {
        const res = await axios.get(
            route("admin.statistik.isi-statistik.getGroupKategori", {
                kategoriDataId: selectedKategori.value.id,
            }),
        );
        groups.value = Array.isArray(res.data)
            ? res.data
            : (res.data?.data ?? []);
        if (groups.value.length > 0) selectGroup(groups.value[0]);
    } catch (e) {
        console.error(e);
    } finally {
        loadingGroups.value = false;
    }
};

// ─── Pilih group → fetch items ────────────────────────────────────
const selectGroup = async (group) => {
    activeGroup.value = group;
    values.value = {};

    if (group.items) {
        initValues(group.items);
        return;
    }

    loadingItems.value = true;
    try {
        const res = await axios.get(
            route("admin.statistik.isi-statistik.getGroupKategoriItemBatch", {
                groupKategoriId: group.id,
                tahun: tahun.value,
            }),
        );

        const items = Array.isArray(res.data)
            ? res.data
            : (res.data?.data ?? []);
        const idx = groups.value.findIndex((g) => g.id === group.id);
        if (idx !== -1) groups.value[idx].items = items;
        activeGroup.value = { ...group, items };
        initValues(items);
    } catch (e) {
        console.error(e);
    } finally {
        loadingItems.value = false;
    }
};

const initValues = (items) => {
    values.value = {};
    items.forEach((item) => {
        let initialValue = "";
        if (item.isi_statistik && item.isi_statistik.length > 0) {
            initialValue = item.isi_statistik[0].value ?? "";
        }
        values.value[item.id] = {
            value: initialValue,
            status: initialValue ? "saved" : "idle",
        };
    });
};

// ─── Auto-save per item dengan debounce ───────────────────────────
const debounceTimers = {};

const onValueInput = (itemId, val) => {
    values.value[itemId].value = val;
    values.value[itemId].status = "idle";

    clearTimeout(debounceTimers[itemId]);

    if (val === "" || val === null) return;

    debounceTimers[itemId] = setTimeout(() => {
        saveItem(itemId, val);
    }, 800);
};

const saveItem = async (itemId, val) => {
    values.value[itemId].status = "saving";
    try {
        await axios.post(route("admin.statistik.isi-statistik.store"), {
            group_kategori_item_id: itemId,
            value: Number(val),
            tahun: Number(tahun.value),
        });
        values.value[itemId].status = "saved";
    } catch (e) {
        console.error(e);
        values.value[itemId].status = "error";
    }
};

watch(tahun, (val) => {
    if (!val || String(val).length !== 4 || !activeGroup.value) return;
    delete activeGroup.value.items;
    selectGroup(activeGroup.value);
});

// ─── Computed summary ─────────────────────────────────────────────
const activeItems = computed(() => activeGroup.value?.items ?? []);

const summary = computed(() => {
    const total = activeItems.value.length;
    const saved = Object.values(values.value).filter(
        (v) => v.status === "saved",
    ).length;
    const filled = Object.values(values.value).filter(
        (v) => v.value !== "",
    ).length;
    return { total, saved, filled };
});
</script>

<template>
    <Head title="Input Isi Statistik" />
    <div class="max-w-5xl mx-auto">
        <div
            class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm"
        >
            <div class="px-5 py-4 border-b border-gray-100 space-y-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-gray-500"
                        >Kategori</label
                    >
                    <ComboboxSearch
                        v-model="selectedKategori"
                        :emit-object="true"
                        search-url="admin.statistik.isi-statistik.getKategoriData"
                        label-key="nama_kategori"
                        value-key="id"
                        placeholder="Cari kategori..."
                        @update:model-value="onKategoriSelected"
                    />
                </div>

                <template v-if="selectedKategori">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-gray-500"
                                >Group</label
                            >
                            <div
                                v-if="loadingGroups"
                                class="flex items-center gap-2 py-1"
                            >
                                <Loader
                                    class="w-3.5 h-3.5 animate-spin text-gray-300"
                                />
                                <span class="text-xs text-gray-400"
                                    >Memuat group...</span
                                >
                            </div>
                            <div v-else class="flex flex-wrap gap-2">
                                <button
                                    v-for="group in groups"
                                    :key="group.id"
                                    class="text-xs px-3 py-1.5 rounded-full border transition-all"
                                    :class="
                                        activeGroup?.id === group.id
                                            ? 'bg-green-700 text-white border-green-700'
                                            : 'border-gray-200 text-gray-500 hover:border-green-400'
                                    "
                                    @click="selectGroup(group)"
                                >
                                    {{ group.nama_group }}
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-gray-500"
                                >Tahun</label
                            >
                            <input
                                v-model="tahun"
                                type="number"
                                min="1900"
                                :max="new Date().getFullYear()"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-green-500 focus:ring-1 focus:ring-green-100"
                            />
                        </div>
                    </div>
                </template>
            </div>

            <template v-if="activeGroup">
                <div
                    v-if="loadingItems"
                    class="flex items-center justify-center py-10 gap-2"
                >
                    <Loader class="w-4 h-4 animate-spin text-gray-300" />
                    <span class="text-sm text-gray-400">Memuat item...</span>
                </div>

                <template v-else>
                    <div
                        class="grid grid-cols-[1fr_160px_100px] bg-gray-50 border-b border-gray-100"
                    >
                        <div
                            class="px-5 py-2.5 text-xs font-medium text-gray-400"
                        >
                            Item
                        </div>
                        <div
                            class="px-4 py-2.5 text-xs font-medium text-gray-400 border-l border-gray-100"
                        >
                            Nilai
                        </div>
                        <div
                            class="px-4 py-2.5 text-xs font-medium text-gray-400 border-l border-gray-100"
                        >
                            Status
                        </div>
                    </div>
                    <div class="overflow-y-auto" style="max-height: 320px">
                        <div
                            v-for="item in activeItems"
                            :key="item.id"
                            class="grid grid-cols-[1fr_160px_100px] border-b border-gray-50 last:border-0 hover:bg-gray-50 transition-colors"
                        >
                            <div class="px-5 py-3 flex items-center gap-2">
                                <span
                                    class="w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"
                                ></span>
                                <span class="text-sm text-gray-700">{{
                                    item.nama_item
                                }}</span>
                            </div>

                            <div class="border-l border-gray-100">
                                <input
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    :value="values[item.id]?.value"
                                    class="w-full h-full px-4 py-3 text-sm outline-none bg-transparent focus:bg-green-50 transition-colors"
                                    :class="
                                        values[item.id]?.status === 'saved'
                                            ? 'text-green-700 font-medium'
                                            : 'text-gray-800'
                                    "
                                    @input="
                                        onValueInput(
                                            item.id,
                                            $event.target.value,
                                        )
                                    "
                                />
                            </div>

                            <div
                                class="border-l border-gray-100 px-4 py-3 flex items-center"
                            >
                                <span
                                    v-if="
                                        values[item.id]?.status === 'idle' &&
                                        !values[item.id]?.value
                                    "
                                    class="flex items-center gap-1 text-xs text-gray-300"
                                >
                                    <Circle class="w-3 h-3" />
                                    Kosong
                                </span>
                                <span
                                    v-else-if="
                                        values[item.id]?.status === 'idle' &&
                                        values[item.id]?.value
                                    "
                                    class="flex items-center gap-1 text-xs text-gray-400"
                                >
                                    <Circle class="w-3 h-3" />
                                    Menunggu...
                                </span>
                                <span
                                    v-else-if="
                                        values[item.id]?.status === 'saving'
                                    "
                                    class="flex items-center gap-1 text-xs text-amber-500"
                                >
                                    <Loader class="w-3 h-3 animate-spin" />
                                    Menyimpan
                                </span>
                                <span
                                    v-else-if="
                                        values[item.id]?.status === 'saved'
                                    "
                                    class="flex items-center gap-1 text-xs text-green-600"
                                >
                                    <CheckCircle class="w-3 h-3" />
                                    Tersimpan
                                </span>
                                <span
                                    v-else-if="
                                        values[item.id]?.status === 'error'
                                    "
                                    class="flex items-center gap-1 text-xs text-red-400 cursor-pointer"
                                    @click="
                                        saveItem(item.id, values[item.id].value)
                                    "
                                >
                                    ⚠ Gagal · Coba lagi
                                </span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between"
                    >
                        <span class="text-xs text-gray-400">
                            <span class="text-green-600 font-medium">{{
                                summary.saved
                            }}</span>
                            /{{ summary.total }} tersimpan · Tahun
                            {{ tahun }}
                        </span>
                        <span
                            v-if="
                                summary.saved === summary.total &&
                                summary.total > 0
                            "
                            class="text-xs text-green-600 font-medium flex items-center gap-1"
                        >
                            <CheckCircle class="w-3.5 h-3.5" />
                            Semua data tersimpan
                        </span>
                    </div>
                </template>
            </template>

            <div
                v-else-if="!selectedKategori"
                class="flex flex-col items-center justify-center py-12 text-gray-300"
            >
                <Search class="w-8 h-8 mb-2" />
                <p class="text-sm">Cari dan pilih kategori untuk memulai</p>
            </div>
            <div
                v-else-if="!activeGroup && !loadingGroups"
                class="flex flex-col items-center justify-center py-12 text-gray-300"
            >
                <p class="text-sm">Pilih group untuk melihat item</p>
            </div>
        </div>
        <div class="mt-2">
            <p class="text-sm text-gray-400 mt-0.5">
                Data tersimpan otomatis saat Anda selesai mengetik
            </p>
        </div>
    </div>
</template>
