<script setup>
import { computed, ref } from "vue";
import BannerCard from "@/Components/BannerCard.vue";
import { Head, usePage } from "@inertiajs/vue3";
import PpidLayout from "@/Layouts/PpidLayout.vue";
import { Link } from "@inertiajs/vue3";
import {
    ChevronRight,
    CalendarDays,
    FileText,
    FileDown,
    ExternalLink,
    Info,
    Download,
    Lightbulb,
} from "lucide-vue-next";

const props = defineProps({
    menu: {
        type: Object,
        required: true,
    },
    dokumen: {
        type: Object,
        required: true,
    },
    dokumenLainnya: {
        type: Array,
        default: () => [],
    },
});

const lampiran = computed(() => props.dokumen.lampiran ?? []);

const selectedId = ref(lampiran.value[0]?.id ?? null);

const selectedFile = computed(
    () => lampiran.value.find((file) => file.id === selectedId.value) ?? null,
);

const isPdf = (file) => {
    if (!file) return false;

    const type = (file.type ?? "").toLowerCase();
    const url = (file.url ?? "").split("?")[0].toLowerCase();

    return type.includes("pdf") || url.endsWith(".pdf");
};

const formatDate = (value) => {
    if (!value) return null;

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) return value;

    return new Intl.DateTimeFormat("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    }).format(date);
};
</script>

