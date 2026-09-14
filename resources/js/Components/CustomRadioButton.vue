<script setup>
import {
    RadioGroup,
    RadioGroupLabel,
    RadioGroupDescription,
    RadioGroupOption,
} from "@headlessui/vue";
import { CheckCircle } from "lucide-vue-next";
import { computed } from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Number, Object],
        required: true,
    },
    options: {
        type: Array,
        required: true,
    },
    cols: {
        type: [Number, String],
        default: 4,
    },
});
const gridColsMap = {
    1: "grid-cols-1",
    2: "grid-cols-1 md:grid-cols-2",
    3: "grid-cols-1 md:grid-cols-3",
    4: "grid-cols-1 md:grid-cols-4",
    5: "grid-cols-1 md:grid-cols-5",
    6: "grid-cols-1 md:grid-cols-6",
};

const gridColsClass = computed(() => {
    return gridColsMap[props.cols] || "grid-cols-1 md:grid-cols-4";
});

defineEmits(["update:modelValue"]);
</script>
<template>
    <RadioGroup
        :modelValue="modelValue"
        @update:modelValue="$emit('update:modelValue', $event)"
    >
        <RadioGroupLabel class="sr-only">Pilihan</RadioGroupLabel>

        <div :class="['grid gap-4', gridColsClass]">
            <RadioGroupOption
                as="template"
                v-for="option in options"
                :key="option.value"
                :value="option.value"
                v-slot="{ active, checked }"
            >
                <div
                    :class="[
                        active
                            ? 'ring-2 ring-white/60 ring-offset-2 ring-offset-green-600'
                            : '',
                        checked
                            ? 'bg-green-800 text-white ring-2 ring-green-300'
                            : 'bg-white text-gray-900 ring-1 ring-gray-200 hover:bg-gray-50',
                        'relative flex cursor-pointer rounded-lg px-5 py-4 shadow-md focus:outline-none transition-all duration-200 h-full',
                    ]"
                >
                    <div class="flex w-full items-center justify-between">
                        <div class="flex items-center">
                            <div class="text-sm">
                                <RadioGroupLabel
                                    as="p"
                                    :class="
                                        checked
                                            ? 'text-white font-bold'
                                            : 'text-gray-900 font-semibold'
                                    "
                                    class="rounded-full text-base font-semibold"
                                >
                                    {{ option.label }}
                                </RadioGroupLabel>
                                <RadioGroupDescription
                                    as="span"
                                    :class="
                                        checked
                                            ? 'text-sky-100'
                                            : 'text-gray-500'
                                    "
                                    class="inline-block mt-1 text-xs"
                                >
                                    {{ option.description }}
                                </RadioGroupDescription>
                            </div>
                        </div>

                        <div v-show="checked" class="shrink-0 text-white">
                            <CheckCircle class="h-6 w-6 opacity-80" />
                        </div>
                    </div>
                </div>
            </RadioGroupOption>
        </div>
    </RadioGroup>
</template>
