<script setup>
import { computed } from "vue";
const props = defineProps({
    value: {
        type: String,
    },
    logo: {
        type: [Object, Function],
    },
    href: {
        type: String,
        // required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() => ({
    "flex items-center p-2 rounded-lg group cursor-pointer text-white dark:text-white hover:bg-green-700 dark:hover:bg-green-800 transition-colors duration-150 ease-in-out": true,
    "bg-green-700 dark:bg-green-800 text-yellow-400": props.active,
}));
</script>
<template>
    <a :href="href" :class="classes">
        <component
            :is="logo"
            class="w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"
        />
        <span
            class="ms-3 group-hover:text-white dark:group-hover:text-yellow-400"
            >{{ value }}</span
        >
    </a>
</template>
