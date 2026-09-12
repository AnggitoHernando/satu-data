<script setup>
import { computed, ref } from "vue";
import { UploadCloud, FileText, X } from "lucide-vue-next";

const props = defineProps({
    modelValue: {
        type: [File, String, null],
        default: null,
    },
    accept: {
        type: String,
        default: ".pdf,.jpg,.jpeg,.png",
    },
    maxSize: {
        type: Number,
        default: 5,
    },
});

const emit = defineEmits(["update:modelValue"]);

const error = ref(null);

const isExistingFile = computed(() => typeof props.modelValue === "string");
const isNewFile = computed(() => props.modelValue instanceof File);

const fileNameFromPath = (path) => {
    if (!path) return "";
    return path.split("/").pop();
};

const handleFile = (e) => {
    const file = e.target.files?.[0] || e.dataTransfer?.files?.[0];
    if (!file) return;

    if (file.size > props.maxSize * 1024 * 1024) {
        error.value = `Ukuran maksimal ${props.maxSize}MB`;
        return;
    }

    error.value = null;
    emit("update:modelValue", file);
};

const removeFile = () => {
    emit("update:modelValue", null);
};
</script>

<template>
    <div class="space-y-2">
        <label
            v-if="!modelValue"
            class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-primary bg-white p-6 text-center transition hover:border-primary hover:bg-slate-50"
            @dragover.prevent
            @drop.prevent="handleFile"
        >
            <UploadCloud class="h-8 w-8 text-slate-500" />
            <p class="text-sm text-slate-600">
                <span class="font-semibold text-primary">Klik upload</span>
                atau drag file ke sini
            </p>
            <p class="text-xs text-slate-400">
                {{ accept }} • Max {{ maxSize }}MB
            </p>

            <input
                type="file"
                class="hidden"
                :accept="accept"
                @change="handleFile"
            />
        </label>

        <div
            v-if="isExistingFile"
            class="flex items-center justify-between rounded-lg border bg-slate-50 px-4 py-2"
        >
            <div class="flex items-center gap-2">
                <FileText class="h-5 w-5 text-slate-500" />
                <p class="text-sm text-slate-700 truncate max-w-[220px]">
                    {{ fileNameFromPath(modelValue) }}
                </p>
            </div>

            <div class="flex gap-3 text-xs">
                <a
                    :href="`/storage/${modelValue}`"
                    target="_blank"
                    class="text-blue-600 hover:underline"
                >
                    Lihat
                </a>

                <button
                    type="button"
                    @click="removeFile"
                    class="text-red-600 hover:underline"
                >
                    Ganti
                </button>
            </div>
        </div>

        <div
            v-if="isNewFile"
            class="flex items-center justify-between rounded-lg border bg-slate-50 px-4 py-2"
        >
            <div>
                <p class="text-sm font-medium truncate max-w-[220px]">
                    {{ modelValue.name }}
                </p>
                <p class="text-xs text-slate-500">
                    {{ (modelValue.size / 1024 / 1024).toFixed(2) }} MB
                </p>
            </div>

            <button
                type="button"
                @click="removeFile"
                class="text-xs text-red-600 hover:underline"
            >
                Hapus
            </button>
        </div>

        <p v-if="error" class="text-xs text-red-600">
            {{ error }}
        </p>
    </div>
</template>
