<script setup>
import { ref } from "vue";
import ComboboxSearch from "@/Components/ComboBox.vue";

const props = defineProps({
    kategoriId: { type: [Number, null], default: null },
});

const emit = defineEmits(["next"]);

const selected = ref(null);

const next = () => {
    if (!selected.value) return;
    emit("next", {
        id: selected.value.id,
        nama_kategori: selected.value.nama_kategori,
    });
};
</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-1.5">
            <label class="text-xs font-medium text-gray-500"
                >Pilih Kategori</label
            >
            <ComboboxSearch
                v-model="selected"
                :emit-object="true"
                search-url="admin.statistik.isi-statistik.getKategoriData"
                label-key="nama_kategori"
                value-key="id"
                placeholder="Cari kategori..."
            />
            <p class="text-xs text-gray-400">
                Ketik untuk mencari kategori data
            </p>
        </div>

        <div class="flex justify-end pt-2">
            <button
                class="px-4 py-2 text-xs bg-emerald-600 text-white rounded-lg disabled:opacity-50 hover:bg-emerald-700"
                :disabled="!selected"
                @click="next"
            >
                Lanjut →
            </button>
        </div>
    </div>
</template>
