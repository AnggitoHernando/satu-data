<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import PpidLayout from "@/Layouts/PpidLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { SendIcon, SearchIcon, ShieldCheck } from "lucide-vue-next";
const isContrastMode = ref(false);
import Card from "@/Components/Card.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import TextArea from "@/Components/TextArea.vue";
import FileUpload from "@/Components/FileUpload.vue";
import RadioPilGroup from "@/Components/RadioPilGroup.vue";
import BannerCard from "@/Components/BannerCard.vue";

const form = useForm({
    nama_lengkap: "",
    email: "",
    no_telepon: "",
    pekerjaan: "",
    alamat: "",
    bukti_identitas: null,
    rincian_informasi: "",
    tujuan_penggunaan: "",
    cara_mendapatkan: "",
});

const fileName = ref("");
const fileInputRef = ref(null);

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;
    form.bukti_identitas = file;
    fileName.value = file.name;
};

const submit = () => {
    form.post(route("home.ppid.permohonan_informasi.store"), {
        onLoading: () => {},
        onSuccess: () => {
            form.reset();
        },
        onError: (errors) => {
            console.error("Form submission errors:", errors);
        },
        onFinish: () => {},
    });
};

const caraMendapatkanInformasi = [
    {
        label: "Email / Download",
        value: "email_download",
    },
    {
        label: "Ambil Langsung",
        value: "ambil_langsung",
    },
    {
        label: "Pos",
        value: "pos",
    },
];

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
    <Head title="PPID Kemenag Gresik - Permohonan Informasi" />
    <PpidLayout :is-contrast-mode="isContrastMode">
        <BannerCard ukuranCard="max-w-6xl">
            <template #banner-content>
                <span
                    class="inline-flex items-center gap-1.5 bg-gradient-to-b from-[#EBF7F1] via-[#F4FAF6] to-slate-50 text-xs font-bold px-4 py-1.5 rounded-full mb-4 border border-[#0B6E4F]/20"
                >
                    <ShieldCheck class="w-4 h-4" />
                    Formulir
                </span>

                <h1
                    class="text-3xl text-yellow-400 sm:text-4xl md:text-5xl font-bold font-serif tracking-tight leading-tight"
                >
                    Formulir Permohonan Informasi Publik
                </h1>

                <p class="mt-3.5 text-xs text-white mx-auto leading-relaxed">
                    Layanan pengajuan permohonan informasi publik secara online
                    sesuai UU No. 14 Tahun 2008 tentang Keterbukaan Informasi
                    Publik.
                </p>
            </template>
            <template #card-body>
                <form @submit.prevent="submit" class="space-y-6">
                    <Card number="1" header="Data Identitas Pemohon">
                        <template #body>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel value="Nama Lengkap" />
                                    <TextInput
                                        id="nama_lengkap"
                                        type="text"
                                        placeholder="Sesuai KTP / Kartu Identitas"
                                        class="mt-1 block w-full"
                                        v-model="form.nama_lengkap"
                                        required
                                        autocomplete="nama_lengkap"
                                    />
                                    <InputError
                                        :message="form.errors.nama_lengkap"
                                        class="mt-2"
                                    />
                                </div>
                                <div>
                                    <InputLabel value="Email Aktif" />
                                    <TextInput
                                        id="email"
                                        type="email"
                                        placeholder="nama@email.com"
                                        class="mt-1 block w-full"
                                        v-model="form.email"
                                        required
                                        autocomplete="email"
                                    />
                                    <InputError
                                        :message="form.errors.email"
                                        class="mt-2"
                                    />
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4"
                            >
                                <div>
                                    <InputLabel value="Pekerjaan" />
                                    <TextInput
                                        id="pekerjaan"
                                        type="text"
                                        placeholder="Contoh: Mahasiswa / Swasta / PNS"
                                        class="mt-1 block w-full"
                                        v-model="form.pekerjaan"
                                        required
                                        autocomplete="pekerjaan"
                                    />
                                    <InputError
                                        :message="form.errors.pekerjaan"
                                        class="mt-2"
                                    />
                                </div>
                                <div>
                                    <InputLabel value="No Telepon" />
                                    <TextInput
                                        id="no_telepon"
                                        type="tel"
                                        placeholder="081234567890"
                                        class="mt-1 block w-full"
                                        v-model="form.no_telepon"
                                        @keypress="
                                            $event.key.match(/^[0-9]$/) ||
                                            $event.preventDefault()
                                        "
                                        maxlength="12"
                                        autocomplete="no_telepon"
                                    />
                                    <InputError
                                        :message="form.errors.no_telepon"
                                        class="mt-2"
                                    />
                                </div>
                            </div>
                            <div claas="mt-2">
                                <InputLabel value="Alamat Lengkap" />
                                <TextArea
                                    id="alamat"
                                    name="alamat"
                                    placeholder="Alamat domisili tempat tinggal saat ini..."
                                    v-model="form.alamat"
                                    class="mt-1 block w-full"
                                />
                                <InputError
                                    :message="form.errors.alamat"
                                    class="mt-2"
                                />
                            </div>
                            <div class="mt-2">
                                <InputLabel
                                    value="Upload Bukti Identitas (KTP / SIM / Passport /
                                KTM)"
                                />
                                <FileUpload
                                    v-model="form.bukti_identitas"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    :maxSize="5"
                                    class="mt-4 block w-full"
                                />
                                <InputError
                                    :message="form.errors.bukti_identitas"
                                    class="mt-2"
                                />
                            </div>
                        </template>
                    </Card>
                    <Card number="2" header="Rincian Informasi Yang Diminta">
                        <template #body>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <InputLabel value="Rincian Informasi" />
                                    <TextArea
                                        id="rincian_informasi"
                                        name="rincian_informasi"
                                        placeholder="Jelaskan secara spesifik dokumen / informasi publik yang Anda butuhkan..."
                                        v-model="form.rincian_informasi"
                                        class="mt-1 block w-full"
                                    />
                                    <InputError
                                        :message="form.errors.rincian_informasi"
                                        class="mt-2"
                                    />
                                </div>
                                <div>
                                    <InputLabel
                                        value="Tujuan Penggunaan Informasi"
                                    />
                                    <TextArea
                                        id="tujuan_penggunaan"
                                        name="tujuan_penggunaan"
                                        placeholder="Jelaskan tujuan dan peruntukan penggunaan informasi ini..."
                                        v-model="form.tujuan_penggunaan"
                                        class="mt-1 block w-full"
                                    />
                                    <InputError
                                        :message="form.errors.tujuan_penggunaan"
                                        class="mt-2"
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <InputLabel
                                        value="Cara Mendapatkan Informasi"
                                    />
                                    <RadioPilGroup
                                        class="mt-2"
                                        v-model="form.cara_mendapatkan"
                                        :options="caraMendapatkanInformasi"
                                        :cols="3"
                                    />
                                    <InputError
                                        :message="form.errors.cara_mendapatkan"
                                        class="mt-2"
                                    />
                                </div>
                            </div>
                        </template>
                    </Card>

                    <div class="flex items-center justify-between">
                        <Link
                            :href="
                                route('home.ppid.lacak_permohonan_informasi')
                            "
                            class="text-xs text-green-700 hover:underline flex items-center gap-1"
                        >
                            <SearchIcon class="w-3.5 h-3.5" />
                            Sudah punya nomor permohonan? Lacak di sini →
                        </Link>

                        <button
                            type="submit"
                            class="flex items-center gap-2 px-5 py-2.5 bg-green-800 text-white text-sm font-medium rounded-lg hover:bg-green-700 disabled:opacity-50 transition-colors"
                            :disabled="form.processing"
                        >
                            <SendIcon class="w-4 h-4" />
                            {{
                                form.processing
                                    ? "Mengirim..."
                                    : "Kirim Permohonan Informasi"
                            }}
                        </button>
                    </div>
                </form>
            </template>
        </BannerCard>

        <!-- <div class="py-10 max-w-2xl mx-auto px-4 sm:px-5">

        </div> -->
    </PpidLayout>
</template>
