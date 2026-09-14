<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, useForm } from "@inertiajs/vue3";
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
    nama_informasi: "",
    tahun: "",
    unit_kerja: "",
    jenis_data_id: "",
    seksi_id: "",
    bentuk_dokumen: "",
    detail_informasi: "",
    status: "",
    ringkasan: "",
    file: null,
    kategori: "",
});

const sourceDocument = [
    { name: "Upload File", selected: true, key: "upload_file" },
    { name: "Ambil Dari Portal Data", selected: false, key: "portal_data" },
];

console.log(usePage().props);
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Tambah Informasi" />
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow p-5">
                    <div
                        class="mb-4 flex flex-col sm:flex-row justify-between gap-3"
                    >
                        <h1 class="text-2xl font-bold mb-4">
                            Tambah Informasi
                        </h1>
                        <div class="flex gap-2">
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
                                                v-model="form.file"
                                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                                                :maxSize="5"
                                                class="mt-4 block w-full"
                                            />
                                        </template>
                                        <template #tab-panel-portal_data>
                                            <ComboboxSearch
                                                v-model="selectedKategori"
                                                :emit-object="true"
                                                class="mt-4 block w-full"
                                                search-url="admin.statistik.isi-statistik.getKategoriData"
                                                label-key="nama_kategori"
                                                value-key="id"
                                                placeholder="Cari Data Pada Portal Data..."
                                                @update:model-value="
                                                    onKategoriSelected
                                                "
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
                                    </div>
                                </template>
                            </Card>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
