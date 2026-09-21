<script setup>
import { FileText } from "lucide-vue-next";

defineProps({
    name: { type: String, required: true },
    tag: { type: String, default: null },
    url: { type: String, default: "#" },
    type: { type: String, default: "file" },
});
</script>

<template>
    <div
        class="flex items-center gap-3.5 border-t border-stone-200 px-5 py-3.5 first:border-t-0"
    >
        <span
            aria-hidden="true"
            class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700"
        >
            <FileText class="h-4.5 w-4.5" />
        </span>

        <div class="min-w-0 flex-1">
            <p class="truncate text-sm font-semibold text-stone-900">
                {{ name }}
            </p>
            <div class="mt-0.5 flex items-center gap-2 text-xs text-stone-500">
                <span
                    v-if="tag"
                    class="rounded-full border px-2 py-0.5 font-semibold"
                    :class="
                        tag.includes('1')
                            ? 'border-amber-300/60 bg-amber-50 text-amber-800'
                            : 'border-stone-200 bg-stone-100 text-stone-600'
                    "
                >
                    {{ tag }}
                </span>
                <span v-else>Dokumen</span>
                <!-- <span class="capitalize">{{ type }}</span> -->
            </div>
        </div>

        <a
            :href="`/storage/${url}`"
            target="_blank"
            :aria-label="`Lihat dokumen ${name}${tag ? ', ' + tag : ''}`"
            class="flex-shrink-0 rounded-lg border border-stone-200 bg-stone-100 px-4 py-2 text-sm font-semibold text-green-600 transition-colors hover:bg-green-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
        >
            Lihat
        </a>
    </div>
</template>
