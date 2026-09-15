<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import Table from "@/Components/Table.vue";
import ActionButtons from "@/Components/ActionButtons.vue";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";
import ModalHeadnessUI from "@/Components/ModalHeadnessUI.vue";
import { Link } from "lucide-vue-next";
import Loading from "@/Components/Loading.vue";
const columns = [
    { header: "Nama Informasi", key: "nama_informasi", width: "25%" },
    {
        header: "Kategori Informasi",
        key: "kategori",
        width: "15%",
        classTd: "text-center",
    },
    { header: "Detail Informasi", key: "detail_informasi", width: "25%" },
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
    { label: "Status Informasi", value: "status" },
];

const formatDate = (dateStr) => {
    if (!dateStr) return "-";
    const date = new Date(dateStr);
    return date.toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "2-digit", //long format: "long", short format: "short"
        year: "numeric",
        timeZone: "Asia/Jakarta",
    });
};

const handleDelete = async (item) => {
    const result = await Swal.fire({
        title: "Apakah Anda Yakin Ingin Menghapus?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#166534",
        confirmButtonText: "Ya, hapus!",
    });
    if (result.isConfirmed) {
        pageLoading.value = true;
        await router.delete(route("admin.ppid.tambah-informasi.delete", item), {
            onError: () => {
                pageLoading.value = false;
            },
            onSuccess: () => {
                pageLoading.value = false;
            },
            onFinish: () => {
                pageLoading.value = false;
            },
        });
    }
};

const pageLoading = ref(true);
onMounted(() => {
    pageLoading.value = false;
});
watch(
    () => usePage().props.flash,
    (flash) => {
        if (flash?.success) {
            Swal.fire({
                icon: "success",
                title: "Sukses",
                text: flash.success,
            });
        } else if (flash?.error) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: flash.error,
            });
        }
        usePage().props.flash.success = null;
        usePage().props.flash.error = null;
    },
    { immediate: true },
);
// console.log(usePage().props);
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Tambah Informasi" />
        <Loading v-if="pageLoading" />
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow p-5">
                    <div
                        class="mb-4 flex flex-col sm:flex-row justify-between gap-3"
                    >
                        <h1 class="text-2xl font-bold mb-4">List Informasi</h1>
                        <a
                            :href="
                                route('admin.ppid.tambah-informasi.tambah-data')
                            "
                        >
                            <PrimaryButtonAdmin>
                                + Tambah Data
                            </PrimaryButtonAdmin>
                        </a>
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
                            <span class="font-medium"
                                >{{ row.nama_informasi }} Tahun
                                {{ row.tahun }}</span
                            >
                            <span class="block text-gray-500 text-xs">
                                {{ row.seksi?.nama_seksi ?? "" }}
                                {{
                                    row.unit_kerja !== null &&
                                    row.unit_kerja !== undefined
                                        ? "Unit Kerja: " + row.unit_kerja
                                        : ""
                                }}</span
                            >
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
                        <template #cell-detail_informasi="{ row }">
                            <span class="block text-gray-500 text-xs">
                                Waktu Pembuatan:
                                {{
                                    formatDate(row.waktu_pembuatan) ?? "-"
                                }}</span
                            >
                            <span class="block text-gray-500 text-xs">
                                Bentuk Dokumen:
                                {{
                                    row.bentuk_dokumen === "soft_copy"
                                        ? "Soft Copy"
                                        : row.bentuk_dokumen === "hard_copy"
                                          ? "Hard Copy"
                                          : row.bentuk_dokumen === "keduanya"
                                            ? "Keduanya"
                                            : "-"
                                }}</span
                            >
                        </template>
                        <template #cell-actions="{ row }">
                            <div class="flex gap-2 justify-center">
                                <ActionButtons
                                    :visibleButtons="['edit', 'delete']"
                                    :item="row"
                                    @edit="
                                        () =>
                                            router.get(
                                                route(
                                                    'admin.ppid.tambah-informasi.edit',
                                                    row,
                                                ),
                                            )
                                    "
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
