<script setup>
import { PencilLine, Trash } from "lucide-vue-next";
const props = defineProps({
    item: Object,
    onEdit: Function,
    onDelete: Function,
});
</script>

<template>
    <div class="flex gap-2 justify-center">
        <button
            class="bg-blue-500 text-white px-2 py-1 rounded"
            @click="onEdit(item)"
        >
            <component
                :is="PencilLine"
                class="w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"
            />
        </button>
        <button
            class="bg-red-500 text-white px-2 py-1 rounded"
            @click="onDelete(item.id)"
        >
            <component
                :is="Trash"
                class="w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"
            />
        </button>
    </div>
</template>
