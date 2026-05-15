<script setup>
import { ref, computed, watch } from "vue";
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from "@headlessui/vue";
import {
    CheckIcon,
    ChevronsUpDownIcon,
    LoaderCircleIcon,
    XIcon,
} from "lucide-vue-next";
import axios from "axios";

const props = defineProps({
    modelValue: {
        type: [Object, String, Number, null],
        default: null,
    },
    options: {
        type: Array,
        default: () => [],
    },

    labelKey: {
        type: String,
        default: "name",
    },
    searchUrl: {
        type: String,
        default: null,
    },
    valueKey: {
        type: String,
        default: "id",
    },

    placeholder: {
        type: String,
        default: "Pilih data",
    },
    debounce: {
        type: Number,
        default: 400,
    },

    minChars: {
        type: Number,
        default: 1,
    },
    initialLabel: {
        type: String,
        default: null,
    },
    emitObject: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:modelValue"]);
const buttonRef = ref(null);

const selected = ref(props.modelValue);
const query = ref("");
const serverOptions = ref([]);
const isLoading = ref(false);
const errorMsg = ref("");

const isServerMode = computed(() => {
    return !props.options || props.options.length === 0;
});

if (props.modelValue && props.initialLabel) {
    selected.value = {
        [props.valueKey]: props.modelValue,
        [props.labelKey]: props.initialLabel,
    };
}

watch(
    () => props.modelValue,
    (val) => {
        if (!val) {
            selected.value = null;
            return;
        }

        if (props.emitObject) {
            selected.value = val;
        } else {
            const allOptions = isServerMode.value
                ? serverOptions.value
                : props.options;
            const found = allOptions.find(
                (item) => item[props.valueKey] == val,
            );
            selected.value = found ?? null;
        }
    },
);

watch(selected, (val) => {
    if (props.emitObject) {
        emit("update:modelValue", val ?? null);
    } else {
        emit("update:modelValue", val ? val[props.valueKey] : null);
    }
});

const getLabel = (item) => {
    if (!item) return "";
    return String(item[props.labelKey] ?? "");
};

const filteredOptions = computed(() => {
    if (isServerMode.value) return serverOptions.value;
    if (!Array.isArray(props.options)) return [];

    if (!query.value) return props.options;

    const q = query.value.toLowerCase().replace(/\s+/g, "");

    return props.options.filter((item) =>
        getLabel(item).toLowerCase().replace(/\s+/g, "").includes(q),
    );
});
let debounceTimer = null;

const fetchFromServer = async (q) => {
    if (!props.searchUrl) {
        console.warn("[ComboboxSearch] searchUrl tidak diisi.");
        return;
    }

    isLoading.value = true;
    errorMsg.value = "";

    try {
        const response = await axios.get(route(props.searchUrl), {
            params: { q },
        });

        const result = Array.isArray(response.data)
            ? response.data
            : (response.data?.data ?? []);

        serverOptions.value = result;
    } catch (err) {
        errorMsg.value = "Gagal mengambil data.";
        serverOptions.value = [];
        console.error("[ComboboxSearch] Error:", err);
    } finally {
        isLoading.value = false;
    }
};

const onQueryChange = (value) => {
    query.value = value;

    if (!isServerMode.value) return; // mode lokal, tidak perlu fetch

    clearTimeout(debounceTimer);

    if (value.length < props.minChars) {
        serverOptions.value = [];
        return;
    }

    debounceTimer = setTimeout(() => {
        fetchFromServer(value);
    }, props.debounce);
};

const onAfterLeave = () => {
    query.value = "";
    if (isServerMode.value) serverOptions.value = [];
};
</script>

<template>
    <Combobox v-model="selected">
        <div class="relative w-full">
            <div
                class="relative w-full overflow-hidden shadow-sm rounded-md bg-white border border-gray-300 focus-within:border-primary focus-within:ring-primary"
            >
                <ComboboxInput
                    class="w-full border-none py-2 pl-3 pr-10"
                    :placeholder="placeholder"
                    :displayValue="getLabel"
                    @change="onQueryChange($event.target.value)"
                    @focus="buttonRef?.$el?.click()"
                />

                <button
                    v-if="selected"
                    type="button"
                    class="absolute inset-y-0 right-8 flex items-center px-1 text-gray-400 hover:text-gray-600"
                    @click.stop="selected = null"
                >
                    <XIcon class="h-4 w-4" />
                </button>

                <span
                    v-if="isServerMode && isLoading"
                    class="absolute inset-y-0 right-8 flex items-center pr-1"
                >
                    <LoaderCircleIcon
                        class="h-4 w-4 animate-spin text-gray-400"
                    />
                </span>

                <ComboboxButton
                    ref="buttonRef"
                    class="absolute inset-y-0 right-0 flex items-center pr-2"
                >
                    <ChevronsUpDownIcon class="h-5 w-5" />
                </ComboboxButton>
            </div>

            <TransitionRoot
                leave="transition ease-in duration-100"
                leaveFrom="opacity-100"
                leaveTo="opacity-0"
                @after-leave="onAfterLeave"
            >
                <ComboboxOptions
                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-sm shadow-lg"
                >
                    <div v-if="errorMsg" class="px-4 py-2 text-red-500">
                        {{ errorMsg }}
                    </div>

                    <div
                        v-else-if="
                            isServerMode &&
                            query.length < minChars &&
                            !isLoading
                        "
                        class="px-4 py-2 text-gray-400"
                    >
                        Ketik untuk mencari...
                    </div>

                    <div
                        v-else-if="isServerMode && isLoading"
                        class="px-4 py-2 text-gray-400"
                    >
                        Memuat data...
                    </div>

                    <div
                        v-else-if="filteredOptions.length === 0"
                        class="px-4 py-2 text-primary"
                    >
                        Data tidak ditemukan
                    </div>

                    <ComboboxOption
                        v-for="item in filteredOptions"
                        :key="item[valueKey]"
                        :value="item"
                        as="template"
                        v-slot="{ selected, active }"
                    >
                        <li
                            class="relative cursor-pointer select-none py-2 pl-10 pr-4"
                            :class="{
                                'bg-primary text-white': active,
                                'text-blackz-900': !active,
                            }"
                        >
                            <span
                                class="block truncate"
                                :class="{
                                    'font-semibold': selected,
                                    'font-normal': !selected,
                                }"
                            >
                                {{ getLabel(item) }}
                            </span>

                            <span
                                v-if="selected"
                                class="absolute inset-y-0 left-0 flex items-center pl-3"
                            >
                                <CheckIcon class="h-5 w-5" />
                            </span>
                        </li>
                    </ComboboxOption>
                </ComboboxOptions>
            </TransitionRoot>
        </div>
    </Combobox>
</template>
