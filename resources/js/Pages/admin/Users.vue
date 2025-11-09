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
import Checkbox from "@/Components/Checkbox.vue";
import { UserCog } from "lucide-vue-next";
import { router } from "@inertiajs/vue3";

const controller = usePage();

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

// Table state
const total = ref(0);
const page = ref(1);
const perPage = ref(10);
const search = ref("");
const sortBy = ref("id");
const sortDir = ref("desc");

// Data & State
const data = ref([]);
const loading = ref(false);

const columns = [
    { header: "Nama", key: "name", width: "20%" },
    { header: "Username", key: "username", width: "10%" },
    { header: "Role", key: "role", width: "10%" },
    { header: "Seksi", key: "seksi", width: "30%" },
    { header: "Aksi", key: "actions", width: "30%" },
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
        data.value = res.data.data ?? [];
        total.value = res.data.total ?? 0;
        data.value = res.data.data.map((user) => ({
            ...user,
            list_seksi: user.list_seksi ? JSON.parse(user.list_seksi) : [],
        }));
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

const modalRole = ref(false);
const formRole = ref("");
const lengthFormRole = ref(0);
const listSeksi = ref([]);
const roleSubmit = useForm({
    user_id: "",
    role: "",
    seksi_id: [],
});
const selectedSeksi = ref([]);
const openModalRole = async (id) => {
    try {
        const res = await axios.get(route("users.formRole", id));
        formRole.value = res.data.formRole;
        listSeksi.value = [...res.data.listSeksi];
        lengthFormRole.value = formRole.value.length;
        roleSubmit.user_id = res.data.user.id;
        roleSubmit.role = res.data.user.role;
        selectedSeksi.value = listSeksi.value
            .filter((s) => s.checked === 1)
            .map((s) => s.id);
        modalRole.value = true;
    } catch (error) {
        console.error(error);
        Swal.fire("Error", "Gagal load modal", "error");
    }
};

const submitRole = async () => {
    roleSubmit.seksi_id = selectedSeksi.value;
    try {
        await roleSubmit.post(route("users.storeRole"), {
            onSuccess: () => {
                Swal.fire("Sukses", "Role berhasil disimpan", "success");
                modalRole.value = false;
                roleSubmit.reset();
                fetchData();
            },
            onError: (errors) => {
                Swal.fire("Validasi Gagal", "Periksa input kamu", "warning");
                console.error(errors);
            },
        });
    } catch (error) {
        console.error("Terjadi error:", error);
        Swal.fire("Error", "Koneksi server gagal", "error");
    }
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
                                        <span class="capitalize">{{
                                            row.role
                                        }}</span>
                                    </td>
                                    <td class="border border-gray-300 p-2">
                                        <div
                                            v-if="row.list_seksi.length > 0"
                                            class="flex flex-wrap gap-1.5"
                                        >
                                            <span
                                                v-for="(
                                                    seksi, i
                                                ) in row.list_seksi"
                                                :key="i"
                                                class="text-[12px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-700 border border-gray-200"
                                            >
                                                {{ seksi }}
                                            </span>
                                        </div>
                                        <span
                                            v-else
                                            class="text-gray-400 italic text-sm"
                                            >Tidak ada seksi</span
                                        >
                                    </td>
                                    <td class="border border-gray-300 p-2">
                                        <div class="flex gap-2 justify-center">
                                            <ActionButtons
                                                :item="row"
                                                @edit="openModal"
                                                @delete="deleteItem"
                                                :visible-buttons="[
                                                    'edit',
                                                    'delete',
                                                ]"
                                            />
                                            <button
                                                class="bg-green-500 text-white px-2 py-1 rounded"
                                                @click="openModalRole(row.id)"
                                                title="Tambah/Ganti Role"
                                            >
                                                <component
                                                    :is="UserCog"
                                                    class="w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"
                                                />
                                            </button>
                                        </div>
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
        <ModalHeadnessUI
            :open-modal="modalRole"
            @close="modalRole = false"
            judul_modal="Role"
        >
            <form @submit.prevent="submitRole">
                <div>
                    <InputLabel for="role" value="Role" />

                    <TextInput
                        v-if="lengthFormRole.value > 1"
                        readonly
                        id="role"
                        type="text"
                        class="mt-1 block w-full bg-gray-300"
                        autocomplete="role"
                        v-model="roleSubmit.role"
                    />
                    <SelectButton
                        v-else
                        id="role"
                        name="role"
                        v-model="roleSubmit.role"
                        class="capitalize"
                    >
                        <option disabled value="">-- Pilih Role --</option>
                        <option
                            v-for="(role, i) in formRole"
                            :key="i"
                            :value="String(role)"
                            class="capitalize"
                        >
                            {{ role }}
                        </option>
                    </SelectButton>
                </div>
                <div class="mt-4" v-if="roleSubmit.role === 'operator'">
                    <InputLabel for="seksi" value="Seksi" />
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                        <label
                            v-for="seksi in listSeksi"
                            :key="seksi.id"
                            class="flex items-center space-x-2 cursor-pointer"
                        >
                            <Checkbox
                                :id="`seksi-${seksi.id}`"
                                v-model:checked="selectedSeksi"
                                :value="seksi.id"
                            />
                            <span class="text-gray-800 text-sm">{{
                                seksi.nama_seksi
                            }}</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-8">
                    <button
                        type="button"
                        @click="modalRole = false"
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