<template>
    <Head :title="`PPID Kemenag Gresik - ${usePage().props.menu.nama_menu}`">
        <meta name="description" content="Informasi Publik" />
    </Head>
    <PpidLayout>
        <BannerCard :use-card="false" :full-height="false">
            <template #banner-content>
                <div class="text-left">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[11px] font-semibold text-white"
                    >
                        <FileText :size="13" />
                        {{ dokumen.kategori_label || "DOKUMEN RESMI" }}
                    </span>

                    <div
                        class="mt-4 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between"
                    >
                        <div class="min-w-0 flex-1">
                            <h1
                                class="max-w-4xl text-2xl font-extrabold leading-tight tracking-tight text-white sm:text-3xl"
                            >
                                {{ dokumen.judul }}
                            </h1>

                            <div
                                class="mt-4 flex flex-wrap items-center gap-4 text-xs text-emerald-100"
                            >
                                <span class="flex items-center gap-2">
                                    <CalendarDays :size="14" />
                                    Tahun {{ dokumen.tahun || "-" }}
                                </span>
                            </div>
                        </div>

                        <a
                            v-if="selectedFile"
                            :href="`/storage/${selectedFile.url}`"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-white/30 px-4 py-3 text-xs font-bold text-white transition hover:bg-white/10"
                        >
                            <ExternalLink :size="15" />
                            Buka Lampiran
                        </a>
                    </div>
                </div>
            </template>

            <template #content>
                <main class="mx-auto max-w-7xl px-4 pb-16 pt-7 sm:px-6">
                    <div
                        class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12"
                    >
                        <div class="min-w-0 lg:col-span-8">
                            <section
                                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
                            >
                                <div
                                    class="mb-4 flex items-center justify-between gap-3 border-b border-slate-100 pb-4"
                                >
                                    <h2
                                        class="flex items-center gap-2 text-sm font-bold text-slate-900"
                                    >
                                        <FileText
                                            :size="17"
                                            class="text-emerald-600"
                                        />
                                        Lampiran Dokumen
                                    </h2>

                                    <span
                                        class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700"
                                    >
                                        {{ lampiran.length }} berkas
                                    </span>
                                </div>

                                <div v-if="lampiran.length" class="space-y-2">
                                    <button
                                        v-for="file in lampiran"
                                        :key="file.id"
                                        type="button"
                                        @click="selectedId = file.id"
                                        class="flex w-full items-center gap-3 rounded-xl border p-3 text-left transition"
                                        :class="
                                            selectedId === file.id
                                                ? 'border-emerald-500 bg-emerald-50'
                                                : 'border-slate-200 hover:border-emerald-300'
                                        "
                                    >
                                        <span
                                            class="rounded-lg bg-white p-2 text-emerald-700"
                                        >
                                            <FileText :size="20" />
                                        </span>

                                        <span class="min-w-0 flex-1">
                                            <span
                                                class="block truncate text-sm font-semibold text-slate-800"
                                            >
                                                {{ file.name }}
                                            </span>
                                            <span
                                                class="mt-1 block text-xs text-slate-500"
                                            >
                                                {{ file.type || "Dokumen" }}
                                            </span>
                                        </span>

                                        <FileDown
                                            :size="17"
                                            class="shrink-0 text-slate-400"
                                        />
                                    </button>
                                </div>

                                <p
                                    v-else
                                    class="py-8 text-center text-sm text-slate-500"
                                >
                                    Belum ada lampiran untuk informasi ini.
                                </p>
                            </section>

                            <section
                                v-if="selectedFile && isPdf(selectedFile)"
                                class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                            >
                                <div
                                    class="flex items-center justify-between gap-3 bg-slate-900 px-4 py-3"
                                >
                                    <span
                                        class="flex min-w-0 items-center gap-2 text-xs font-semibold text-emerald-300"
                                    >
                                        <FileText :size="15" />
                                        <span class="truncate">
                                            {{ selectedFile.name }}
                                        </span>
                                    </span>

                                    <a
                                        :href="`/storage/${selectedFile.url}`"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex shrink-0 items-center gap-1.5 text-xs text-white hover:text-emerald-300"
                                    >
                                        Buka Tab Baru
                                        <ExternalLink :size="13" />
                                    </a>
                                </div>

                                <iframe
                                    :src="`/storage/${selectedFile.url}#toolbar=1&navpanes=1&view=FitH`"
                                    :title="selectedFile.name"
                                    class="h-[65vh] min-h-[420px] w-full border-0 bg-slate-100 lg:h-[580px]"
                                />
                            </section>

                            <section
                                v-else-if="selectedFile"
                                class="mt-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
                            >
                                <div class="flex items-start gap-4">
                                    <span
                                        class="rounded-xl bg-emerald-50 p-3 text-emerald-700"
                                    >
                                        <FileText :size="25" />
                                    </span>

                                    <div class="min-w-0 flex-1">
                                        <h2
                                            class="text-sm font-bold text-slate-900"
                                        >
                                            {{ selectedFile.name }}
                                        </h2>

                                        <p
                                            class="mt-2 text-sm leading-6 text-slate-500"
                                        >
                                            Pratinjau tidak ditampilkan untuk
                                            format berkas ini.
                                        </p>

                                        <a
                                            :href="selectedFile.url"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            download
                                            class="mt-4 inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-3 text-xs font-bold text-white hover:bg-emerald-800"
                                        >
                                            <Download :size="15" />
                                            Unduh Lampiran
                                        </a>
                                    </div>
                                </div>
                            </section>

                            <section
                                class="mt-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"
                            >
                                <h2
                                    class="flex items-center gap-2 border-b border-slate-100 pb-4 text-sm font-bold text-slate-900"
                                >
                                    <Info :size="17" class="text-emerald-600" />
                                    Rincian Dokumen
                                </h2>

                                <dl class="mt-4 space-y-4 text-sm">
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-slate-500">Judul</dt>
                                        <dd
                                            class="max-w-[70%] text-right font-medium text-slate-800"
                                        >
                                            {{ dokumen.judul }}
                                        </dd>
                                    </div>

                                    <div class="flex justify-between gap-4">
                                        <dt class="text-slate-500">Tahun</dt>
                                        <dd class="font-medium text-slate-800">
                                            {{ dokumen.tahun || "-" }}
                                        </dd>
                                    </div>

                                    <div class="flex justify-between gap-4">
                                        <dt class="text-slate-500">
                                            Ringkasan
                                        </dt>
                                        <dd
                                            class="text-right font-medium text-slate-800"
                                        >
                                            {{ dokumen.ringkasan || "-" }}
                                        </dd>
                                    </div>
                                </dl>
                            </section>
                        </div>

                        <aside class="lg:col-span-4">
                            <section
                                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
                            >
                                <h3
                                    class="mb-4 text-lg font-bold text-gray-900"
                                >
                                    Dokumen Lainnya
                                </h3>

                                <div
                                    v-if="dokumenLainnya.length"
                                    class="space-y-3"
                                >
                                    <Link
                                        v-for="item in dokumenLainnya"
                                        :key="item.id"
                                        :href="
                                            route('home.ppid.detail_dokumen', {
                                                fullPath: item.menu.slug,
                                                dokumenId: item.id,
                                            })
                                        "
                                        class="group flex gap-3 rounded-xl border border-green-200 p-3 transition hover:border-emerald-200 hover:bg-emerald-50"
                                    >
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 transition group-hover:bg-emerald-200"
                                        >
                                            <FileText
                                                :size="20"
                                                :stroke-width="1.8"
                                            />
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <p
                                                class="line-clamp-2 text-sm font-semibold text-gray-800 transition group-hover:text-emerald-800"
                                            >
                                                {{ item.nama_informasi }}
                                            </p>

                                            <div
                                                class="mt-2 flex items-center gap-2 text-xs text-gray-500"
                                            >
                                                <span>{{
                                                    item.tahun || "—"
                                                }}</span>
                                            </div>
                                        </div>
                                    </Link>
                                </div>

                                <p v-else class="text-sm text-gray-500">
                                    Belum ada dokumen lainnya.
                                </p>
                            </section>
                        </aside>
                    </div>
                </main>
            </template>
        </BannerCard>
    </PpidLayout>
</template>
