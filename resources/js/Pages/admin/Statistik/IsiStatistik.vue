<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import { usePage, useForm, router } from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";
import ModalHeadnessUI from "@/Components/ModalHeadnessUI.vue";
import ActionButtons from "@/Components/ActionButtons.vue";
import StepPilihKategori from "@/Components/Statistik/StepPilihKategori.vue";
import StepPilihKategoriGroup from "@/Components/Statistik/StepPilihKategoriGroup.vue";
import StepNilaiTahun from "@/Components/Statistik/StepNilaiTahun.vue";

const columns = [
    {
        header: "Nama Kategori",
        key: "group_kategori_item.group_kategori.kategori_data.nama_kategori",
    },
    {
        header: "Group Kategori",
        key: "group_kategori_item.group_kategori.nama_group",
    },
    { header: "Nama Item", key: "group_kategori_item.nama_item" },
    { header: "Tahun", key: "tahun" },
    { header: "Value", key: "value" },
    { header: "Aksi", key: "actions" },
];

console.log(usePage().props);

const filtersort = [
    {
        label: "Nama Kategori",
        value: "group_kategori_item.group_kategori.kategori_data.nama_kategori",
    },
    {
        label: "Group Kategori",
        value: "group_kategori_item.group_kategori.nama_group",
    },
    { label: "Nama Item", value: "group_kategori_item.nama_item" },
    { label: "Tahun", value: "tahun" },
];

const searchFilters = ref("");
const modalMode = ref("create");
const judulModal = ref("");
const isOpen = ref(false);
const pageLoading = ref(true);
const step = ref(1);
const saving = ref(false);

const form = ref({
    kategori_id: null,
    kategori_nama: "",
    kategori_group_id: null,
    kategori_group_nama: "",
    kategori_group_item_id: null,
    kategori_group_item_nama: "",
    value: null,
    tahun: new Date().getFullYear(),
});

const statistikForm = useForm({
    group_kategori_item_id: null,
    value: null,
    tahun: null,
});

const reset = () => {
    step.value = 1;
    form.value = {
        kategori_id: null,
        kategori_nama: "",
        kategori_group_id: null,
        kategori_group_nama: "",
        kategori_group_item_id: null,
        kategori_group_item_nama: "",
        value: null,
        tahun: new Date().getFullYear(),
    };
};

const onKategoriNext = (data) => {
    form.value.kategori_id = data.id;
    form.value.kategori_nama = data.nama_kategori;
    step.value = 2;
};

const onGroupItemNext = (data) => {
    form.value.kategori_group_id = data.group_id;
    form.value.kategori_group_nama = data.group_nama;
    form.value.kategori_group_item_id = data.item_id;
    form.value.kategori_group_item_nama = data.item_nama;
    step.value = 3;
};

const steps = [
    { num: 1, label: "Kategori" },
    { num: 2, label: "Group & Item" },
    { num: 3, label: "Nilai & Tahun" },
];
const openModal = (item = null) => {
    if (item) {
        form.clearErrors();
        modalMode.value = "edit";
        Object.assign(form, item);
        judulModal.value = "Ubah Iisi Statistik";
    } else {
        modalMode.value = "create";
        // form.reset();
        judulModal.value = "Tambah Isi Statistik";
    }
    isOpen.value = true;
};

const onSubmit = (data) => {
    statistikForm.group_kategori_item_id = form.value.kategori_group_item_id;
    statistikForm.value = data.value;
    statistikForm.tahun = data.tahun;

    statistikForm.post(route("admin.statistik.isi-statistik.store"), {
        onError: () => {
            saving.value = false;
        },
        onSuccess: () => {
            saving.value = false;
            isOpen.value = false;
            reset();
            statistikForm.reset();
            console.log("Data submitted:", statistikForm);
        },
        onFinish: () => {
            saving.value = false;
        },
    });
};

