<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import ActionButtons from "@/Components/ActionButtons.vue";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";
import ModalHeadnessUI from "@/Components/ModalHeadnessUI.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectButton from "@/Components/SelectButton.vue";
import InputError from "@/Components/InputError.vue";
import ComboBox from "@/Components/ComboBox.vue";
import ModalGroupKategori from "@/Components/ModalGroupKategori.vue";
const columns = [
    { header: "Nama Informasi", key: "nama_informasi", width: "25%" },
    {
        header: "Kategori Informasi",
        key: "kategori",
        width: "15%",
        classTd: "text-center",
    },
    { header: "Waktu Informasi", key: "waktu_pembuatan", width: "25%" },
    {
        header: "Status Informasi",
        key: "status",
        class: "text-center",
        width: "15%",
        classTd: "text-center",
    },
    { header: "Aksi", key: "actions", width: "20%", class: "text-center" },
];

const filtersort = [
    { label: "Nama Informasi", value: "nama_informasi" },
    { label: "Kategori Informasi", value: "kategori" },
    { label: "Waktu Informasi", value: "waktu_pembuatan" },
    { label: "Status Informasi", value: "status" },
];

const formatDate = (dateStr) => {
    if (!dateStr) return "-";
    const date = new Date(dateStr);
    return date.toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "long",
        year: "numeric",
        timeZone: "Asia/Jakarta",
    });
};
console.log(usePage().props);
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Tambah Informasi" />
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow p-5">
                    <div
                        class="mb-4 flex flex-col sm:flex-row justify-between gap-3"
                    >
                        <h1 class="text-2xl font-bold mb-4">List Informasi</h1>
                        <PrimaryButtonAdmin> + Tambah Data</PrimaryButtonAdmin>
                    </div>
                    <Table
                        :columns="columns"
                        :rows="usePage().props.listInformasi.data || []"
                        :list_seksi="usePage().props.listSeksi || []"
                        :filterSortOptions="filtersort"
                        :links="usePage().props.listInformasi.links"
                        :meta="usePage().props.listInformasi"
                    >
                        <template #cell-nama_informasi="{ row }">
                            <span class="font-medium">{{
                                row.nama_informasi
                            }}</span>
                            <span class="block text-gray-500 text-xs">
                                {{ row.seksi?.nama_seksi ?? "-" }}
                            </span>
                        </template>
                        <template #cell-kategori="{ row }">
                            <span class="font-medium">{{
                                row.kategori === "serta_merta"
                                    ? "Serta Merta"
                                    : row.kategori === "setiap_saat"
                                      ? "Setiap Saat"
                                      : row.kategori === "dikecualikan"
                                        ? "Dikecualikan"
                                        : row.kategori === "berkala"
                                          ? "Berkala"
                                          : "-"
                            }}</span>
                        </template>
                        <template #cell-status="{ row }">
                            <span
                                :class="{
                                    'bg-green-100 text-green-800':
                                        row.status === 'dapat_diakses',
                                    'bg-red-100 text-red-800':
                                        row.status === 'dikecualikan',
                                }"
                                class="px-2 py-1 rounded-full text-center text-xs font-semibold"
                            >
                                {{
                                    row.status === "dapat_diakses"
                                        ? "Dapat Diakses"
                                        : ("Dikecualikan" ?? "-")
                                }}
                            </span>
                        </template>
                        <template #cell-waktu_pembuatan="{ row }">
                            <span class="block text-gray-500 text-xs">
                                Waktu Pembuatan:
                                {{
                                    formatDate(row.waktu_pembuatan) ?? "-"
                                }}</span
                            >
                            <span class="block text-gray-500 text-xs">
                                Waktu Penguasaan:
                                {{ formatDate(row.waktu_penguasaan) ?? "-" }}
                            </span>
                        </template>
                        <template #cell-actions="{ row }">
                            <div class="flex gap-2 justify-center">
                                <ActionButtons
                                    :visibleButtons="['edit', 'delete']"
                                    :item="row"
                                    @edit="() => openModal(row)"
                                    @delete="() => handleDelete(row)"
                                />
                            </div>
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
