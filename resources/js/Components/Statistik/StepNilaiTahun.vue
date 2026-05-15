<script setup>
import { ref, computed } from "vue";
import { LoaderCircleIcon } from "lucide-vue-next";

const props = defineProps({
    form: { type: Object, required: true },
    saving: { type: Boolean, default: false },
});

const emit = defineEmits(["submit", "back"]);
const saving = computed(() => props.saving);

const value = ref(props.form.value ?? null);
const tahun = ref(props.form.tahun ?? new Date().getFullYear());

const valueFormatted = computed(() => {
    if (!value.value) return "-";
    return Number(value.value).toLocaleString("id-ID");
});

const isValid = computed(
    () => value.value !== null && value.value !== "" && tahun.value,
);

const submit = () => {
    if (!isValid.value) return;
    emit("submit", {
        value: Number(value.value),
        tahun: Number(tahun.value),
    });
};
</script>

<template>
    <div class="flex flex-col gap-4">
        <div
            class="bg-gray-50 rounded-lg px-4 py-3 flex flex-col gap-2 text-xs"
        >
            <div class="flex justify-between">
                <span class="text-gray-400">Kategori</span>
                <span class="text-gray-700 font-medium">{{
                    form.kategori_nama
                }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Group</span>
                <span class="text-gray-700">{{
                    form.kategori_group_nama
                }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Item</span>
                <span class="text-gray-700">{{
                    form.kategori_group_item_nama
                }}</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-medium text-gray-500">Nilai</label>
                <input
                    v-model="value"
                    type="number"
                    min="0"
                    placeholder="Contoh: 1234"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-100"
                />
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-medium text-gray-500">Tahun</label>
                <input
                    v-model="tahun"
                    type="number"
                    min="1900"
                    :max="new Date().getFullYear()"
                    placeholder="Contoh: 2024"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-100"
                />
            </div>
        </div>

        <div class="flex justify-between pt-2">
            <button
                class="px-4 py-2 text-xs text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50"
                :disabled="saving"
                @click="emit('back')"
            >
                ← Kembali
            </button>
            <button
                class="flex items-center gap-1.5 px-4 py-2 text-xs bg-emerald-600 text-white rounded-lg disabled:opacity-50 hover:bg-emerald-700"
                :disabled="!isValid || saving"
                @click="submit"
            >
                <LoaderCircleIcon
                    v-if="saving"
                    class="w-3.5 h-3.5 animate-spin"
                />
                <span>{{ saving ? "Menyimpan..." : "Simpan" }}</span>
            </button>
        </div>
    </div>
</template>
