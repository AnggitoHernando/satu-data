<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted, h } from "vue";
import axios from "axios";
import ActionButtons from "@/Components/ActionButtons.vue";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";
import ModalHeadnessUI from "@/Components/ModalHeadnessUI.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectButton from "@/Components/SelectButton.vue";
import TextArea from "@/Components/TextArea.vue";
import { router } from "@inertiajs/vue3";
// import route from "ziggy-js";

const controller = usePage();
const { list_seksi } = controller.props;

//Form
const form = useForm({
    id: null,
    name: "",
    username: "",
    password: "",
    role: "",
});

const currentYear = new Date().getFullYear();
const years = Array.from({ length: 11 }, (_, i) => currentYear - 5 + i);

//Modal Add
const isOpen = ref(false);

//Modal Deskripsi
const isOpenDeskripsi = ref(false);
const currentDescription = ref("");
const currentJudulDescription = ref("");

//Deskripsi
const openDeskripsi = (description, judul) => {
    currentDescription.value = description;
    currentJudulDescription.value = "Deskripsi " + judul;
    isOpenDeskripsi.value = true;
};

// Table state
const total = ref(0);
const page = ref(1);
const perPage = ref(10);
const search = ref("");
const sortBy = ref("id");
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
    { header: "Nama", key: "name", width: "30%" },
    { header: "Username", key: "username", width: "10%" },
    { header: "Role", key: "role", width: "10%" },
    { header: "Aksi", key: "actions", width: "50%" },
];
// Fetch API
const fetchData = async () => {
    loading.value = true;
    try {
        const res = await axios.get("/api/users-all", {
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
const deleteItem = async (item) => {
    console.log(item.id);
    const confirm = await Swal.fire({
        title: `Apakah Kamu Ingin Menghapus Data "${item.name}"?`,
        icon: "warning",
        showCancelButton: true,
    });
    if (confirm.isConfirmed) {
        const confirm = await Swal.fire({
            title: `Apakah kamu Yakin Menghapus Data "${item.name}" ini?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus",
            cancelButtonText: "Batal",
        });

        if (confirm.isConfirmed) {
            router.delete(route("users.destroy", item.id), {
                onSuccess: async () => {
                    await fetchData();
                    Swal.fire("Berhasil!", "Data telah dihapus.", "success");
                },
                onError: () => {
                    Swal.fire(
                        "Gagal!",
                        "Terjadi kesalahan saat menghapus data.",
                        "error"
                    );
                },
            });
        }
    }
};

const modalMode = ref("create");
const openModal = (item = null) => {
    if (item) {
        modalMode.value = "edit";
        Object.assign(form, item);
    } else {
        modalMode.value = "create";
        form.reset();
    }
    isOpen.value = true;
};

//Fungsi Save
const submit = async () => {
    try {
        let res;
        if (modalMode.value == "create") {
            res = await axios.post(route("users.save"), form, {
                headers: { "Content-Type": "multipart/form-data" },
            });
        } else {
            const formData = new FormData();
            for (const key in form) {
                const value = form[key];
                if (value === null || value === undefined) continue;
                if (key === "file_path" && typeof value === "string") continue;
                formData.append(key, value);
            }
            formData.append("_method", "PATCH");

            res = await axios.post(route("users.update", form.id), formData, {
                headers: { "Content-Type": "multipart/form-data" },
            });
        }
        if (res.data.success) {
            form.reset();
            isOpen.value = false;
            Swal.fire("Berhasil!", res.data.message, "success");
            await fetchData();
        }
    } catch (err) {
        console.log(err);
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
            console.log(err);
        }
    }
};

const generateSlug = () => {
    form.slug = form.judul_data
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, "")
        .replace(/\s+/g, "-")
        .replace(/-+/g, "-");
};

watch([page, perPage, search, sortBy, sortDir], fetchData);

onMounted(fetchData);
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-3 flex justify-end">
                    <PrimaryButtonAdmin @click="() => openModal(null)"
                        >+ Tambah Data</PrimaryButtonAdmin
                    >
                </div>
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div>
                        <input
                            v-model="search"
                            placeholder="Search..."
                            class="ml-2 mt-1 px-2 py-1 mb-2 rounded-md"
                        />

                        <table
                            class="table-fixed border border-gray-300 w-full"
                        >
                            <thead>
                                <tr>
                                    <th
                                        v-for="col in columns"
                                        :key="col.key"
                                        class="border px-4 py-2"
                                        :class="col.class"
                                        :style="{ width: col.width }"
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
                                    <td
                                        class="border border-gray-300 p-2 capitalize"
                                    >
                                        <span class="block font-semibold">{{
                                            row.name
                                        }}</span>
                                    </td>
                                    <td
                                        class="border border-gray-300 p-2 capitalize"
                                    >
                                        <span class="block font-semibold">{{
                                            row.username
                                        }}</span>
                                    </td>
                                    <td
                                        class="border border-gray-300 p-2 text-center"
                                    >
                                        <a
                                            @click="
                                                openDeskripsi(
                                                    row.deskripsi,
                                                    row.judul_data
                                                )
                                            "
                                            class="text-blue-600 hover:underline cursor-pointer"
                                        >
                                            Lihat Role
                                        </a>
                                    </td>
                                    <td class="border border-gray-300 p-2">
                                        <ActionButtons
                                            :item="row"
                                            @edit="openModal"
                                            @delete="deleteItem"
                                            :visible-buttons="[
                                                'edit',
                                                'delete',
                                            ]"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <ModalHeadnessUI
                            :open-modal="isOpenDeskripsi"
                            @close="isOpenDeskripsi = false"
                            :judul_modal="currentJudulDescription"
                            ><p>
                                {{ currentDescription }}
                            </p></ModalHeadnessUI
                        >

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
        <ModalHeadnessUI
            :open-modal="isOpen"
            @close="isOpen = false"
            judul_modal="Tambah User"
        >
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="name" value="Nama User" />

                    <TextInput
                        id="name"
                        type="text"
                        placeholder="Masukkan Nama User"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autocomplete="name"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="username" value="Username" />

                    <TextInput
                        id="username"
                        type="text"
                        placeholder="Masukkan Username"
                        class="mt-1 block w-full"
                        v-model="form.username"
                        required
                        autocomplete="username"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        placeholder="Masukkan password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        :required="modalMode === 'create'"
                        autocomplete="password"
                    />
                </div>
                <div class="flex justify-end gap-2 mt-2">
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
        </ModalHeadnessUI>
    </AuthenticatedLayout>
</template>
