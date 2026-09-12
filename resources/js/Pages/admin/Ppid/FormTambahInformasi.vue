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
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from "@headlessui/vue";
import FileUpload from "@/Components/FileUpload.vue";
import TextArea from "@/Components/TextArea.vue";

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
const form = useForm({
    nama_informasi: "",
    tahun: "",
    unit_kerja: "",
    seksi_id: "",
    bentuk_dokumen: "",
    detail_informasi: "",
    status: "",
    ringkasan: "",
    file: null,
});

const kategoriOptions = [
    { label: "Berkala", value: "berkala" },
    {
        label: "Serta Merta",
        value: "serta_merta",
    },
    {
        label: "Setiap Saat",
        value: "setiap_saat",
    },
    {
        label: "Dikecualikan",
        value: "dikecualikan",
    },
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
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel value="Nama Informasi" />
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
                                            v-for="seksi in usePage().props
                                                .listSeksi"
                                            :key="seksi.id"
                                            :value="String(seksi.id)"
                                        >
                                            {{ seksi.nama_seksi }}
                                        </option>
                                    </SelectButton>
                                </div>
                            </div>
                            <div class="mt-4">
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
                                                $event.key.match(/^[0-9]$/) ||
                                                $event.preventDefault()
                                            "
                                            maxlength="4"
                                            required
                                            autocomplete="tahun"
                                        />
                                    </div>
                                    <div>
                                        <InputLabel value="Unit Kerja" />
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
                            <div class="mt-4">
                                <div
                                    class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                                >
                                    <div>
                                        <InputLabel
                                            value="Kategori Informasi"
                                        />
                                    </div>
                                    <div>
                                        <InputLabel value="Bentuk Dokumen" />
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <InputLabel value="Ringkasan Informasi" />
                                <TextArea
                                    id="ringkasan"
                                    name="ringkasan"
                                    placeholder="Masukkan Ringkasan Informasi"
                                    v-model="form.ringkasan"
                                    class="mt-1 block w-full"
                                />
                            </div>
                            <div class="mt-4">
                                <InputLabel value="Upload File" />
                                <FileUpload
                                    v-model="form.file"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                                    :maxSize="5"
                                    class="mt-1 block w-full"
                                />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
