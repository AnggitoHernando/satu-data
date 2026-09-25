<script setup>
const props = defineProps({
    statusConfig: { type: Object, required: true },
    statusCounts: { type: Object, default: () => ({}) },
    totalRecords: { type: Number, default: 0 },
    selectedStatus: { type: String, default: "semua" },
});

defineEmits(["select-status"]);
</script>

<template>
    <section class="mb-8">
        <div class="flex items-center justify-between mb-3">
            <h2
                class="text-xs font-bold uppercase tracking-wider text-slate-500"
            >
                <slot name="title" />
            </h2>
            <span class="text-xs font-semibold text-slate-500"
                >Total Database: {{ totalRecords }} Data</span
            >
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
            <div
                v-for="(cfg, key) in statusConfig"
                :key="key"
                @click="$emit('select-status', key)"
                :class="[
                    'p-3 bg-white rounded-2xl border transition-all cursor-pointer shadow-sm flex items-center gap-2.5',
                    selectedStatus === key
                        ? 'border-[#0B6E4F] ring-2 ring-[#0B6E4F]/20'
                        : 'border-slate-200/80 hover:border-slate-300',
                ]"
            >
                <div
                    :class="[
                        cfg.color,
                        'w-8 h-8 rounded-xl flex items-center justify-center shrink-0 border',
                    ]"
                >
                    <component :is="cfg.icon" class="w-4 h-4" />
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800">
                        {{ cfg.label }}
                    </p>
                    <p class="text-[0.68rem] text-slate-500">
                        {{ statusCounts[key] || 0 }} Berkas
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
