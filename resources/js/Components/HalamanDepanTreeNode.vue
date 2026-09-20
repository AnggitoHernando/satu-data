<script setup>
import { ref, defineEmits, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";

const props = defineProps({
    node: {
        type: Object,
        required: true,
    },
    currentIndex: {
        type: Number,
        default: 0,
    },
    totalCount: {
        type: Number,
        default: 0,
    },
});

const emit = defineEmits(["setLoading"]);

const open = ref(true);
const isGroup =
    props.node.children_recursive && props.node.children_recursive.length > 0;
</script>

<template>
    <div>
        <div
            class="group flex items-center gap-2 rounded-md px-2 py-2 hover:bg-slate-50"
        >
            <div v-if="props.currentIndex !== 0" class="text-sm">
                {{ props.currentIndex }}
            </div>
            <button
                class="w-5 flex-none text-xs text-slate-400"
                :class="{ invisible: !isGroup }"
                @click="open = !open"
            >
                {{ isGroup ? (open ? "▾" : "▸") : "•" }}
            </button>

            <div class="min-w-0 flex-1 truncate text-sm font-semibold">
                {{ node.nama_menu }}
            </div>

            <span
                v-if="!isGroup"
                class="flex-none whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-semibold"
                :class="
                    isGroup
                        ? 'bg-blue-50 text-blue-700'
                        : 'bg-emerald-50 text-emerald-700'
                "
            >
                <a :href="`/${node.full_path}`" target="_blank">Lihat</a>
            </span>
        </div>

        <div
            v-if="isGroup && open"
            class="ml-[26px] border-l border-slate-200 pl-1.5"
        >
            <HalamanDepanTreeNode
                v-for="child in node.children_recursive"
                :key="child.id"
                :node="child"
            />
        </div>
    </div>
</template>