const handleDelete = async (item) => {
    const result = await Swal.fire({
        title: "Apakah Anda yakin?",
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
            route("admin.statistik.isi-statistik.destroy", item),
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

onMounted(async () => {
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
        <Head title="Isi Statistik" />
        <div
            v-if="pageLoading"
            class="fixed inset-0 z-[60] bg-white/70 backdrop-blur-sm flex flex-col items-center justify-center"
        >
            <div
                class="w-10 h-10 border-4 border-green-500 border-t-transparent rounded-full animate-spin"
            ></div>
            <p class="mt-3 text-gray-600 text-sm font-medium animate-pulse">
                Loading
            </p>
        </div>
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow p-5">
                    <div
                        class="mb-4 flex flex-col sm:flex-row justify-between gap-3"
                    >
                        <h1 class="text-2xl font-bold mb-4">Isi Statistik</h1>
                        <PrimaryButtonAdmin @click="() => openModal(null)">
                            + Tambah Data</PrimaryButtonAdmin
                        >
                    </div>
                    <Table
                        :columns="columns"
                        :rows="usePage().props.isiStatistik.data || []"
                        :list_seksi="usePage().props.listSeksi || []"
                        :filterSortOptions="filtersort"
                        :links="usePage().props.isiStatistik.links"
                        :meta="usePage().props.isiStatistik"
                    >
                        <template
                            #cell-group_kategori_item.group_kategori.kategori_data.nama_kategori="{
                                row,
                            }"
                        >
                            <div>
                                <h1 class="font-medium">
                                    {{
                                        row.group_kategori_item.group_kategori
                                            .kategori_data.nama_kategori
                                    }}
                                </h1>
                                <span class="text-xs text-gray-400">{{
                                    row.group_kategori_item.group_kategori
                                        .kategori_data.seksi.nama_seksi
                                }}</span>
                            </div>
                        </template>
                        <template
                            #cell-group_kategori_item.group_kategori.nama_group="{
                                row,
                            }"
                        >
                            <span class="flex justify-center">{{
                                row.group_kategori_item.group_kategori
                                    .nama_group
                            }}</span>
                        </template>
                        <template #cell-group_kategori_item.nama_item="{ row }">
                            <span class="flex justify-center">{{
                                row.group_kategori_item.nama_item
                            }}</span>
                        </template>
                        <template #cell-tahun="{ row }">
                            <span class="flex justify-center">{{
                                row.tahun
                            }}</span>
                        </template>
                        <template #cell-value="{ row }">
                            <span class="flex justify-center">{{
                                row.value
                            }}</span>
                        </template>

                        <template #cell-actions="{ row }">
                            <ActionButtons
                                :visibleButtons="['delete']"
                                :item="row"
                                @edit="() => openModal(row)"
                                @delete="() => handleDelete(row)"
                            />
                        </template>
                    </Table>
                </div>
            </div>
        </div>
        <ModalHeadnessUI
            :open-modal="isOpen"
            @close="isOpen = false"
            :judul_modal="judulModal"
        >
            <div
                class="flex items-center px-5 py-3 border-b border-gray-100 gap-2"
            >
                <template v-for="(s, i) in steps" :key="s.num">
                    <div class="flex items-center gap-1.5">
                        <span
                            class="w-5 h-5 rounded-full flex items-center justify-center text-[11px] font-medium flex-shrink-0"
                            :class="{
                                'bg-primary text-white': step > s.num,
                                'bg-emerald-50 text-emerald-700 ring-1 ring-primary':
                                    step === s.num,
                                'bg-gray-100 text-gray-400': step < s.num,
                            }"
                        >
                            <template v-if="step > s.num">✓</template>
                            <template v-else>{{ s.num }}</template>
                        </span>
                        <span
                            class="text-xs"
                            :class="{
                                'text-gray-400': step < s.num,
                                'text-gray-900 font-medium': step === s.num,
                                'text-gray-400': step > s.num,
                            }"
                            >{{ s.label }}</span
                        >
                    </div>
                    <div
                        v-if="i < steps.length - 1"
                        class="flex-1 h-px bg-gray-100"
                    ></div>
                </template>
            </div>
            <div class="px-5 py-4">
                <StepPilihKategori
                    v-if="step === 1"
                    :kategori-id="form.kategori_id"
                    @next="onKategoriNext"
                />
                <StepPilihKategoriGroup
                    v-else-if="step === 2"
                    :kategori-id="form.kategori_id"
                    :kategori-nama="form.kategori_nama"
                    :group-id="form.kategori_group_id"
                    :item-id="form.kategori_group_item_id"
                    @next="onGroupItemNext"
                    @back="step--"
                />
                <StepNilaiTahun
                    v-else-if="step === 3"
                    :form="form"
                    :saving="saving"
                    @submit="onSubmit"
                    @back="step--"
                />
            </div>
        </ModalHeadnessUI>
    </AuthenticatedLayout>
</template>
