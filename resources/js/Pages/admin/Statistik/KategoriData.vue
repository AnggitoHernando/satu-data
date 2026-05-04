<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import { usePage, Link, useForm, router } from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import ActionButtons from "@/Components/ActionButtons.vue";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";
import ModalHeadnessUI from "@/Components/ModalHeadnessUI.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectButton from "@/Components/SelectButton.vue";
import InputError from "@/Components/InputError.vue";

const columns = [
    { header: "Nama Kategori", key: "nama_kategori" },
    { header: "Nama Seksi", key: "seksi.nama_seksi" },
    { header: "Aksi", key: "actions" },
];

const filtersort = [
    { label: "Nama Kategori", value: "nama_kategori" },
    { label: "Nama Seksi", value: "seksi.nama_seksi" },
];

const modalMode = ref("create");
const judulModal = ref("");
const isOpen = ref(false);
const pageLoading = ref(true);
const form = useForm({
    id: null,
    nama_kategori: "",
    seksi_id: "",
});
const openModal = (item = null) => {
    if (item) {
        form.clearErrors();
        modalMode.value = "edit";
        Object.assign(form, item);
        judulModal.value = "Ubah Kategori Data";
    } else {
        modalMode.value = "create";
        form.reset();
        judulModal.value = "Tambah Kategori Data";
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
            route("admin.statistik.kategori-data.destroy", item),
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
        <Head title="Kategori Data" />
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
                        <h1 class="text-2xl font-bold mb-4">Kategori Data</h1>
                        <PrimaryButtonAdmin @click="() => openModal(null)">
                            + Tambah Data</PrimaryButtonAdmin
                        >
                    </div>
                    <Table
                        :columns="columns"
                        :rows="usePage().props.listKategoriData.data || []"
                        :list_seksi="usePage().props.listSeksi || []"
                        :filterSortOptions="filtersort"
                        :links="usePage().props.listKategoriData.links"
                        :meta="usePage().props.listKategoriData"
                    >
                        <template #cell-seksi.nama_seksi="{ row }">
                            {{ row.seksi?.nama_seksi }}
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
                <SelectButton
                    id="seksi_id"
                    v-model="form.seksi_id"
                    class="mt-1 block w-full"
                >
                    <option value="">-- Pilih Seksi --</option>
                    <option
                        v-for="seksi in usePage().props.listSeksi"
                        :key="seksi.id"
                        :value="seksi.id"
                    >
                        {{ seksi.nama_seksi }}
                    </option>
                </SelectButton>
                <InputError class="mt-2" :message="form.errors.seksi_id" />
            </div>
            <div class="mt-3">
                <InputLabel for="nama_kategori" value="Nama Kategori" />
                <TextInput
                    id="nama_kategori"
                    v-model="form.nama_kategori"
                    class="mt-1 block w-full"
                    placeholder="Masukkan nama kategori data"
                />
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
