<script setup>
defineProps({
    header: {
        type: String,
        default: "",
    },
});
</script>
<template>
    <div
        class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm"
    >
        <div class="px-5 py-4 border-b border-gray-100 space-y-4">
            <div class="flex flex-col gap-1.5">
                <div
                    class="flex items-center gap-2 mb-2 border-b border-gray-800 pb-3"
                >
                    <span class="text-sm font-medium text-bold">{{
                        header
                    }}</span>
                </div>
                <slot name="body"></slot>
            </div>
        </div>
    </div>
</template>
