<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted, h } from "vue";
import axios from "axios";
import ActionButtons from "@/Components/ActionButtons.vue";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    DialogOverlay,
    TransitionRoot,
    TransitionChild,
} from "@headlessui/vue";
import { CircleX, FileText } from "lucide-vue-next";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectButton from "@/Components/SelectButton.vue";
import TextArea from "@/Components/TextArea.vue";

const controller = usePage();
const { list_seksi } = controller.props;

//Form
const form = useForm({
    judul_data: "",
    seksi_id: "",
    slug: "",
    deskripsi: "",
    tahun: "",
    sumber_data: "",
    status_data: "",
    file_path: "",
});

const currentYear = new Date().getFullYear();
const years = Array.from({ length: 11 }, (_, i) => currentYear - 5 + i);

//Modal
const showModal = ref(false);

const isOpen = ref(false);

// Table state
const total = ref(0);
const page = ref(1);
const perPage = ref(10);
const search = ref("");
const sortBy = ref("jenis_data.id");
const sortDir = ref("desc");
const fileInput = ref(null);

// Data & State
const data = ref([]);
const loading = ref(false);

const handleFileChange = (event) => {
    const files = event.target.files;
    form.file_path = files.length ? files[0] : null;
};

const columns = [
    { header: "Nama Data", key: "judul_data" },
    { header: "Seksi", key: "nama_seksi" },
    { header: "Deskripsi", key: "deskripsi" },
    { header: "Tahun", key: "tahun" },
    { header: "Status Data", key: "status_data" },
    { header: "File", key: "file_path" },
    { header: "Aksi", key: "actions" },
];
// Fetch API
const fetchData = async () => {
    loading.value = true;
    try {
        const res = await axios.get("/api/jenis_data_all", {
            params: {
                page: page.value,
                perPage: perPage.value,
                search: search.value,
                sortBy: sortBy.value,
                sortDir: sortDir.value,
            },
        });
        data.value = [...res.data.data]; // reactive array
        total.value = res.data.total;
    } catch (error) {
        console.error(error);
        Swal.fire("Error", "Gagal load data", "error");
    } finally {
        loading.value = false;
    }
};

// Delete data
const deleteItem = async (id) => {
    const confirm = await Swal.fire({
        title: "Hapus data?",
        icon: "warning",
        showCancelButton: true,
    });
    if (confirm.isConfirmed) {
        await axios.delete(`/api/jenis_data/${id}`);
        fetchData(); // refresh
        Swal.fire("Terhapus!", "Data berhasil dihapus", "success");
    }
};

// Edit item (contoh)
const editItem = (item) => {
    form.seksi_id = String(item.seksi_id);
    form.judul_data = item.judul_data;
    form.slug = item.slug;
    form.deskripsi = item.deskripsi;
    form.tahun = String(item.tahun);
    form.sumber_data = item.sumber_data;
    form.status_data = item.status_data;
    showModal.value = true;
};

//Fungsi Save
const submit = async () => {
    try {
        const res = await axios.post(route("jenis_data.save"), form, {
            headers: { "Content-Type": "multipart/form-data" },
        });
        if (res.data.success) {
            form.reset();
            form.file_path = null;
            if (fileInput.value) fileInput.value.value = null;
            isOpen.value = false;
            Swal.fire("Berhasil!", res.data.message, "success");
            await fetchData();
        }
    } catch (err) {
        if (err.response && err.response.status === 422) {
            Object.values(err.response.data.errors).forEach((msg) => {
                Toastify({
                    text: msg,
                    duration: 4000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#EF4444",
                    },
                }).showToast();
            });
        } else {
            Swal.fire("Error", "Terjadi kesalahan server", "error");
        }
    }
};

watch([page, perPage, search, sortBy, sortDir], fetchData);

//reset modal ketika di click awal
watch(showModal, (val) => {
    if (val) form.reset();
});
onMounted(fetchData);
</script>

