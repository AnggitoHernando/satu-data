<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import ActionButtons from "@/Components/ActionButtons.vue";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";
import ModalHeadnessUI from "@/Components/ModalHeadnessUI.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectButton from "@/Components/SelectButton.vue";
import InputError from "@/Components/InputError.vue";
import ComboBox from "@/Components/ComboBox.vue";
import ModalGroupKategori from "@/Components/ModalGroupKategori.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import FileUpload from "@/Components/FileUpload.vue";
import TextArea from "@/Components/TextArea.vue";
import Card from "@/Components/Card.vue";
import Tab from "@/Components/Tab.vue";
import ComboboxSearch from "@/Components/ComboBox.vue";
import CustomRadioButton from "@/Components/CustomRadioButton.vue";
import RadioPilGroup from "@/Components/RadioPilGroup.vue";
import { Laptop, FileText, Copy } from "lucide-vue-next";
import Loading from "@/Components/Loading.vue";

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

const kategoriInformasiOptions = [
    {
        label: "Berkala",
        value: "berkala",
        description:
            "Informasi yang diterbitkan secara berkala sesuai jadwal tertentu.",
    },
    {
        label: "Serta Merta",
        value: "serta_merta",
        description:
            "Informasi yang diterbitkan secara serta merta ketika terjadi perubahan atau kejadian penting.",
    },
    {
        label: "Setiap Saat",
        value: "setiap_saat",
        description:
            "Informasi yang dapat diakses setiap saat tanpa batasan waktu.",
    },
    {
        label: "Dikecualikan",
        value: "dikecualikan",
        description:
            "Informasi yang dikecualikan dari publikasi karena alasan tertentu.",
    },
];

const bentukDokumenOptions = [
    {
        label: "Soft copy",
        value: "soft_copy",
        icon: Laptop,
    },
    {
        label: "Hard copy",
        value: "hard_copy",
        icon: FileText,
    },
    {
        label: "Keduanya",
        value: "keduanya",
        icon: Copy,
    },
];

const form = useForm({
    id: usePage().props.ppidInformasi.id ?? null,
    nama_informasi: usePage().props.ppidInformasi.nama_informasi ?? "",
    tahun: String(usePage().props.ppidInformasi.tahun) ?? "",
    unit_kerja: usePage().props.ppidInformasi.unit_kerja ?? "",
    jenis_data_id: usePage().props.ppidInformasi.jenis_data_id ?? "",
    seksi_id: usePage().props.ppidInformasi.seksi_id ?? "",
    bentuk_dokumen: usePage().props.ppidInformasi.bentuk_dokumen ?? "",
    ringkasan: usePage().props.ppidInformasi.ringkasan ?? "",
    file_path: usePage().props.ppidInformasi.file_path ?? null,
    kategori: usePage().props.ppidInformasi.kategori ?? "",
});

const sourceDocument = [
    { name: "Upload File", selected: true, key: "upload_file" },
    { name: "Ambil Dari Portal Data", selected: false, key: "portal_data" },
];

