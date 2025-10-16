<script setup>
import { PencilLine, Trash, Globe, GlobeLock } from "lucide-vue-next";
const props = defineProps({
    item: Object,
});
const emit = defineEmits(["edit", "delete", "toggleStatus"]);
</script>

<template>
    <div class="flex gap-2 justify-center">
        <button
            class="bg-blue-500 text-white px-2 py-1 rounded"
            @click="emit('edit', item)"
        >
            <component
                :is="PencilLine"
                class="w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"
            />
        </button>
        <button
            class="bg-red-500 text-white px-2 py-1 rounded"
            @click="emit('delete', item.id, item.judul_data)"
        >
            <component
                :is="Trash"
                class="w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"
            />
        </button>
        <button
            v-if="item.status_data === 'private'"
            class="bg-yellow-500 text-white px-2 py-1 rounded"
            @click="emit('toggleStatus', item, 'publik')"
        >
            <component
                :is="Globe"
                class="w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"
            />
        </button>
        <button
            v-else
            class="bg-yellow-500 text-white px-2 py-1 rounded"
            @click="emit('toggleStatus', item, 'private')"
        >
            <component
                :is="GlobeLock"
                class="w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"
            />
        </button>
    </div>
</template>
