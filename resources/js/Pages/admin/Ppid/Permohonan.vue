<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import {
    Search,
    Clock,
    CheckCircle2,
    RotateCw,
    MessageSquare,
    FileCheck,
    XCircle,
} from "lucide-vue-next";
import StatusStepper from "@/Components/StatusStepper.vue";
import Table from "@/Components/Table.vue";
import ActionButtons from "@/Components/ActionButtons.vue";

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

const handleFilterStatus = (statusKey) => {
    console.log(statusKey);
    usePage().props.filters.status = statusKey;
    console.log(usePage().props.filters);
    router.get(
        route("admin.permohonan.index"),
        { ...usePage().props.filters },
        { preserveState: true, replace: true },
    );
};

const columns = [
    {
        header: "No. Registrasi & Pemohon",
        key: "nomor_registrasi",
        width: "25%",
    },
    {
        header: "Jenis",
        key: "jenis",
        width: "15%",
        classTd: "text-center",
    },
    { header: "Tujuan Penggunaan", key: "tujuan_penggunaan", width: "25%" },
    {
        header: "Cara Mendapatkan",
        key: "cara_mendapatkan",
        width: "15%",
        classTd: "text-center",
    },
    {
        header: "Tanggal Masuk",
        key: "tanggal_tanggapan",
        width: "15%",
    },
    {
        header: "Status Saat Ini",
        key: "status",
        class: "text-center",
        width: "15%",
        classTd: "text-center",
    },
];

const statusConfig = {
    diajukan: {
        label: "Diajukan",
        color: "bg-amber-100 text-amber-800 border-amber-300",
        icon: Clock,
    },
    diverifikasi: {
        label: "Diverifikasi",
        color: "bg-blue-100 text-blue-800 border-blue-300",
        icon: CheckCircle2,
    },
    diproses: {
        label: "Diproses",
        color: "bg-indigo-100 text-indigo-800 border-indigo-300",
        icon: RotateCw,
    },
    ditanggapi: {
        label: "Ditanggapi",
        color: "bg-purple-100 text-purple-800 border-purple-300",
        icon: MessageSquare,
    },
    selesai: {
        label: "Selesai",
        color: "bg-emerald-100 text-emerald-800 border-emerald-300",
        icon: FileCheck,
    },
    ditolak: {
        label: "Ditolak",
        color: "bg-rose-100 text-rose-800 border-rose-300",
        icon: XCircle,
    },
};

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
        <Head title="Permohonan" />
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <StatusStepper
                    :statusConfig="statusConfig"
                    :statusCounts="usePage().props.statusCounts"
                    :totalRecords="usePage().props.permohonanList.total"
                    :selectedStatus="usePage().props.filters.status || 'semua'"
                    @select-status="handleFilterStatus"
                >
                    <template #title>
                        Alur Status Permohonan & Keberatan
                    </template>
                </StatusStepper>
                <div class="overflow-x-auto">
                    <Table
                        :columns="columns"
                        :rows="usePage().props.permohonanList.data"
                        :links="usePage().props.permohonanList.links"
                        :meta="usePage().props.permohonanList.meta"
                        :show-seksi-filter="false"
                        :show-sort="false"
                        :row-href="
                            (row) => route('admin.permohonan.show', row.id)
                        "
                    >
                        <template #cell-nomor_registrasi="{ row }">
                            <p class="font-bold text-slate-900 font-mono">
                                {{ row.nomor_registrasi }}
                            </p>

                            <p class="text-slate-600 font-medium">
                                {{ row.nama_lengkap }}
                            </p>

                            <p class="text-[0.68rem] text-slate-400">
                                {{ row.email }}
                            </p>
                        </template>

                        <template #cell-jenis="{ row }">
                            <span
                                :class="{
                                    'bg-green-100 text-green-800':
                                        row.jenis === 'informasi',
                                    'bg-red-100 text-red-800':
                                        row.jenis === 'keberatan',
                                }"
                                class="px-2 py-1 rounded-full text-center text-xs font-semibold"
                            >
                                {{ row.jenis }}
                            </span>
                        </template>
                        <template #cell-tujuan_penggunaan="{ row }">
                            <span class="font-medium">{{
                                row.tujuan_penggunaan
                            }}</span>
                        </template>
                        <template #cell-cara_mendapatkan="{ row }">
                            <span class="font-medium">{{
                                row.cara_mendapatkan_label
                            }}</span>
                        </template>
                        <template #cell-tanggal_tanggapan="{ row }">
                            <span class="font-medium">{{
                                formatDate(row.created_at)
                            }}</span>
                        </template>
                        <template #cell-status="{ row }">
                            <span
                                :class="[
                                    statusConfig[row.status].color,
                                    'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[0.7rem] font-bold border',
                                ]"
                            >
                                <component
                                    :is="statusConfig[row.status].icon"
                                    class="w-3.5 h-3.5"
                                />
                                {{ statusConfig[row.status].label }}
                            </span>
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
