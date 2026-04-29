<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import ActionButtons from "@/Components/ActionButtons.vue";

const columns = [
    { header: "Nama Kategori", key: "nama_kategori" },
    { header: "Nama Seksi", key: "seksi.nama_seksi" },
    { header: "Aksi", key: "actions" },
];

const filtersort = [
    { label: "Nama Kategori", value: "nama_kategori" },
    { label: "Nama Seksi", value: "seksi.nama_seksi" },
];

console.log(usePage().props);
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Kategori Data" />
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow p-5">
                    <h1 class="text-2xl font-bold mb-4">Kategori Data</h1>
                    <Table
                        :columns="columns"
                        :rows="usePage().props.listKategoriData.data || []"
                        :list_seksi="usePage().props.listSeksi || []"
                        :filterSortOptions="filtersort"
                        :link="usePage().props.listKategoriData.links"
                        :meta="usePage().props.listKategoriData"
                    >
                        <template #cell-seksi.nama_seksi="{ row }">
                            {{ row.seksi?.nama_seksi }}
                        </template>
                        <template #cell-actions="{ row }">
                            <ActionButtons
                                :visibleButtons="['edit', 'delete']"
                                :item="row"
                                @edit=""
                                @delete=""
                            />
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
