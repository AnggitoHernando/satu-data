<script setup>
import PpidLayout from "@/Layouts/PpidLayout.vue";
import { Head, Link, useForm, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import InputWithButton from "@/Components/InputWithButton.vue";
import {
    Car,
    SendIcon,
    CheckCircle2,
    Clock,
    XCircle,
    FileSearch,
} from "lucide-vue-next";
import { QrCode } from "lucide-vue-next";
import BannerCard from "@/Components/BannerCard.vue";

// Dikirim dari PermohonanController@search — kosong/default kalau baru
// pertama buka halaman ini (belum pernah nyari apapun)
const props = defineProps({
    query: { type: String, default: "" },
    searched: { type: Boolean, default: false },
    found: { type: Boolean, default: false },
    permohonan: { type: Object, default: null },
});

const form = useForm({
    nomor_permohonan: props.query || "",
});
const isContrastMode = ref(false);
const submit = () => {
    console.log(form.nomor_permohonan);
    router.get(
        route("home.ppid.lacakPermohonan"),
        {
            search: form.nomor_permohonan,
        },
        {
            preserveState: true, // input yang lagi diketik nggak ke-reset pas hasil datang
        },
    );
};

// Styling badge status — beda warna tergantung tahap alurnya
const statusStyle = {
    diajukan: { badge: "bg-slate-100 text-slate-600", icon: Clock },
    diverifikasi: { badge: "bg-blue-50 text-blue-700", icon: Clock },
    diproses: { badge: "bg-amber-50 text-amber-700", icon: Clock },
    ditanggapi: { badge: "bg-emerald-50 text-emerald-700", icon: CheckCircle2 },
    selesai: { badge: "bg-emerald-50 text-emerald-700", icon: CheckCircle2 },
    ditolak: { badge: "bg-red-50 text-red-700", icon: XCircle },
};

const tahapUrutan = [
    "diajukan",
    "diverifikasi",
    "diproses",
    "ditanggapi",
    "selesai",
];
</script>
<template>
    <Head title="PPID Kemenag Gresik — Lacak Permohonan Informasi" />
    <PpidLayout :is-contrast-mode="isContrastMode">
        <BannerCard :useCard="false" :full-height="false">
            <template #banner-content>
                <div class="max-w-4xl mx-auto px-4">
                    <h1
                        class="text-2xl md:text-3xl text-white font-bold text-slate-800 tracking-tight mb-2"
                    >
                        Lacak Status Permohonan Informasi Atau Keberatan
                    </h1>

                    <p
                        class="text-sm md:text-base text-slate-600 max-w-2xl mx-auto mb-3 text-white"
                    >
                        Masukkan Nomor Registrasi Permohonan Anda (contoh:
                        PPID1234567 atau KBR1234567) untuk memantau progres
                        tindak lanjut.
                    </p>
                    <div class="mt-5 flex flex-col gap-4">
                        <InputWithButton
                            v-model="form.nomor_permohonan"
                            placeholder="Masukkan Nomor Registrasi Permohonan"
                            button-text="Lacak Progres"
                            class="w-full"
                            @submit="submit"
                        >
                            <template #icon>
                                <QrCode class="w-6 h-6 stroke-[2.2]" />
                            </template>
                        </InputWithButton>
                    </div>
                </div>
            </template>
        </BannerCard>

        <div class="max-w-4xl mx-auto px-4 py-8">
            <div
                v-if="!searched"
                class="flex flex-col items-center text-center text-slate-400 py-6"
            >
                <FileSearch class="w-10 h-10 mb-3" />
                <p class="text-sm">Hasil pencarian akan muncul di sini.</p>
            </div>

            <div
                v-else-if="!found"
                class="rounded-xl border border-red-100 bg-red-50 px-5 py-6 text-center"
            >
                <XCircle class="w-8 h-8 mx-auto mb-2 text-red-500" />
                <p class="text-sm font-semibold text-red-700">
                    Nomor registrasi "{{ query }}" tidak ditemukan.
                </p>
                <p class="text-xs text-red-500 mt-1">
                    Periksa kembali penulisan nomornya, pastikan sesuai dengan
                    yang tertera di email/bukti permohonan Anda.
                </p>
            </div>

            <div
                v-else
                class="rounded-xl border border-slate-200 bg-white overflow-hidden"
            >
                <div class="bg-slate-50 border-b border-slate-100 px-5 py-4">
                    <div class="text-xs text-slate-400">
                        {{ permohonan.jenis_label }}
                    </div>
                    <div class="font-mono text-sm font-bold text-slate-800">
                        {{ permohonan.nomor_registrasi }}
                    </div>
                </div>

                <div class="px-5 py-5">
                    <div class="flex items-center gap-2 mb-5">
                        <component
                            :is="statusStyle[permohonan.status]?.icon ?? Clock"
                            class="w-5 h-5"
                        />
                        <span
                            class="px-3 py-1 rounded-full text-sm font-semibold"
                            :class="
                                statusStyle[permohonan.status]?.badge ??
                                'bg-slate-100 text-slate-600'
                            "
                        >
                            {{ permohonan.status_label }}
                        </span>
                    </div>

                    <div
                        v-if="permohonan.status !== 'ditolak'"
                        class="flex items-center mb-6"
                    >
                        <template
                            v-for="(tahap, index) in tahapUrutan"
                            :key="tahap"
                        >
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold"
                                    :class="
                                        tahapUrutan.indexOf(
                                            permohonan.status,
                                        ) >= index
                                            ? 'bg-emerald-600 text-white'
                                            : 'bg-slate-100 text-slate-400'
                                    "
                                >
                                    {{ index + 1 }}
                                </div>
                            </div>
                            <div
                                v-if="index < tahapUrutan.length - 1"
                                class="flex-1 h-0.5 mx-1"
                                :class="
                                    tahapUrutan.indexOf(permohonan.status) >
                                    index
                                        ? 'bg-emerald-600'
                                        : 'bg-slate-100'
                                "
                            ></div>
                        </template>
                    </div>

                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-400">Pemohon</dt>
                            <dd class="text-slate-700 font-medium text-right">
                                {{ permohonan.nama_lengkap }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-400">Diajukan pada</dt>
                            <dd class="text-slate-700 font-medium text-right">
                                {{ permohonan.dibuat_pada }}
                            </dd>
                        </div>
                        <div
                            v-if="permohonan.permohonan_asal_nomor"
                            class="flex justify-between gap-4"
                        >
                            <dt class="text-slate-400">Keberatan atas</dt>
                            <dd
                                class="text-slate-700 font-mono text-xs font-medium text-right"
                            >
                                {{ permohonan.permohonan_asal_nomor }}
                            </dd>
                        </div>
                        <div
                            v-if="permohonan.jumlah_keberatan > 0"
                            class="flex justify-between gap-4"
                        >
                            <dt class="text-slate-400">Keberatan diajukan</dt>
                            <dd class="text-slate-700 font-medium text-right">
                                {{ permohonan.jumlah_keberatan }} kali
                            </dd>
                        </div>
                    </dl>

                    <div
                        v-if="permohonan.tanggapan"
                        class="mt-5 bg-emerald-50 rounded-lg px-4 py-3.5"
                    >
                        <div
                            class="text-xs font-semibold text-emerald-700 mb-1"
                        >
                            Tanggapan PPID
                            <span
                                v-if="permohonan.tanggal_tanggapan"
                                class="font-normal text-emerald-500"
                                >— {{ permohonan.tanggal_tanggapan }}</span
                            >
                        </div>
                        <p class="text-sm text-emerald-800">
                            {{ permohonan.tanggapan }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </PpidLayout>
</template>
