<script setup>
import { ChevronDown } from "lucide-vue-next";

defineProps({
    label: {
        type: String,
        default: "Menu",
    },
    items: {
        type: Array,
        default: () => [],
        // Format: [{ name: 'Item', link: '#', icon: FileText }]
    },
    isContrastMode: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="relative group inline-block">
        <button
            :class="
                isContrastMode
                    ? 'hover:bg-zinc-800 text-white'
                    : 'hover:bg-[#E4F5EC] hover:text-[#085239] text-gray-800'
            "
            class="px-3.5 py-1.5 rounded-full transition-colors flex items-center gap-1.5 cursor-pointer font-semibold text-sm"
        >
            <slot name="trigger">
                {{ label }}
            </slot>

            <ChevronDown
                class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180 shrink-0"
            />
        </button>

        <div
            class="absolute left-0 mt-2 w-52 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 rounded-xl shadow-xl border overflow-hidden z-50"
            :class="
                isContrastMode
                    ? 'bg-black border-yellow-300'
                    : 'bg-white border-[#E6EFE9]'
            "
        >
            <slot name="menu">
                <a
                    v-for="(item, index) in items"
                    :key="index"
                    :href="item.link"
                    :class="
                        isContrastMode
                            ? 'hover:bg-zinc-800 text-white'
                            : 'hover:bg-[#E4F5EC] hover:text-[#085239] text-gray-700'
                    "
                    class="flex items-center gap-2.5 px-4 py-2.5 text-sm transition-colors"
                >
                    <component
                        :is="item.icon"
                        v-if="item.icon"
                        class="w-4 h-4 shrink-0"
                    />
                    <span>{{ item.name }}</span>
                </a>
            </slot>
        </div>
    </div>
</template>
