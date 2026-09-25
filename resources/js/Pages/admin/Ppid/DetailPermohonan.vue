<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import Card from "@/Components/Card.vue";
import { Car } from "lucide-vue-next";
import InputLabel from "@/Components/InputLabel.vue";
import TextArea from "@/Components/TextArea.vue";
import RadioPilGroup from "@/Components/RadioPilGroup.vue";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";

// Dikirim dari Admin\PermohonanController@show
const props = defineProps({
    permohonan: { type: Object, required: true },
});

console.log(props.permohonan);

const STATUS_LIST = [
    { value: "diajukan", label: "Diajukan" },
    { value: "diverifikasi", label: "Diverifikasi" },
    { value: "diproses", label: "Diproses" },
    { value: "ditanggapi", label: "Ditanggapi" },
    { value: "selesai", label: "Selesai" },
    { value: "ditolak", label: "Ditolak" },
];

const form = useForm({
    status: props.permohonan.status,
    tanggapan: props.permohonan.tanggapan ?? "",
});

const isDitolak = computed(() => form.status === "ditolak");

function radioClass(active, isReject) {
    if (active && isReject) return "border-red-600 bg-red-50";
    if (active) return "border-emerald-600 bg-emerald-50";
    return "border-slate-200";
}

function submit() {
    form.put(route("admin.permohonan.update-status", props.permohonan.id));
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Detail Permohonan" />
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Card header="Detail Permohonan" number="1">
                    <template #header> Detail Permohonan </template>
                    <template #body>
                        <div class="text-xl font-bold tracking-tight">
                            {{ permohonan.nomor_registrasi }}
                        </div>
                        <div
                            class="mt-1 capitalize font-mono text-xs text-slate-500"
                        >
                            {{ permohonan.jenis }}
                        </div>
                    </template>
                </Card>

                <div class="max-w-5xl px-8 py-6">
                    <div
                        class="grid grid-cols-1 gap-4 lg:grid-cols-[1.1fr_.9fr] lg:items-start"
                    >
                        <div>
                            <Card header="Data Pemohon" class="mb-4" number="2">
                                <template #body>
                                    <dl class="space-y-2 text-sm">
                                        <div
                                            class="flex justify-between gap-3 border-b border-slate-100 pb-2"
                                        >
                                            <dt class="text-slate-400">Nama</dt>
                                            <dd class="font-medium">
                                                {{ permohonan.nama_lengkap }}
                                            </dd>
                                        </div>
                                        <div
                                            class="flex justify-between gap-3 border-b border-slate-100 pb-2"
                                        >
                                            <dt class="text-slate-400">
                                                Email
                                            </dt>
                                            <dd class="font-medium">
                                                {{ permohonan.email }}
                                            </dd>
                                        </div>
                                        <div
                                            class="flex justify-between gap-3 border-b border-slate-100 pb-2"
                                        >
                                            <dt class="text-slate-400">
                                                Pekerjaan
                                            </dt>
                                            <dd class="font-medium">
                                                {{
                                                    permohonan.pekerjaan ?? "—"
                                                }}
                                            </dd>
                                        </div>
                                        <div
                                            class="flex justify-between gap-3 border-b border-slate-100 pb-2"
                                        >
                                            <dt class="text-slate-400">
                                                No. Telepon
                                            </dt>
                                            <dd class="font-medium">
                                                {{ permohonan.no_telepon }}
                                            </dd>
                                        </div>
                                        <div class="flex justify-between gap-3">
                                            <dt class="text-slate-400">
                                                Alamat
                                            </dt>
                                            <dd class="text-right font-medium">
                                                {{ permohonan.alamat }}
                                            </dd>
                                        </div>
                                    </dl>
                                    <a
                                        v-if="permohonan.bukti_identitas"
                                        :href="`/storage/${permohonan.bukti_identitas}`"
                                        target="_blank"
                                        class="mt-3 inline-flex items-center gap-1.5 rounded-md border border-slate-200 px-3 py-1.5 text-xs font-medium text-emerald-700 hover:border-emerald-600"
                                    >
                                        Lihat Bukti Identitas
                                    </a>
                                </template>
                            </Card>

                            <Card
                                v-if="permohonan.jenis === 'informasi'"
                                header="Rincian Informasi Diminta"
                                number="3"
                            >
                                <template #body>
                                    <h3 class="mb-3 text-sm font-bold">
                                        Rincian Informasi Diminta
                                    </h3>
                                    <div
                                        class="rounded-md bg-slate-50 p-3 text-sm leading-relaxed text-slate-700"
                                    >
                                        {{ permohonan.rincian_informasi }}
                                    </div>
                                    <dl class="mt-3 space-y-2 text-sm">
                                        <div
                                            class="flex justify-between gap-3 border-b border-slate-100 pb-2"
                                        >
                                            <dt class="text-slate-400">
                                                Tujuan Penggunaan
                                            </dt>
                                            <dd class="text-right font-medium">
                                                {{
                                                    permohonan.tujuan_penggunaan
                                                }}
                                            </dd>
                                        </div>
                                        <div class="flex justify-between gap-3">
                                            <dt class="text-slate-400">
                                                Cara Mendapatkan
                                            </dt>
                                            <dd class="font-medium">
                                                {{
                                                    permohonan.cara_mendapatkan_label
                                                }}
                                            </dd>
                                        </div>
                                    </dl>
                                </template>
                            </Card>

                            <Card v-else header="Detail Keberatan" number="3">
                                <template #body>
                                    <h3 class="mb-3 text-sm font-bold">
                                        Detail Keberatan
                                    </h3>
                                    <div
                                        class="mb-3 flex justify-between gap-3 border-b border-slate-100 pb-2 text-sm"
                                    >
                                        <dt class="text-slate-400">
                                            Atas Permohonan
                                        </dt>
                                        <dd
                                            class="font-mono text-xs font-medium"
                                        >
                                            {{
                                                permohonan.permohonan_asal_nomor
                                            }}
                                        </dd>
                                    </div>
                                    <div
                                        class="rounded-md bg-slate-50 p-3 text-sm leading-relaxed text-slate-700"
                                    >
                                        {{ permohonan.alasan_keberatan }}
                                    </div>
                                    <div
                                        class="mt-3 flex justify-between gap-3 text-sm"
                                    >
                                        <dt class="text-slate-400">
                                            Tujuan Penggunaan
                                        </dt>
                                        <dd class="text-right font-medium">
                                            {{ permohonan.tujuan_penggunaan }}
                                        </dd>
                                    </div>
                                </template>
                            </Card>
                        </div>

                        <Card header="Tindak Lanjut" class="w-full" number="4">
                            <template #body>
                                <form @submit.prevent="submit">
                                    <div class="mb-4">
                                        <InputLabel
                                            label="Ubah Status"
                                            class="mb-1.5"
                                        />
                                        <RadioPilGroup
                                            v-model="form.status"
                                            :options="STATUS_LIST"
                                            class="w-full"
                                            cols="2"
                                        />
                                    </div>

                                    <div
                                        v-if="isDitolak"
                                        class="mb-4 rounded-md border border-red-300 bg-red-50 p-3"
                                    >
                                        <InputLabel
                                            label="Alasan Penolakan *"
                                            class="mb-1.5"
                                        />
                                        <TextArea
                                            v-model="form.tanggapan"
                                            rows="4"
                                            placeholder="Jelaskan dasar hukum/alasan penolakan — ini akan dikirim ke pemohon"
                                            class="w-full rounded-md border border-red-200 px-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-2 focus:ring-red-600"
                                        />
                                        <div
                                            v-if="form.errors.tanggapan"
                                            class="mt-1.5 text-xs text-red-600"
                                        >
                                            {{ form.errors.tanggapan }}
                                        </div>
                                    </div>

                                    <div v-else class="mb-4">
                                        <InputLabel
                                            label="Tanggapan / Catatan untuk Pemohon"
                                            class="mb-1.5"
                                        />
                                        <TextArea
                                            v-model="form.tanggapan"
                                            rows="4"
                                            placeholder="Isi jawaban resmi PPID di sini…"
                                            class="w-full rounded-md border border-slate-200 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600"
                                        />
                                        <div
                                            class="mt-1.5 text-xs text-slate-500"
                                        >
                                            Pemohon bisa melihat ini lewat fitur
                                            "Lacak Permohonan".
                                        </div>
                                    </div>

                                    <div
                                        class="flex justify-end gap-2 border-t border-slate-100 pt-3"
                                    >
                                        <Link
                                            :href="
                                                route('admin.permohonan.index')
                                            "
                                            class="rounded-md border border-slate-200 px-4 py-2 text-sm font-semibold hover:border-slate-400"
                                            >Batal</Link
                                        >
                                        <PrimaryButtonAdmin
                                            type="submit"
                                            :disabled="form.processing"
                                            class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800 disabled:opacity-60"
                                        >
                                            {{
                                                form.processing
                                                    ? "Menyimpan…"
                                                    : "Simpan Tanggapan"
                                            }}
                                        </PrimaryButtonAdmin>
                                    </div>
                                </form>
                            </template>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