const selectedJenisData = ref(null);
const pageLoading = ref(true);
onMounted(() => {
    pageLoading.value = false;
});
const submit = () => {
    selectedJenisData.value = form.jenis_data_id ?? null;
    form.jenis_data_id = form.jenis_data_id ? form.jenis_data_id.id : null;
    if (form.id === null) {
        form.post(route("admin.ppid.tambah-informasi.simpan"), {
            onLoading: () => {
                pageLoading.value = true;
            },
            onSuccess: () => {},
            onError: (errors) => {
                // console.error("Form submission errors:", errors);
                form.jenis_data_id = selectedJenisData;
            },
            onFinish: () => {
                pageLoading.value = false;
            },
        });
    } else {
        if (!(form.file_path instanceof File)) {
            form.file_path = null;
        }
        form.transform((data) => ({
            ...data,
            _method: "put",
        })).post(route("admin.ppid.tambah-informasi.update", form.id), {
            onLoading: () => {
                pageLoading.value = true;
            },
            onSuccess: () => {},
            onError: (errors) => {
                form.jenis_data_id = selectedJenisData;
            },
            onFinish: () => {
                pageLoading.value = false;
            },
        });
    }
};
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
                        <h1 class="text-2xl font-bold mb-4">
                            Tambah Informasi
                        </h1>
                    </div>
                    <div>
                        <form @submit.prevent="submit">
                            <Card class="mb-4" header="Informasi Dasar">
                                <template #body>
                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                                    >
                                        <div>
                                            <InputLabel
                                                value="Nama Informasi"
                                            />
                                            <TextInput
                                                id="nama_informasi"
                                                type="text"
                                                placeholder="Masukkan Nama Informasi"
                                                class="mt-1 block w-full"
                                                v-model="form.nama_informasi"
                                                required
                                                autocomplete="nama_informasi"
                                            />
                                            <InputError
                                                :message="
                                                    form.errors.nama_informasi
                                                "
                                                class="mt-2"
                                            />
                                        </div>
                                        <div>
                                            <InputLabel value="Pilih Seksi" />
                                            <SelectButton
                                                id="seksi_id"
                                                name="seksi_id"
                                                v-model="form.seksi_id"
                                                class="mt-1 block w-full"
                                            >
                                                <option disabled value="">
                                                    -- Pilih Seksi --
                                                </option>
                                                <option
                                                    v-for="seksi in usePage()
                                                        .props.listSeksi"
                                                    :key="seksi.id"
                                                    :value="String(seksi.id)"
                                                >
                                                    {{ seksi.nama_seksi }}
                                                </option>
                                            </SelectButton>
                                            <InputError
                                                :message="form.errors.seksi_id"
                                                class="mt-2"
                                            />
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <InputLabel
                                            value="Ringkasan Informasi"
                                        />
                                        <TextArea
                                            id="ringkasan"
                                            name="ringkasan"
                                            placeholder="Masukkan Ringkasan Informasi"
                                            v-model="form.ringkasan"
                                            class="mt-1 block w-full"
                                        />
                                        <InputError
                                            :message="form.errors.ringkasan"
                                            class="mt-2"
                                        />
                                    </div>
                                    <div class="mt-2">
                                        <div
                                            class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                                        >
                                            <div>
                                                <InputLabel value="Tahun" />
                                                <TextInput
                                                    id="tahun"
                                                    type="text"
                                                    placeholder="Masukkan Tahun"
                                                    class="mt-1 block w-full"
                                                    v-model="form.tahun"
                                                    @keypress="
                                                        $event.key.match(
                                                            /^[0-9]$/,
                                                        ) ||
                                                        $event.preventDefault()
                                                    "
                                                    maxlength="4"
                                                    required
                                                    autocomplete="tahun"
                                                />
                                                <InputError
                                                    :message="form.errors.tahun"
                                                    class="mt-2"
                                                />
                                            </div>
                                            <div>
                                                <InputLabel
                                                    value="Unit Kerja"
                                                />
                                                <TextInput
                                                    id="unit_kerja"
                                                    type="text"
                                                    placeholder="Masukkan Unit Kerja"
                                                    class="mt-1 block w-full"
                                                    v-model="form.unit_kerja"
                                                    required
                                                    autocomplete="unit_kerja"
                                                />
                                                <InputError
                                                    :message="
                                                        form.errors.unit_kerja
                                                    "
                                                    class="mt-2"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </Card>
                            <Card
                                class="mb-4"
                                header="Dokumen / File Informasi"
                            >
                                <template #body>
                                    <Tab
                                        class="mt-2"
                                        :categories="sourceDocument"
                                    >
                                        <template #tab-panel-upload_file>
                                            <FileUpload
                                                v-model="form.file_path"
                                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                                                :maxSize="5"
                                                class="mt-4 block w-full"
                                            />
                                            <InputError
                                                :message="form.errors.file_path"
                                                class="mt-2"
                                            />
                                        </template>
                                        <template #tab-panel-portal_data>
                                            <ComboboxSearch
                                                v-model="form.jenis_data_id"
                                                :emit-object="true"
                                                class="mt-4 block w-full"
                                                search-url="admin.ppid.get-jenis-data"
                                                label-key="judul_data"
                                                value-key="id"
                                                placeholder="Cari Data Pada Portal Data..."
                                            />
                                            <InputError
                                                :message="
                                                    form.errors.jenis_data_id
                                                "
                                                class="mt-2"
                                            />
                                        </template>
                                    </Tab>
                                </template>
                            </Card>
                            <Card class="mb-4" header="Kategori Informasi">
                                <template #body>
                                    <div class="mb-4">
                                        <InputLabel value="Bentuk Dokumen" />
                                        <RadioPilGroup
                                            v-model="form.bentuk_dokumen"
                                            class="mt-4"
                                            :options="bentukDokumenOptions"
                                            :cols="3"
                                        />
                                        <InputError
                                            :message="
                                                form.errors.bentuk_dokumen
                                            "
                                            class="mt-2"
                                        />
                                    </div>
                                    <div>
                                        <InputLabel
                                            value="Kategori Informasi"
                                        />
                                        <CustomRadioButton
                                            v-model="form.kategori"
                                            class="mt-4"
                                            :options="kategoriInformasiOptions"
                                            :cols="2"
                                        />
                                        <InputError
                                            :message="form.errors.kategori"
                                            class="mt-2"
                                        />
                                    </div>
                                </template>
                            </Card>
                            <div class="flex justify-end gap-2">
                                <SecondaryButton
                                    @click="
                                        () =>
                                            $inertia.get(
                                                route(
                                                    'admin.ppid.tambah-informasi',
                                                ),
                                            )
                                    "
                                >
                                    Kembali
                                </SecondaryButton>
                                <PrimaryButtonAdmin>Simpan</PrimaryButtonAdmin>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
