<script setup>
import { computed } from "vue";
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from "@headlessui/vue";
// Import ikon penanda radio dari Lucide
import { Circle, CircleDot } from "lucide-vue-next";

const props = defineProps({
    modelValue: {
        type: [String, Number, Object, Boolean],
        required: true,
    },
    options: {
        type: Array,
        required: true,
    },
    cols: {
        type: [Number, String],
        default: 3,
    },
});

defineEmits(["update:modelValue"]);

// Mapping class grid Tailwind agar aman dari JIT compiler
const gridColsMap = {
    1: "grid-cols-1",
    2: "grid-cols-1 md:grid-cols-2",
    3: "grid-cols-1 md:grid-cols-3",
    4: "grid-cols-1 md:grid-cols-4",
    5: "grid-cols-1 md:grid-cols-5",
    6: "grid-cols-1 md:grid-cols-6",
};

const gridColsClass = computed(() => {
    return gridColsMap[props.cols] || "grid-cols-1 md:grid-cols-3";
});
</script>
<template>
    <RadioGroup
        :modelValue="modelValue"
        @update:modelValue="$emit('update:modelValue', $event)"
    >
        <RadioGroupLabel class="sr-only">Pilihan Radio</RadioGroupLabel>

        <div :class="['grid gap-3', gridColsClass]">
            <RadioGroupOption
                as="template"
                v-for="option in options"
                :key="option.value"
                :value="option.value"
                v-slot="{ active, checked }"
            >
                <div
                    :class="[
                        active ? 'ring-2 ring-green-500/50' : '',
                        checked
                            ? 'border-green-900 bg-green-900 text-white ring-1 ring-green-500/30'
                            : 'border-zinc-800  hover:bg-green-800 hover:border-green-700 hover:text-white',
                        'relative flex cursor-pointer items-center rounded-xl border px-4 py-3 shadow-sm transition-all duration-200 select-none',
                    ]"
                >
                    <div class="flex items-center gap-3 w-full">
                        <div class="shrink-0 flex items-center justify-center">
                            <CircleDot
                                v-if="checked"
                                class="h-5 w-5 text-green-500 fill-green-500"
                            />
                            <Circle v-else class="h-5 w-5" />
                        </div>

                        <component
                            :is="option.icon"
                            v-if="option.icon"
                            :class="[
                                'h-5 w-5 shrink-0 transition-colors',
                                checked ? 'text-green-400' : '',
                            ]"
                        />

                        <span class="text-sm font-medium tracking-wide">
                            {{ option.label }}
                        </span>
                    </div>
                </div>
            </RadioGroupOption>
        </div>
    </RadioGroup>
</template>
