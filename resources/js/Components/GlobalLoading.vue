<script setup>
import { Loader2 } from "lucide-vue-next";
</script>

<template>
    <Teleport to="body">
        <div
            class="fixed inset-0 bg-white/70 backdrop-blur-sm z-[9999] flex items-center justify-center"
        >
            <div class="flex items-center gap-3 text-gray-700">
                <Loader2 class="animate-spin text-primary" :size="36" />
                <span class="text-sm font-medium">Loading</span>
            </div>
        </div>
    </Teleport>
</template>