<template>
    <Head title="Jenis Data" />

    <AuthenticatedLayout>
        <!-- <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template> -->
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-3 flex justify-end">
                    <PrimaryButtonAdmin @click="isOpen = true"
                        >+ Tambah Data</PrimaryButtonAdmin
                    >
                    <TransitionRoot :show="isOpen" as="template">
                        <Dialog
                            class="fixed inset-0 z-50 overflow-y-auto"
                            @close="() => {}"
                            static
                        >
                            <div
                                class="flex min-h-screen items-center justify-center p-4"
                            >
                                <TransitionChild
                                    enter="ease-out duration-300"
                                    enter-from="opacity-0"
                                    enter-to="opacity-100"
                                    leave="ease-in duration-200"
                                    leave-from="opacity-100"
                                    leave-to="opacity-0"
                                >
                                    <DialogOverlay
                                        class="fixed inset-0 bg-black bg-opacity-20 backdrop-blur-md z-40"
                                    />
                                </TransitionChild>

                                <TransitionChild
                                    enter="ease-out duration-300"
                                    enter-from="opacity-0 scale-95"
                                    enter-to="opacity-100 scale-100"
                                    leave="ease-in duration-200"
                                    leave-from="opacity-100 scale-100"
                                    leave-to="opacity-0 scale-95"
                                >
                                </TransitionChild>
                                <DialogPanel
                                    class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 z-50 relative"
                                >
                                    <div
                                        class="flex justify-between items-center mb-4"
                                    >
                                        <h2 class="text-xl font-semibold">
                                            Tambah Jenis Data
                                        </h2>
                                        <button
                                            @click="isOpen = false"
                                            class="absolute top-2 right-2 p-2 rounded hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        >
                                            ✕
                                        </button>
                                    </div>

                                    <form @submit.prevent="submit">
                                        <div>
                                            <InputLabel
                                                for="seksi_id"
                                                value="Pilih Seksi"
                                            />
                                            <SelectButton
                                                id="seksi_id"
                                                name="seksi_id"
                                                v-model="form.seksi_id"
                                            >
                                                <option disabled value="">
                                                    -- Pilih Seksi --
                                                </option>
                                                <option
                                                    v-for="seksi in list_seksi"
                                                    :key="seksi.id"
                                                    :value="String(seksi.id)"
                                                >
                                                    {{ seksi.nama_seksi }}
                                                </option>
                                            </SelectButton>
                                        </div>
                                        <div class="mt-4">
                                            <InputLabel
                                                for="judul_data"
                                                value="Nama Data"
                                            />

                                            <TextInput
                                                id="judul_data"
                                                type="text"
                                                placeholder="Masukkan Nama Data"
                                                class="mt-1 block w-full"
                                                v-model="form.judul_data"
                                                required
                                                autocomplete="judul_data"
                                            />
                                        </div>
                                        <div class="mt-4">
                                            <InputLabel
                                                for="slug"
                                                value="Slug"
                                            />

                                            <TextInput
                                                id="slug"
                                                type="text"
                                                placeholder="Masukkan Slug"
                                                class="mt-1 block w-full"
                                                v-model="form.slug"
                                                required
                                                autocomplete="slug"
                                            />
                                        </div>
                                        <div class="mt-4">
                                            <InputLabel
                                                for="deskripsi"
                                                value="Deskripsi"
                                            />

                                            <TextArea
                                                id="deskripsi"
                                                name="deskrpisi"
                                                placeholder="Masukkan Deskripsi"
                                                v-model="form.deskripsi"
                                            />
                                        </div>
                                        <div class="mt-4">
                                            <InputLabel
                                                for="tahun"
                                                value="Tahun"
                                            />

                                            <SelectButton
                                                id="tahun"
                                                name="tahun"
                                                v-model="form.tahun"
                                            >
                                                <option disabled value="">
                                                    -- Pilih Tahun --
                                                </option>
                                                <option
                                                    v-for="year in years"
                                                    :key="year"
                                                    :value="String(year)"
                                                    :selected="
                                                        year === currentYear
                                                    "
                                                >
                                                    {{ year }}
                                                </option>
                                            </SelectButton>
                                        </div>
                                        <div class="mt-4">
                                            <InputLabel
                                                for="sumber_data"
                                                value="Sumber Data"
                                            />

                                            <TextInput
                                                id="sumber_data"
                                                type="text"
                                                placeholder="Masukkan Sumber Data"
                                                class="mt-1 block w-full"
                                                v-model="form.sumber_data"
                                                required
                                                autocomplete="sumber_data"
                                            />
                                        </div>
                                        <div class="mt-4">
                                            <InputLabel
                                                for="file_path"
                                                value="File"
                                            />

                                            <input
                                                id="file_path"
                                                type="file"
                                                class="mt-1 block w-full border rounded px-2 py-1"
                                                ref="fileInput"
                                                @change="handleFileChange"
                                            />
                                        </div>
                                        <div
                                            class="flex justify-end gap-2 mt-2"
                                        >
                                            <button
                                                type="button"
                                                @click="isOpen = false"
                                                class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded"
                                            >
                                                Batal
                                            </button>
                                            <PrimaryButtonAdmin type="submit"
                                                >Simpan</PrimaryButtonAdmin
                                            >
                                        </div>
                                    </form>
                                </DialogPanel>
                            </div>
                        </Dialog>
                    </TransitionRoot>
                </div>
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div>
                        <input
                            v-model="search"
                            placeholder="Search..."
                            class="ml-2 mt-1 px-2 py-1 mb-2 rounded-md"
                        />

                        <table class="table-auto border border-gray-300 w-full">
                            <thead>
                                <tr>
                                    <th
                                        v-for="col in columns"
                                        :key="col.key"
                                        class="border px-4 py-2"
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
                                <tr v-else-if="data.length === 0">
                                    <td
                                        :colspan="columns.length"
                                        class="text-center py-2"
                                    >
                                        Data Tidak Ditemukan
                                    </td>
                                </tr>
                                <tr
                                    v-else
                                    v-for="row in data"
                                    :key="row.id"
                                    class="hover:bg-gray-100"
                                >
                                    <td>{{ row.judul_data }}</td>
                                    <td>{{ row.nama_seksi }}</td>
                                    <td>{{ row.deskripsi }}</td>
                                    <td>{{ row.tahun }}</td>
                                    <td>{{ row.status_data }}</td>
                                    <td>
                                        <span
                                            v-if="!row.file_path"
                                            class="text-gray-400"
                                            >Tidak ada file</span
                                        >
                                        <a
                                            v-else
                                            :href="`/storage/${row.file_path}`"
                                            target="_blank"
                                            class="text-blue-600 hover:underline"
                                        >
                                            Lihat File
                                        </a>
                                    </td>
                                    <td>
                                        <ActionButtons
                                            :item="row"
                                            @onEdit="editItem"
                                            @onDelete="deleteItem"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div
                            class="ml-3 mr-3 mt-4 mb-3 flex justify-between items-center"
                        >
                            <button
                                @click="page > 1 ? page-- : null"
                                :disabled="page === 1"
                            >
                                Prev
                            </button>
                            <span
                                >Page {{ page }} of
                                {{ Math.ceil(total / perPage) }}</span
                            >
                            <button
                                @click="
                                    page < Math.ceil(total / perPage)
                                        ? page++
                                        : null
                                "
                                :disabled="page >= Math.ceil(total / perPage)"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
