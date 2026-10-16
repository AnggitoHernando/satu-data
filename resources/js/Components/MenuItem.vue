<script setup>
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { ChevronDown, ChevronRight } from "lucide-vue-next";

// Komponen ini memanggil dirinya sendiri (rekursif) -> kedalaman child tidak terbatas.
defineOptions({ name: "MenuItem" });

const props = defineProps({
    menu: { type: Object, required: true },
    selected: { type: String, default: null }, // slug menu yang aktif
    level: { type: Number, default: 0 },
    defaultOpen: { type: Boolean, default: true }, // true = semua menu terbuka dulu
});

const children = computed(() => props.menu.children_recursive ?? []);
const hasChildren = computed(() => children.value.length > 0);
const isActive = computed(() => props.selected === props.menu.slug);

// Semua menu yang punya child terbuka di awal, lalu bisa di-collapse manual.
// Jika defaultOpen=false, hanya cabang yang berisi menu aktif yang terbuka.
const containsSelected = (node) =>
    node.slug === props.selected ||
    (node.children_recursive ?? []).some(containsSelected);

const open = ref(
    hasChildren.value && (props.defaultOpen || containsSelected(props.menu)),
);

const href = computed(() => {
    const path = props.menu.full_path ?? "";
    return path.startsWith("/") ? path : `/${path}`;
});

const iconSize = computed(() =>
    props.level === 0 ? "h-4 w-4" : "h-3.5 w-3.5",
);

const activeClass = "bg-green-600 text-white shadow-md shadow-green-900/20";
const idleClass =
    "text-stone-500 hover:bg-stone-100 hover:text-stone-900 transition";
const baseClass =
    "w-full flex items-start justify-between gap-2 rounded-xl px-4 py-3 text-sm font-semibold text-left whitespace-normal break-words focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600";
</script>

<template>
    <Link
        v-if="!hasChildren"
        :href="href"
        :class="[baseClass, isActive ? activeClass : idleClass]"
        :aria-current="isActive ? 'page' : undefined"
    >
        <span class="min-w-0 flex-1 leading-snug break-words">
            {{ menu.nama_menu }}
        </span>
        <ChevronRight
            aria-hidden="true"
            :class="[iconSize, 'shrink-0 mt-0.5']"
        />
    </Link>

    <div v-else class="relative flex w-full flex-col gap-1">
        <button
            type="button"
            :class="[baseClass, isActive ? activeClass : idleClass]"
            :aria-expanded="open"
            @click="open = !open"
        >
            <span class="min-w-0 flex-1 leading-snug break-words">
                {{ menu.nama_menu }}
            </span>
            <component
                :is="open ? ChevronDown : ChevronRight"
                aria-hidden="true"
                :class="[iconSize, 'shrink-0 mt-0.5']"
            />
        </button>

        <div
            v-if="open"
            :class="[
                'flex min-w-0 flex-col gap-1',
                level === 0 ? 'ml-4 pl-2 border-l-2 border-stone-200' : 'ml-3',
            ]"
        >
            <MenuItem
                v-for="child in children"
                :key="child.id"
                :menu="child"
                :selected="selected"
                :level="level + 1"
                :default-open="defaultOpen"
            />
        </div>
    </div>
</template>
