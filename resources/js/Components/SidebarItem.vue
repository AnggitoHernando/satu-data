<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const props = defineProps({
    title: String,
    icon: {
        type: [Object, Function],
    },
    items: Array,
    active: Boolean,
});

const isOpen = ref(props.active);

const toggle = () => {
    isOpen.value = !isOpen.value;
};

const isSubMenuActive = (routePath) => {
    return usePage().url.startsWith(routePath);
};
</script>

<template>
    <div class="w-full">
        <button
            @click="toggle"
            :class="[
                'w-full flex items-center justify-between px-2 py-3 transition-colors duration-200 group hover:bg-green-700 dark:hover:bg-green-800 hover:rounded-lg',
                isOpen || active
                    ? 'text-white bg-primary/20 border-l-4 border-primary'
                    : 'text-white hover:text-white hover:bg-green-700 dark:hover:bg-green-800',
            ]"
        >
            <div class="flex items-center gap-3">
                <component
                    :is="icon"
                    :class="['w-5 h-5 transition-transform duration-200']"
                />
                <span class="font-medium">{{ title }}</span>
            </div>

            <svg
                :class="[
                    'w-4 h-4 transition-transform duration-200',
                    isOpen ? 'rotate-180' : '',
                ]"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                />
            </svg>
        </button>

        <div
            v-show="isOpen"
            class="bg-green-900 overflow-hidden transition-all"
        >
            <template v-for="item in items" :key="item.label">
                <Link
                    :href="route(item.route)"
                    :class="[
                        'block pl-11 pr-4 py-2 transition-colors duration-200',
                        isSubMenuActive(route(item.route))
                            ? 'text-white font-semibold'
                            : 'text-white hover:text-yellow-400',
                    ]"
                >
                    {{ item.label }}
                </Link>
            </template>
        </div>
    </div>
</template>
