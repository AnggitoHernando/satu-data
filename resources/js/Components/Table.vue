<script setup>
import { ref, computed, watch } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import { watchDebounced } from "@vueuse/core";
import {
    LucideSearch,
    LucideFilter,
    LucideArrowUpDown,
    LucideArrowUp,
    LucideRotateCcw,
} from "lucide-vue-next";
const props = defineProps({
    columns: {
        type: Array,
        required: true,
    },
    rows: {
        type: Array,
    },
    loading: Boolean,
    list_seksi: {
        type: Array,
        default: () => [],
    },
    userRole: {
        type: String,
        default: "",
    },
    filterSortOptions: {
        type: Array,
        default: () => [],
    },
    links: {
        type: Array,
        default: () => [],
    },
    meta: {
        type: Object,
        default: () => ({
            total: 0,
            per_page: 0,
        }),
    },
});

const search = ref(
    new URLSearchParams(window.location.search).get("search") || "",
);
const sortBy = ref(
    new URLSearchParams(window.location.search).get("sortBy") || "",
);
const sortDir = ref(
    new URLSearchParams(window.location.search).get("sortDir") || "desc",
);
const selectedSeksiFilter = ref(
    new URLSearchParams(window.location.search).get("seksi_id") || "",
);

const updateFilters = () => {
    router.get(
        window.location.pathname,
        {
            search: search.value,
            seksi_id: selectedSeksiFilter.value,
            sortBy: sortBy.value,
            sortDir: sortDir.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};
watchDebounced(search, updateFilters, { debounce: 500 });

watch([selectedSeksiFilter, sortBy, sortDir], () => {
    updateFilters();
});

const resetFilters = () => {
    search.value = "";
    selectedSeksiFilter.value = "";
    sortBy.value = "";
    sortDir.value = "desc";
};
</script>
<template>
    <div>
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 bg-gray-50 px-4 py-3 border border-gray-200 rounded-md mb-3 shadow-sm"
        >
            <!-- Left filters -->
            <div class="flex flex-wrap items-center gap-3 w-full">
                <!-- Search -->
                <div class="relative w-full sm:w-auto">
                    <LucideSearch
                        class="absolute left-2 top-2.5 text-gray-400"
                        :size="18"
                    />
                    <input
                        v-model="search"
                        type="text"
                        name="search"
                        placeholder="Cari data..."
                        class="pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm w-full sm:w-auto"
                    />
                </div>

                <!-- Filter Seksi -->
                <div class="relative w-full sm:w-auto">
                    <LucideFilter
                        class="absolute left-2 top-2.5 text-gray-400"
                        :size="18"
                    />
                    <select
                        name="selectSeksiFilter"
                        v-model="selectedSeksiFilter"
                        class="pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white w-full sm:w-auto"
                    >
                        <option value="">Semua Seksi</option>
                        <option
                            v-for="seksi in list_seksi"
                            :key="seksi.id"
                            :value="String(seksi.id)"
                        >
                            {{ seksi.nama_seksi }}
                        </option>
                    </select>
                </div>

                <!-- Sort By -->
                <div class="relative w-full sm:w-auto">
                    <LucideArrowUpDown
                        class="absolute left-2 top-2.5 text-gray-400"
                        :size="18"
                    />
                    <select
                        name="selectSortBy"
                        v-model="sortBy"
                        class="pl-8 pr-8 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white w-full sm:w-auto"
                    >
                        <option disabled value="">Urutkan Berdasarkan</option>
                        <option
                            v-for="val in filterSortOptions"
                            :key="val.value"
                            :value="val.value"
                        >
                            {{ val.label }}
                        </option>
                    </select>
                </div>

                <!-- Sort Direction -->
                <button
                    @click="sortDir = sortDir === 'asc' ? 'desc' : 'asc'"
                    class="flex items-center gap-1 px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-100 transition w-full sm:w-auto"
                    :title="`Urutan: ${sortDir.toUpperCase()}`"
                >
                    <LucideArrowUp
                        :class="{
                            'rotate-180': sortDir === 'desc',
                        }"
                        :size="16"
                    />
                    <span class="text-sm">{{
                        sortDir === "asc" ? "Naik" : "Turun"
                    }}</span>
                </button>
            </div>

            <!-- Reset -->
            <button
                @click="resetFilters"
                class="flex items-center gap-1 px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100 w-full md:w-auto"
            >
                <LucideRotateCcw :size="16" />
                <span>Reset</span>
            </button>
        </div>

        <div class="w-full">
            <!-- DESKTOP TABLE -->
            <div class="hidden md:block overflow-x-auto">
                <table class="table-fixed border border-gray-300 w-full">
                    <thead>
                        <tr>
                            <th
                                v-for="col in columns"
                                :key="col.key"
                                class="border px-4 py-2"
                                :class="col.class"
                                :style="{ width: col.width }"
                            >
                                {{ col.header }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading">
                            <td
                                :colspan="columns.length"
                                class="text-center py-2"
                            >
                                Loading...
                            </td>
                        </tr>

                        <tr v-else-if="rows.length === 0">
                            <td
                                :colspan="columns.length"
                                class="text-center py-2"
                            >
                                Data Tidak Ditemukan
                            </td>
                        </tr>

                        <tr
                            v-else
                            v-for="row in rows"
                            :key="row.id"
                            class="capitalize border-t hover:bg-gray-50"
                        >
                            <td
                                v-for="col in columns"
                                :key="col.key"
                                class="px-4 py-3 whitespace-normal break-words border p-2 capitalize"
                            >
                                <slot :name="`cell-${col.key}`" :row="row">
                                    {{ row[col.key] }}
                                </slot>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4 mb-3 flex items-center justify-end gap-2">
            <template v-for="(link, i) in links" :key="i">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    v-html="link.label"
                    class="px-3 py-1 border rounded text-sm"
                    :class="
                        link.active
                            ? 'bg-primary text-white'
                            : 'hover:bg-gray-100'
                    "
                />
                <span
                    v-else
                    v-html="link.label"
                    class="px-3 py-1 border rounded text-gray-400 cursor-not-allowed"
                />
            </template>
        </div>
    </div>
</template>
