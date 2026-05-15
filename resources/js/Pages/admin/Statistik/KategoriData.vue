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
import ComboBox from "@/Components/ComboBox.vue";
import ModalGroupKategori from "@/Components/ModalGroupKategori.vue";
import { Settings } from "lucide-vue-next";

const columns = [
    { header: "Nama Kategori", key: "nama_kategori" },
    { header: "Nama Seksi", key: "seksi.nama_seksi" },
    { header: "Referensi Data", key: "jenis_data.judul_data" },
    { header: "Aksi", key: "actions" },
];

const filtersort = [
    { label: "Nama Kategori", value: "nama_kategori" },
    { label: "Nama Seksi", value: "seksi.nama_seksi" },
    { label: "Referensi Data", value: "jenis_data.judul_data" },
];

const showRef = ref(false);
const modalMode = ref("create");
const judulModal = ref("");
const isOpen = ref(false);
const pageLoading = ref(true);
const form = useForm({
    id: null,
    nama_kategori: "",
    seksi_id: "",
    jenis_data_id: null,
    judul_data: null,
});
const selectedKategori = ref(null);
const modalGroupKategoriOpen = ref(false);

const openModalGroupKategori = (kategori) => {
    selectedKategori.value = kategori;
    modalGroupKategoriOpen.value = true;
};

const openModal = (item = null) => {
    if (item) {
        form.clearErrors();
        modalMode.value = "edit";
        Object.assign(form, item);
        if (item.jenis_data_id && item.jenis_data) {
            form.judul_data = item.jenis_data.judul_data;
        } else {
            form.judul_data = null;
        }
        judulModal.value = "Ubah Kategori Data";
        showRef.value = !!item.jenis_data_id;
    } else {
        form.clearErrors();
        modalMode.value = "create";
        form.reset();
        judulModal.value = "Tambah Kategori Data";
        showRef.value = false;
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
                        <template #cell-jenis_data.judul_data="{ row }">
                            {{ row.jenis_data?.judul_data || "-" }}
                        </template>
                        <template #cell-actions="{ row }">
                            <div class="flex gap-2 justify-center">
                                <ActionButtons
                                    :visibleButtons="['edit', 'delete']"
                                    :item="row"
                                    @edit="() => openModal(row)"
                                    @delete="() => handleDelete(row)"
                                />
                                <button
                                    class="bg-green-500 text-white px-2 py-1 rounded"
                                    @click="openModalGroupKategori(row)"
                                    title="Tambah Group Kategori"
                                >
                                    <Settings
                                        class="w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"
                                    />
                                </button>
                            </div>
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
            <div class="mt-4">
                <button
                    type="button"
                    @click="showRef = !showRef"
                    class="text-sm text-blue-600 hover:underline"
                >
                    {{
                        showRef
                            ? "- Sembunyikan Referensi"
                            : "+ Tambah Referensi (Opsional)"
                    }}
                </button>
            </div>
            <transition name="fade">
                <div v-if="showRef" class="mt-3">
                    <InputLabel value="Pilih Referensi Data" />
                    <ComboBox
                        id="jenis_data_id"
                        v-model="form.jenis_data_id"
                        search-url="admin.statistik.kategori-data.searchReferensi"
                        :initial-label="form.judul_data"
                        label-key="judul_data"
                        value-key="id"
                        class="mt-1 block w-full"
                        placeholder="Ketik Nama File Referensi..."
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.jenis_data_id"
                    />
                </div>
            </transition>
            <div class="mt-5 flex justify-end gap-2">
                <PrimaryButtonAdmin @click="handleSubmit">
                    Simpan
                </PrimaryButtonAdmin>
            </div>
        </ModalHeadnessUI>
        <ModalGroupKategori
            :model-value="modalGroupKategoriOpen"
            :kategori="selectedKategori"
            @update:modelValue="modalGroupKategoriOpen = $event"
        />
    </AuthenticatedLayout>
</template>
