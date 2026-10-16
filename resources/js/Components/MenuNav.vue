<script setup>
import MenuItem from "./MenuItem.vue";

defineProps({
    items: { type: Array, required: true }, // listMenu
    selected: { type: String, default: null }, // selectedMenu (slug)
    label: { type: String, default: "Navigasi laporan" },
});
</script>

<template>
    <nav
        :aria-label="label"
        class="flex gap-2 overflow-x-auto md:sticky md:top-5 md:h-fit md:flex-col md:overflow-visible"
    >
        <MenuItem
            v-for="menu in items"
            :key="menu.id"
            :menu="menu"
            :selected="selected"
        />
    </nav>
</template>
