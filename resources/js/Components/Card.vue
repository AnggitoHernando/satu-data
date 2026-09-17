<script setup>
defineProps({
    header: {
        type: String,
        default: "",
    },
    number: {
        type: String,
        default: "",
    },
});
</script>
<template>
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div
            class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 bg-gray-50"
        >
            <span
                class="w-6 h-6 rounded-full bg-green-800 text-white text-xs font-medium flex items-center justify-center flex-shrink-0"
                >{{ number }}</span
            >
            <h2 class="text-sm font-medium text-gray-800">
                {{ header }}
            </h2>
        </div>

        <div class="px-5 py-4 space-y-4">
            <slot name="body"></slot>
        </div>
    </div>
</template>
