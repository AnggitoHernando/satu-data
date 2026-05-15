<script setup>
import { ref, computed, watch } from "vue";
import axios from "axios";
import {
    XIcon,
    PlusIcon,
    TrashIcon,
    LoaderCircleIcon,
    PencilIcon,
} from "lucide-vue-next";

const props = defineProps({
    kategori: {
        type: Object,
        default: null,
    },
    modelValue: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:modelValue"]);

// Auto focus directive untuk input edit
const vFocus = { mounted: (el) => el.focus() };

const close = () => emit("update:modelValue", false);

// ─── State ────────────────────────────────────────────────────────
const groups = ref([]);
const selectedGroup = ref(null);
const loadingGroups = ref(false);
const loadingItems = ref(false);

const newGroupName = ref("");
const savingGroup = ref(false);

const newItemName = ref("");
const savingItem = ref(false);

const deletingGroupId = ref(null);
const deletingItemId = ref(null);

const editingGroupId = ref(null);
const editingGroupName = ref("");
const savingEditGroup = ref(false);

const selectedItems = computed(() => {
    if (!selectedGroup.value) return [];
    const found = groups.value.find((g) => g.id === selectedGroup.value.id);
    return found?.items ?? [];
});

const totalItems = computed(() =>
    groups.value.reduce((acc, g) => acc + (g.items?.length ?? 0), 0),
);

watch(
    () => props.modelValue,
    async (val) => {
        if (val && props.kategori) {
            selectedGroup.value = null;
            groups.value = [];
            await fetchGroups();
        }
    },
);

const fetchGroups = async () => {
    loadingGroups.value = true;
    try {
        const res = await axios.get(
            route("admin.statistik.group-kategori", props.kategori.id),
        );
        groups.value = Array.isArray(res.data)
            ? res.data
            : (res.data?.data ?? []);
        if (groups.value.length > 0) {
            selectGroup(groups.value[0]);
        }
    } catch (e) {
        console.error("[ModalGroupKategori] fetchGroups error:", e);
    } finally {
        loadingGroups.value = false;
    }
};

const selectGroup = async (group) => {
    selectedGroup.value = group;
    if (group.items) return; // sudah ada, tidak perlu fetch ulang

    loadingItems.value = true;
    try {
        const res = await axios.get(
            route("admin.statistik.group-kategori-items", group.id),
        );
        const items = Array.isArray(res.data)
            ? res.data
            : (res.data?.data ?? []);
        const idx = groups.value.findIndex((g) => g.id === group.id);
        if (idx !== -1) groups.value[idx].items = items;
    } catch (e) {
        console.error("[ModalGroupKategori] fetchItems error:", e);
    } finally {
        loadingItems.value = false;
    }
};

const tambahGroup = async () => {
    const nama = newGroupName.value.trim();
    if (!nama) return;

    savingGroup.value = true;
    try {
        const res = await axios.post(
            route("admin.statistik.group-kategori.simpan", props.kategori.id),
            {
                nama_group: nama,
                kategori_data_id: props.kategori.id,
            },
        );
        const group = { ...(res.data?.data ?? res.data), items: [] };
        groups.value.push(group);
        newGroupName.value = "";
        selectGroup(group);
    } catch (e) {
        console.error("[ModalGroupKategori] tambahGroup error:", e);
    } finally {
        savingGroup.value = false;
    }
};

const hapusGroup = async (group) => {
    const result = await Swal.fire({
        title: "Apakah Anda yakin?",
        text: `Hapus group "${group.nama_group}"? Semua items akan ikut terhapus.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#166534",
        confirmButtonText: "Ya, hapus!",
    });

    if (!result.isConfirmed) return;
    if (result.isConfirmed) {
        deletingGroupId.value = group.id;
        try {
            await axios.delete(
                route("admin.statistik.group-kategori.destroy", group.id),
            );
            groups.value = groups.value.filter((g) => g.id !== group.id);
            if (selectedGroup.value?.id === group.id) {
                selectedGroup.value = groups.value[0] ?? null;
            }
        } catch (e) {
            console.error("[ModalGroupKategori] hapusGroup error:", e);
        } finally {
            deletingGroupId.value = null;
        }
    }
};

const tambahItem = async () => {
    const nama = newItemName.value.trim();
    if (!nama || !selectedGroup.value) return;

    savingItem.value = true;
    try {
        const res = await axios.post(
            route("admin.statistik.group-kategori-items.simpan"),
            {
                nama_item: nama,
                group_kategori_id: selectedGroup.value.id,
            },
        );
        const item = res.data?.data ?? res.data;
        const idx = groups.value.findIndex(
            (g) => g.id === selectedGroup.value.id,
        );
        if (idx !== -1) {
            if (!groups.value[idx].items) groups.value[idx].items = [];
            groups.value[idx].items.push(item);
        }
        newItemName.value = "";
    } catch (e) {
        console.error("[ModalGroupKategori] tambahItem error:", e);
    } finally {
        savingItem.value = false;
    }
};

const hapusItem = async (item) => {
    const result = await Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#166534",
        confirmButtonText: "Ya, hapus!",
    });

    if (!result.isConfirmed) return;

    if (result.isConfirmed) {
        deletingItemId.value = item.id;
        try {
            await axios.delete(
                route("admin.statistik.group-kategori-items.destroy", item.id),
            );
            const idx = groups.value.findIndex(
                (g) => g.id === selectedGroup.value.id,
            );
            if (idx !== -1) {
                groups.value[idx].items = groups.value[idx].items.filter(
                    (i) => i.id !== item.id,
                );
            }
        } catch (e) {
            console.error("[ModalGroupKategori] hapusItem error:", e);
        } finally {
            deletingItemId.value = null;
        }
    }
};

const startEditGroup = (group) => {
    editingGroupId.value = group.id;
    editingGroupName.value = group.nama_group;
};

const cancelEditGroup = () => {
    editingGroupId.value = null;
    editingGroupName.value = "";
};

const saveEditGroup = async (group) => {
    const nama = editingGroupName.value.trim();
    if (!nama || nama === group.nama_group) {
        cancelEditGroup();
        return;
    }

    savingEditGroup.value = true;
    try {
        await axios.patch(
            route("admin.statistik.group-kategori.update", group.id),
            { nama_group: nama, kategori_data_id: props.kategori.id },
        );
        const idx = groups.value.findIndex((g) => g.id === group.id);
        if (idx !== -1) groups.value[idx].nama_group = nama;
        if (selectedGroup.value?.id === group.id)
            selectedGroup.value.nama_group = nama;
        cancelEditGroup();
    } catch (e) {
        console.error("[ModalGroupKategori] saveEditGroup error:", e);
    } finally {
        savingEditGroup.value = false;
    }
};

const onEditGroupKeydown = (e, group) => {
    if (e.key === "Enter") saveEditGroup(group);
    if (e.key === "Escape") cancelEditGroup();
};
const onGroupKeydown = (e) => {
    if (e.key === "Enter") tambahGroup();
};
const onItemKeydown = (e) => {
    if (e.key === "Enter") tambahItem();
};

const mobilePanel = ref("groups"); // "groups" | "items"

const selectGroupMobile = async (group) => {
    await selectGroup(group);
    mobilePanel.value = "items";
};

const backToGroups = () => {
    mobilePanel.value = "groups";
};
</script>

<template>
    <Teleport to="body">
        <Transition name="fade">
            <div
                v-if="modelValue"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
                @click.self="close"
            >
                <Transition name="slide-up">
                    <div
                        v-if="modelValue"
                        class="bg-white rounded-xl border border-gray-200 w-full max-w-4xl shadow-lg overflow-hidden"
                        style="
                            height: 80vh;
                            max-height: 80vh;
                            display: flex;
                            flex-direction: column;
                        "
                    >
                        <div
                            class="flex items-center justify-between px-5 py-3 border-b border-gray-100"
                        >
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ kategori?.nama_kategori }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ groups.length }} group &middot;
                                    {{ totalItems }} items
                                </p>
                            </div>
                            <button
                                @click="close"
                                class="text-gray-400 hover:text-gray-600 p-1"
                            >
                                <XIcon class="w-4 h-4" />
                            </button>
                        </div>

                        <div class="flex flex-1 overflow-hidden">
                            <div
                                class="border-r border-gray-100 flex flex-col flex-shrink-0"
                                :class="[
                                    'sm:flex sm:w-64',
                                    mobilePanel === 'groups'
                                        ? 'flex w-full'
                                        : 'hidden',
                                ]"
                            >
                                <div class="flex-1 overflow-y-auto">
                                    <div
                                        v-if="loadingGroups"
                                        class="flex items-center justify-center py-8"
                                    >
                                        <LoaderCircleIcon
                                            class="w-4 h-4 animate-spin text-gray-300"
                                        />
                                    </div>

                                    <div
                                        v-else-if="groups.length === 0"
                                        class="px-4 py-6 text-xs text-gray-400 text-center"
                                    >
                                        Belum ada group
                                    </div>

                                    <div
                                        v-for="group in groups"
                                        :key="group.id"
                                        class="w-full text-left flex items-center justify-between px-3 py-2.5 text-xs border-b border-gray-50 transition-colors group/item"
                                        :class="
                                            selectedGroup?.id === group.id
                                                ? 'bg-emerald-50 text-emerald-800 border-l-2 border-l-emerald-500'
                                                : 'text-gray-700 hover:bg-gray-50'
                                        "
                                    >
                                        <template
                                            v-if="editingGroupId === group.id"
                                        >
                                            <input
                                                v-model="editingGroupName"
                                                class="flex-1 text-xs border border-emerald-300 rounded px-1.5 py-0.5 outline-none min-w-0 bg-white"
                                                @keydown="
                                                    onEditGroupKeydown(
                                                        $event,
                                                        group,
                                                    )
                                                "
                                                @blur="saveEditGroup(group)"
                                                v-focus
                                            />
                                            <LoaderCircleIcon
                                                v-if="savingEditGroup"
                                                class="w-3 h-3 animate-spin ml-1 flex-shrink-0 text-gray-400"
                                            />
                                        </template>

                                        <template v-else>
                                            <button
                                                class="flex-1 text-left truncate"
                                                :class="
                                                    selectedGroup?.id ===
                                                    group.id
                                                        ? 'font-medium'
                                                        : ''
                                                "
                                                @click="
                                                    selectGroupMobile(group)
                                                "
                                            >
                                                {{ group.nama_group }}
                                            </button>
                                            <div
                                                class="flex items-center gap-1 flex-shrink-0 ml-1"
                                            >
                                                <span
                                                    class="text-gray-400 text-[10px]"
                                                    >{{
                                                        group.items?.length ??
                                                        "..."
                                                    }}</span
                                                >
                                                <button
                                                    class="opacity-0 group-hover/item:opacity-100 text-gray-300 hover:text-emerald-500 transition-all p-0.5"
                                                    @click.stop="
                                                        startEditGroup(group)
                                                    "
                                                    aria-label="Edit group"
                                                >
                                                    <PencilIcon
                                                        class="w-3 h-3"
                                                    />
                                                </button>
                                                <span
                                                    class="sm:hidden text-gray-300 text-[10px]"
                                                    >›</span
                                                >
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 p-2">
                                    <div class="flex gap-1">
                                        <input
                                            v-model="newGroupName"
                                            type="text"
                                            placeholder="Nama group..."
                                            class="flex-1 text-xs border border-gray-200 rounded-md px-2 py-1.5 outline-none focus:border-emerald-400 min-w-0"
                                            @keydown="onGroupKeydown"
                                        />
                                        <button
                                            class="p-1.5 bg-primary text-white rounded-md hover:bg-emerald-700 disabled:opacity-50"
                                            :disabled="
                                                savingGroup ||
                                                !newGroupName.trim()
                                            "
                                            @click="tambahGroup"
                                            aria-label="Tambah group"
                                        >
                                            <LoaderCircleIcon
                                                v-if="savingGroup"
                                                class="w-3 h-3 animate-spin"
                                            />
                                            <PlusIcon v-else class="w-3 h-3" />
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom kanan -->
                            <div
                                class="flex-1 flex flex-col overflow-hidden"
                                :class="[
                                    'sm:flex',
                                    mobilePanel === 'items' ? 'flex' : 'hidden',
                                ]"
                            >
                                <div
                                    class="sm:hidden flex items-center gap-2 px-4 py-2 border-b border-gray-100 bg-gray-50"
                                >
                                    <button
                                        class="flex items-center gap-1 text-xs text-emerald-600"
                                        @click="backToGroups"
                                    >
                                        <span>‹</span> Semua Group
                                    </button>
                                </div>

                                <div
                                    v-if="!selectedGroup"
                                    class="flex-1 flex items-center justify-center text-xs text-gray-400"
                                >
                                    Pilih group di sebelah kiri
                                </div>

                                <template v-else>
                                    <div
                                        class="flex items-center justify-between px-4 py-2.5 border-b border-gray-100"
                                    >
                                        <p
                                            class="text-xs font-medium text-gray-700"
                                        >
                                            Items —
                                            {{ selectedGroup.nama_group }}
                                        </p>
                                        <button
                                            class="text-gray-300 hover:text-red-400 transition-colors p-1 disabled:opacity-50"
                                            :disabled="
                                                deletingGroupId ===
                                                selectedGroup.id
                                            "
                                            @click="hapusGroup(selectedGroup)"
                                            aria-label="Hapus group"
                                        >
                                            <LoaderCircleIcon
                                                v-if="
                                                    deletingGroupId ===
                                                    selectedGroup.id
                                                "
                                                class="w-3.5 h-3.5 animate-spin"
                                            />
                                            <TrashIcon
                                                v-else
                                                class="w-3.5 h-3.5"
                                            />
                                        </button>
                                    </div>

                                    <div
                                        v-if="loadingItems"
                                        class="flex-1 flex items-center justify-center"
                                    >
                                        <LoaderCircleIcon
                                            class="w-4 h-4 animate-spin text-gray-300"
                                        />
                                    </div>

                                    <div v-else class="flex-1 overflow-y-auto">
                                        <div
                                            v-if="selectedItems.length === 0"
                                            class="px-4 py-6 text-xs text-gray-400 text-center"
                                        >
                                            Belum ada item — tambahkan di bawah
                                        </div>

                                        <div
                                            v-for="item in selectedItems"
                                            :key="item.id"
                                            class="flex items-center gap-2 px-4 py-2.5 border-b border-gray-50 group hover:bg-gray-50"
                                        >
                                            <span
                                                class="w-1 h-1 rounded-full bg-gray-300 flex-shrink-0"
                                            ></span>
                                            <span
                                                class="text-xs text-gray-700 flex-1"
                                                >{{ item.nama_item }}</span
                                            >
                                            <button
                                                class="text-gray-300 hover:text-red-400 transition-all p-0.5 disabled:opacity-50 sm:opacity-0 sm:group-hover:opacity-100"
                                                :disabled="
                                                    deletingItemId === item.id
                                                "
                                                @click="hapusItem(item)"
                                                aria-label="Hapus item"
                                            >
                                                <LoaderCircleIcon
                                                    v-if="
                                                        deletingItemId ===
                                                        item.id
                                                    "
                                                    class="w-3 h-3 animate-spin"
                                                />
                                                <XIcon v-else class="w-3 h-3" />
                                            </button>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-100 p-3">
                                        <div class="flex gap-2">
                                            <input
                                                v-model="newItemName"
                                                type="text"
                                                placeholder="Tambah item baru..."
                                                class="flex-1 text-xs border border-gray-200 rounded-md px-3 py-1.5 outline-none focus:border-emerald-400"
                                                @keydown="onItemKeydown"
                                            />
                                            <button
                                                class="flex items-center gap-1 px-3 py-1.5 text-xs bg-primary text-white rounded-md hover:bg-emerald-700 disabled:opacity-50"
                                                :disabled="
                                                    savingItem ||
                                                    !newItemName.trim()
                                                "
                                                @click="tambahItem"
                                            >
                                                <LoaderCircleIcon
                                                    v-if="savingItem"
                                                    class="w-3 h-3 animate-spin"
                                                />
                                                <PlusIcon
                                                    v-else
                                                    class="w-3 h-3"
                                                />
                                                Tambah
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div
                            class="px-5 py-3 border-t border-gray-100 flex justify-end"
                        >
                            <button
                                @click="close"
                                class="text-xs px-4 py-1.5 border border-gray-200 rounded-md text-gray-600 hover:bg-gray-50"
                            >
                                Tutup
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
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
