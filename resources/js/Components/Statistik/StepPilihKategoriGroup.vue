<script setup>
import { ref, watch } from "vue";
import axios from "axios";
import { LoaderCircleIcon } from "lucide-vue-next";

const props = defineProps({
    kategoriId: { type: Number, required: true },
    kategoriNama: { type: String, default: "" },
    groupId: { type: [Number, null], default: null },
    itemId: { type: [Number, null], default: null },
});

const emit = defineEmits(["next", "back"]);

const groups = ref([]);
const items = ref([]);
const selectedGroup = ref(null);
const selectedItem = ref(null);
const loadingGroups = ref(false);
const loadingItems = ref(false);

const fetchGroups = async () => {
    loadingGroups.value = true;
    try {
        const res = await axios.get(
            route("admin.statistik.isi-statistik.getGroupKategori", {
                kategoriDataId: props.kategoriId,
            }),
        );
        groups.value = Array.isArray(res.data)
            ? res.data
            : (res.data?.data ?? []);

        if (props.groupId) {
            selectedGroup.value =
                groups.value.find((g) => g.id === props.groupId) ?? null;
        }
    } catch (e) {
        console.error("[StepPilihGroupItem] fetchGroups error:", e);
    } finally {
        loadingGroups.value = false;
    }
};

// ─── Fetch items saat group dipilih ───────────────────────────────
const fetchItems = async (group) => {
    if (!group) {
        items.value = [];
        selectedItem.value = null;
        return;
    }

    loadingItems.value = true;
    try {
        const res = await axios.get(
            route("admin.statistik.isi-statistik.getGroupKategoriItem", {
                groupKategoriId: group.id,
            }),
        );
        items.value = Array.isArray(res.data)
            ? res.data
            : (res.data?.data ?? []);

        if (props.itemId) {
            selectedItem.value =
                items.value.find((i) => i.id === props.itemId) ?? null;
        }
    } catch (e) {
        console.error("[StepPilihGroupItem] fetchItems error:", e);
    } finally {
        loadingItems.value = false;
    }
};

watch(selectedGroup, (val) => {
    selectedItem.value = null;
    fetchItems(val);
});

fetchGroups();

const next = () => {
    if (!selectedGroup.value || !selectedItem.value) return;
    emit("next", {
        group_id: selectedGroup.value.id,
        group_nama: selectedGroup.value.nama_group,
        item_id: selectedItem.value.id,
        item_nama: selectedItem.value.nama_item,
    });
};
</script>

<template>
    <div class="flex flex-col gap-4">
        <div
            class="flex items-center gap-1.5 text-xs text-gray-400 bg-gray-50 rounded-lg px-3 py-2"
        >
            <span class="text-emerald-600 font-medium">{{ kategoriNama }}</span>
        </div>

        <div class="flex flex-col gap-1.5">
            <label class="text-xs font-medium text-gray-500">Group</label>
            <div v-if="loadingGroups" class="flex items-center gap-2 py-2">
                <LoaderCircleIcon
                    class="w-3.5 h-3.5 animate-spin text-gray-300"
                />
                <span class="text-xs text-gray-400">Memuat group...</span>
            </div>
            <div v-else class="flex flex-wrap gap-2">
                <button
                    v-for="group in groups"
                    :key="group.id"
                    class="px-3 py-1.5 text-xs rounded-lg border transition-colors"
                    :class="
                        selectedGroup?.id === group.id
                            ? 'bg-emerald-600 text-white border-emerald-600'
                            : 'bg-white text-gray-600 border-gray-200 hover:border-emerald-300'
                    "
                    @click="selectedGroup = group"
                >
                    {{ group.nama_group }}
                </button>
                <span v-if="groups.length === 0" class="text-xs text-gray-400">
                    Belum ada group untuk kategori ini
                </span>
            </div>
        </div>

        <div class="flex flex-col gap-1.5">
            <label class="text-xs font-medium text-gray-500">Item</label>
            <div v-if="!selectedGroup" class="text-xs text-gray-400 py-1">
                Pilih group terlebih dahulu
            </div>
            <div v-else-if="loadingItems" class="flex items-center gap-2 py-2">
                <LoaderCircleIcon
                    class="w-3.5 h-3.5 animate-spin text-gray-300"
                />
                <span class="text-xs text-gray-400">Memuat item...</span>
            </div>
            <div v-else class="flex flex-wrap gap-2">
                <button
                    v-for="item in items"
                    :key="item.id"
                    class="px-3 py-1.5 text-xs rounded-lg border transition-colors"
                    :class="
                        selectedItem?.id === item.id
                            ? 'bg-emerald-600 text-white border-emerald-600'
                            : 'bg-white text-gray-600 border-gray-200 hover:border-emerald-300'
                    "
                    @click="selectedItem = item"
                >
                    {{ item.nama_item }}
                </button>
                <span v-if="items.length === 0" class="text-xs text-gray-400">
                    Belum ada item untuk group ini
                </span>
            </div>
        </div>

        <div class="flex justify-between pt-2">
            <button
                class="px-4 py-2 text-xs text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50"
                @click="emit('back')"
            >
                ← Kembali
            </button>
            <button
                class="px-4 py-2 text-xs bg-emerald-600 text-white rounded-lg disabled:opacity-50 hover:bg-emerald-700"
                :disabled="!selectedGroup || !selectedItem"
                @click="next"
            >
                Lanjut →
            </button>
        </div>
    </div>
</template>
