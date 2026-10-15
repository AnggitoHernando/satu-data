<script setup>
import { computed, ref } from "vue";
import { UploadCloud, FileText, X } from "lucide-vue-next";

const props = defineProps({
    modelValue: {
        type: [File, String, Array, null],
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
    multiple: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:modelValue"]);

const error = ref(null);

// Status mode tunggal
const isExistingFile = computed(() => typeof props.modelValue === "string");
const isFileObject = (val) =>
    typeof File !== "undefined" && val instanceof File;
const isNewFile = computed(() => isFileObject(props.modelValue));

// Status mode multiple
const isMultipleList = computed(
    () => props.multiple && Array.isArray(props.modelValue),
);

const fileNameFromPath = (path) => {
    if (!path) return "";
    return path.split("/").pop();
};

const validateFile = (file) => {
    if (file.size > props.maxSize * 1024 * 1024) {
        return `Ukuran file ${file.name} melebihi ${props.maxSize}MB`;
    }
    return null;
};

const handleFile = (e) => {
    const rawFiles = e.target.files || e.dataTransfer?.files;
    if (!rawFiles || rawFiles.length === 0) return;

    error.value = null;

    if (props.multiple) {
        const currentFiles = Array.isArray(props.modelValue)
            ? [...props.modelValue]
            : [];
        const newFiles = Array.from(rawFiles);

        for (const file of newFiles) {
            const err = validateFile(file);
            if (err) {
                error.value = err;
                return;
            }
        }

        emit("update:modelValue", [...currentFiles, ...newFiles]);
    } else {
        const file = rawFiles[0];
        const err = validateFile(file);
        if (err) {
            error.value = err;
            return;
        }
        emit("update:modelValue", file);
    }
};

const removeFile = (index = null) => {
    if (props.multiple && index !== null && Array.isArray(props.modelValue)) {
        const updatedFiles = props.modelValue.filter((_, i) => i !== index);
        emit(
            "update:modelValue",
            updatedFiles.length > 0 ? updatedFiles : null,
        );
    } else {
        emit("update:modelValue", null);
    }
};
</script>

<template>
    <div class="space-y-2">
        <label
            v-if="!modelValue || multiple"
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
                <span v-if="multiple">Multiple</span>
            </p>

            <input
                type="file"
                class="hidden"
                :accept="accept"
                :multiple="multiple"
                @change="handleFile"
            />
        </label>

        <div v-if="isMultipleList" class="space-y-2">
            <div
                v-for="(item, idx) in modelValue"
                :key="idx"
                class="flex items-center justify-between rounded-lg border bg-slate-50 px-4 py-2"
            >
                <div class="flex items-center gap-2 truncate max-w-[80%]">
                    <FileText class="h-5 w-5 text-slate-500 shrink-0" />
                    <div>
                        <p class="text-sm font-medium truncate">
                            {{
                                typeof item === "string"
                                    ? fileNameFromPath(item)
                                    : item.name
                            }}
                        </p>

                        <p
                            v-if="isFileObject(item)"
                            class="text-xs text-slate-500"
                        >
                            {{ (item.size / 1024 / 1024).toFixed(2) }} MB
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 text-xs">
                    <a
                        v-if="typeof item === 'string'"
                        :href="`/storage/${item}`"
                        target="_blank"
                        class="text-blue-600 hover:underline"
                    >
                        Lihat
                    </a>

                    <button
                        type="button"
                        @click="removeFile(idx)"
                        class="text-red-600 hover:underline"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>

        <div
            v-if="!multiple && isExistingFile"
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
                    @click="removeFile()"
                    class="text-red-600 hover:underline"
                >
                    Ganti
                </button>
            </div>
        </div>

        <div
            v-if="!multiple && isNewFile"
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
                @click="removeFile()"
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
