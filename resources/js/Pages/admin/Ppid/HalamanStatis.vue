<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import Table from "@/Components/Table.vue";
import ActionButtons from "@/Components/ActionButtons.vue";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";
import { Link } from "lucide-vue-next";
import Loading from "@/Components/Loading.vue";
const columns = [
    { header: "Nama Menu", key: "nama_menu", width: "25%" },
    {
        header: "Halaman",
        key: "halaman",
        width: "10%",
        classTd: "text-center",
    },
    { header: "Status", key: "status", width: "10%", classTd: "text-center" },
    { header: "Aksi", key: "actions", width: "20%", class: "text-center" },
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
        title: "Apakah Anda Yakin Ingin Menghapus Halaman Statis?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#166534",
        confirmButtonText: "Ya, hapus!",
    });
    if (result.isConfirmed) {
        pageLoading.value = true;
        await router.delete(
            route("admin.ppid.halaman-statis.destroy", item.halaman_statis),
            {
                onError: () => {
                    pageLoading.value = false;
                },
                onSuccess: () => {
                    pageLoading.value = false;
                },
                onFinish: () => {
                    pageLoading.value = false;
                },
            },
        );
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
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Halaman Statis" />
        <Loading v-if="pageLoading" />
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow p-5">
                    <div
                        class="mb-4 flex flex-col sm:flex-row justify-between gap-3"
                    >
                        <h1 class="text-2xl font-bold mb-4">
                            List Halaman Statis
                        </h1>
                    </div>
                    <Table
                        :columns="columns"
                        :rows="usePage().props.listMenuStatis?.data || []"
                        :show-seksi-filter="false"
                        :show-sort="false"
                        :links="usePage().props.listMenuStatis?.links"
                        :meta="usePage().props.listMenuStatis"
                    >
                        <template #cell-halaman="{ row }">
                            <span
                                :class="{
                                    'bg-green-100 text-green-800':
                                        row.halaman_statis !== null,
                                    'bg-red-100 text-red-800':
                                        row.halaman_statis === null,
                                }"
                                class="px-2 py-1 rounded-full text-center text-xs font-semibold"
                            >
                                {{
                                    row.halaman_statis !== null
                                        ? "Tersedia"
                                        : ("Belum Tersedia!" ?? "-")
                                }}
                            </span>
                        </template>
                        <template #cell-status="{ row }">
                            <span
                                :class="{
                                    'bg-green-100 text-green-800':
                                        row.is_active,
                                    'bg-red-100 text-red-800':
                                        row.is_active === false,
                                }"
                                class="px-2 py-1 rounded-full text-center text-xs font-semibold"
                            >
                                {{
                                    row.is_active === true
                                        ? "Aktif"
                                        : ("Tidak Aktif" ?? "-")
                                }}
                            </span>
                        </template>
                        <template #cell-actions="{ row }">
                            <div
                                v-if="row.halaman_statis !== null"
                                class="flex gap-2 justify-center"
                            >
                                <ActionButtons
                                    :visibleButtons="['edit', 'delete']"
                                    :item="row"
                                    @edit="
                                        () =>
                                            router.get(
                                                route(
                                                    'admin.ppid.halaman-statis.edit',
                                                    row,
                                                ),
                                            )
                                    "
                                    @delete="() => handleDelete(row)"
                                />
                            </div>
                            <div v-else class="flex gap-2 justify-center">
                                <a
                                    :href="
                                        route(
                                            'admin.ppid.halaman-statis.create',
                                            row,
                                        )
                                    "
                                >
                                    <PrimaryButtonAdmin> + </PrimaryButtonAdmin>
                                </a>
                            </div>
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
