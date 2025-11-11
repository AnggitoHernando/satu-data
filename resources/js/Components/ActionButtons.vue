<script setup>
import {
    PencilLine,
    Trash,
    Globe,
    GlobeLock,
    RotateCcw,
} from "lucide-vue-next";
const props = defineProps({
    item: Object,
    visibleButtons: {
        type: Array,
        default: () => ["edit", "delete", "status", "retry"], // default: semua muncul
    },
});
const emit = defineEmits(["edit", "delete", "toggleStatus", "retryUpload"]);
</script>

<template>
    <div class="flex gap-2 justify-center">
        <button
            v-if="visibleButtons.includes('edit')"
            class="bg-blue-500 text-white px-2 py-1 rounded"
            @click="emit('edit', item)"
            title="Edit Data"
        >
            <component
                :is="PencilLine"
                class="w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"
            />
        </button>
        <button
            v-if="visibleButtons.includes('delete')"
            class="bg-red-500 text-white px-2 py-1 rounded"
            @click="emit('delete', item)"
            title="Hapus Data"
        >
            <component
                :is="Trash"
                class="w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"
            />
        </button>
        <template v-if="visibleButtons.includes('status')">
            <button
                v-if="item.status_data === 'private'"
                class="bg-yellow-500 text-white px-2 py-1 rounded"
                @click="emit('toggleStatus', item, 'publik')"
                title="Set Data Publik"
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
                title="Set Data Private"
            >
                <component
                    :is="GlobeLock"
                    class="w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"
                />
            </button>
        </template>
        <button
            v-if="
                visibleButtons.includes('retry') &&
                item.status_upload !== 'success' &&
                item.status_upload !== 'processing'
            "
            class="bg-green-500 text-white px-2 py-1 rounded"
            @click="emit('retryUpload', item)"
            title="ulangi upload"
        >
            <component
                :is="RotateCcw"
                class="w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"
            />
        </button>
    </div>
</template>
