<script setup>
import { QrCode } from "lucide-vue-next";

defineProps({
    modelValue: {
        type: [String, Number],
        default: "",
    },
    placeholder: {
        type: String,
        default: "Ketik",
    },
    buttonText: {
        type: String,
        default: "Lacak Progres",
    },
});

const emit = defineEmits(["update:modelValue", "submit"]);

const handleSubmit = () => {
    emit("submit");
};
</script>

<template>
    <form @submit.prevent="handleSubmit" class="w-full">
        <div
            class="relative flex items-center w-full bg-white rounded-2xl md:rounded-full p-2 pl-4 sm:pl-5 shadow-lg border border-gray-100"
        >
            <div class="shrink-0 mr-3 text-[#10B981]">
                <slot name="icon">
                    <QrCode class="w-6 h-6 stroke-[2.2]" />
                </slot>
            </div>

            <input
                type="text"
                :value="modelValue"
                @input="emit('update:modelValue', $event.target.value)"
                :placeholder="placeholder"
                class="w-full bg-transparent border-0 outline-none focus:outline-none focus:ring-0 text-gray-700 placeholder-gray-400 text-sm md:text-base font-mono pr-2"
            />

            <button
                type="submit"
                class="shrink-0 bg-green-800 hover:bg-[#059669] text-white font-semibold px-5 md:px-7 py-3 rounded-xl md:rounded-full transition-colors duration-200 text-sm md:text-base cursor-pointer shadow-sm"
            >
                {{ buttonText }}
            </button>
        </div>
    </form>
</template>
