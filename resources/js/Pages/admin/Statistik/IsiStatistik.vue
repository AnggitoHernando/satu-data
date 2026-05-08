<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import { usePage, useForm, router } from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";
import ModalHeadnessUI from "@/Components/ModalHeadnessUI.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectButton from "@/Components/SelectButton.vue";
import InputError from "@/Components/InputError.vue";
import ActionButtons from "@/Components/ActionButtons.vue";

const columns = [
    { header: "Nama Kategori", key: "kategoriData.nama_kategori" },
    { header: "Nama Seksi", key: "kategoriData.seksi.nama_seksi" },
    { header: "Tahun", key: "tahun" },
    { header: "Value", key: "value" },
    { header: "Aksi", key: "actions" },
];

console.log(usePage().props);

const filtersort = [
    { label: "Nama Kategori", value: "kategoriData.nama_kategori" },
    { label: "Nama Seksi", value: "kategoriData.seksi.nama_seksi" },
    { label: "Tahun", value: "tahun" },
];

const searchFilters = ref("");
const modalMode = ref("create");
const judulModal = ref("");
const isOpen = ref(false);
const pageLoading = ref(true);
const form = useForm({
    id: null,
    kategori_data_id: "",
    tahun: "",
    value: "",
});
const openModal = (item = null) => {
    if (item) {
        form.clearErrors();
        modalMode.value = "edit";
        Object.assign(form, item);
        judulModal.value = "Ubah Iisi Statistik";
    } else {
        modalMode.value = "create";
        form.reset();
        judulModal.value = "Tambah Isi Statistik";
    }
    isOpen.value = true;
};
const handleSubmit = async () => {
    pageLoading.value = true;
    if (modalMode.value == "create") {
        await form.post(route("admin.statistik.kategori-data.simpan"), {
            onError: () => {
                pageLoading.value = false;
            },
            onSuccess: () => {
                pageLoading.value = false;
                isOpen.value = false;
                form.reset();
            },
            onFinish: () => {
                pageLoading.value = false;
            },
        });
    } else {
        await form.patch(route("admin.statistik.kategori-data.update", form), {
            onError: () => {
                pageLoading.value = false;
            },
            onSuccess: () => {
                pageLoading.value = false;
                isOpen.value = false;
                form.reset();
            },
            onFinish: () => {
                pageLoading.value = false;
            },
        });
    }
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
                        <template #cell-kategoriData.nama_kategori="{ row }">
                            {{ row.kategori_data?.nama_kategori }}
                        </template>
                        <template #cell-kategoriData.seksi.nama_seksi="{ row }">
                            {{ row.kategori_data.seksi?.nama_seksi }}
                        </template>
                        <template #cell-actions="{ row }">
                            <ActionButtons
                                :visibleButtons="['edit', 'delete']"
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
            <div>
                <InputLabel for="seksi_id" value="Pilih Seksi" />

                <InputError class="mt-2" :message="form.errors.seksi_id" />
            </div>
            <div class="mt-3">
                <InputLabel for="nama_kategori" value="Nama Kategori" />

                <InputError class="mt-2" :message="form.errors.nama_kategori" />
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <PrimaryButtonAdmin @click="handleSubmit">
                    Simpan
                </PrimaryButtonAdmin>
            </div>
        </ModalHeadnessUI>
    </AuthenticatedLayout>
</template>
