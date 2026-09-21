<script setup>
import { ref } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";

import {
    Search,
    FileText,
    FolderKanban,
    Scale,
    MapPin,
    BarChart3,
    Zap,
    Folder,
    Lock,
    ArrowRight,
    Smartphone,
    Sparkles,
    Sun,
    Instagram,
    Youtube,
    Facebook,
    Twitter,
} from "lucide-vue-next";
import PpidLayout from "@/Layouts/PpidLayout.vue";
const isContrastMode = ref(false);

// State Form
const searchQuery = ref("");
const trackNumber = ref("");

const handleSearch = () => {
    if (!searchQuery.value) return;
};

const handleTrack = () => {
    if (!trackNumber.value) return;
};

const toggleContrast = () => {
    isContrastMode.value = !isContrastMode.value;
};

// Data Berita Terkini
const categories = [
    {
        title: "Informasi Berkala",
        description:
            "Diumumkan rutin: kinerja, kegiatan, dan laporan keuangan Kemenag Gresik.",
        icon: BarChart3,
        color: "bg-[#B8E986] text-green-600",
        href: "home.ppid.informasi_berkala",
    },
    {
        title: "Informasi Serta-Merta",
        description:
            "Disampaikan tanpa penundaan kalau menyangkut hajat hidup orang banyak.",
        icon: Zap,
        color: "bg-[#BFE3F2] text-green-600",
        href: "home.ppid.informasiSertaMerta",
    },
    {
        title: "Informasi Setiap Saat",
        description:
            "Selalu tersedia: regulasi, struktur organisasi, dan prosedur layanan.",
        icon: Folder,
        color: "bg-[#FFD3A6] text-green-600",
        href: "home.ppid.informasiSetiapSaat",
    },
    {
        title: "Informasi Dikecualikan",
        description:
            "Info yang dibatasi aksesnya sesuai ketentuan Bab V UU No. 14/2008.",
        icon: Lock,
        color: "bg-[#F6C9D8] text-green-600",
        href: "home.ppid.informasiDikecualikan",
    },
];
</script>

<template>
    <Head title="PPID Kemenag Gresik — Informasi Publik" />
    <PpidLayout :is-contrast-mode="isContrastMode">
        <main>
            <section class="relative py-12 lg:py-16 overflow-hidden">
                <div
                    class="absolute w-[340px] h-[340px] rounded-full bg-[#B8E986] -top-32 -right-20 blur-[2px] opacity-55 pointer-events-none"
                ></div>
                <div
                    class="absolute w-[220px] h-[220px] rounded-full bg-[#BFE3F2] -bottom-16 -left-16 blur-[2px] opacity-45 pointer-events-none"
                ></div>

                <div class="max-w-[1180px] mx-auto px-5 relative z-10">
                    <div
                        class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-10 items-center"
                    >
                        <div>
                            <span
                                class="inline-flex items-center gap-1.5 bg-[#E4F5EC] text-green-600 font-bold text-xs px-4 py-1.5 rounded-full mb-4"
                            >
                                <Sparkles class="w-3.5 h-3.5 text-green-600" />
                                Layanan Informasi Publik Terintegrasi
                            </span>
                            <h1
                                class="text-3xl sm:text-4xl lg:text-[2.5rem] font-bold leading-tight font-serif"
                            >
                                Layanan Informasi Publik
                                <span class="text-green-600"
                                    >Kantor Kementerian Agama Kabupaten
                                    Gresik</span
                                >
                            </h1>
                            <p
                                :class="
                                    isContrastMode
                                        ? 'text-zinc-300'
                                        : 'text-[#6b7a72]'
                                "
                                class="mt-4 max-w-lg text-sm sm:text-base leading-relaxed"
                            >
                                Akses dokumen publik, ajukan permohonan
                                informasi, serta lacak status permohonan Anda
                                secara daring, transparan, dan akuntabel.
                            </p>

                            <form
                                @submit.prevent="handleSearch"
                                class="mt-6 flex items-center justify-between gap-2 max-w-md bg-white border border-[#E6EFE9] rounded-full p-1.5 pl-4 shadow-lg shadow-green-600/8"
                            >
                                <div
                                    class="flex items-center gap-2 flex-1 min-w-0"
                                >
                                    <Search
                                        class="w-4 h-4 text-gray-400 shrink-0"
                                    />
                                    <input
                                        v-model="searchQuery"
                                        type="search"
                                        placeholder="Cari, mis. laporan anggaran 2025..."
                                        class="w-full border-none outline-none text-xs sm:text-sm bg-transparent text-gray-800 placeholder-gray-400 focus:ring-0 focus:outline-none"
                                    />
                                </div>
                                <button
                                    type="submit"
                                    class="shrink-0 bg-green-600 hover:bg-green-600 text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-full transition-transform hover:-translate-y-0.5"
                                >
                                    Cari
                                </button>
                            </form>

                            <div class="flex flex-wrap gap-2.5 mt-5">
                                <a
                                    href="#layanan"
                                    class="inline-flex items-center gap-1.5 font-bold text-xs px-4 py-2.5 rounded-full bg-white text-green-600 border border-[#CDEEDD] hover:bg-[#E4F5EC] transition-transform hover:-translate-y-0.5 shadow-sm"
                                >
                                    <FileText class="w-4 h-4" /> Lihat Dokumen
                                    Publik
                                </a>
                                <a
                                    href="#kategori"
                                    class="inline-flex items-center gap-1.5 font-bold text-xs px-4 py-2.5 rounded-full bg-[#E4F5EC] text-green-600 hover:bg-[#CDEEDD] transition-transform hover:-translate-y-0.5"
                                >
                                    <FolderKanban class="w-4 h-4" /> Jelajahi
                                    Kategori
                                </a>
                            </div>

                            <div class="flex flex-wrap gap-2.5 mt-6">
                                <div
                                    class="flex items-center gap-2 bg-white border border-[#E6EFE9] rounded-full px-3.5 py-1.5 shadow-sm"
                                >
                                    <span
                                        class="font-bold font-serif text-green-600 text-xs sm:text-sm"
                                        >Gratis</span
                                    >
                                    <span class="text-[0.7rem] text-[#6b7a72]"
                                        >Layanan Informasi</span
                                    >
                                </div>
                                <div
                                    class="flex items-center gap-2 bg-white border border-[#E6EFE9] rounded-full px-3.5 py-1.5 shadow-sm"
                                >
                                    <span
                                        class="font-bold font-serif text-green-600 text-xs sm:text-sm"
                                        >24/7</span
                                    >
                                    <span class="text-[0.7rem] text-[#6b7a72]"
                                        >Akses Online</span
                                    >
                                </div>
                                <div
                                    class="flex items-center gap-2 bg-white border border-[#E6EFE9] rounded-full px-3.5 py-1.5 shadow-sm"
                                >
                                    <span
                                        class="font-bold font-serif text-green-600 text-xs sm:text-sm"
                                        >&lt;3 Hari</span
                                    >
                                    <span class="text-[0.7rem] text-[#6b7a72]"
                                        >Rata-rata Respon</span
                                    >
                                </div>
                                <div
                                    class="flex items-center gap-2 bg-white border border-[#E6EFE9] rounded-full px-3.5 py-1.5 shadow-sm"
                                >
                                    <span
                                        class="font-bold font-serif text-green-600 text-xs sm:text-sm"
                                        >UU KIP</span
                                    >
                                    <span class="text-[0.7rem] text-[#6b7a72]"
                                        >No.14/2008</span
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <div
                                class="bg-green-600 rounded-[28px] p-7 sm:p-8 text-white relative overflow-hidden shadow-2xl shadow-green-600/20"
                            >
                                <span
                                    class="inline-flex items-center gap-1.5 bg-white/20 text-[0.72rem] font-bold px-3 py-1 rounded-full backdrop-blur-sm"
                                >
                                    Layanan E-Form
                                </span>

                                <h3
                                    class="text-lg sm:text-xl font-bold mt-4 mb-1 font-serif"
                                >
                                    Pilih layanan yang kamu butuhin
                                </h3>
                                <p
                                    class="text-xs text-white/85 leading-relaxed mb-5"
                                >
                                    Semua proses bisa kamu lakukan sendiri dari
                                    HP atau laptop.
                                </p>

                                <ul class="space-y-2.5">
                                    <li
                                        class="flex items-center gap-3 bg-white/10 rounded-xl px-3.5 py-2.5 text-xs font-semibold backdrop-blur-sm"
                                    >
                                        <span
                                            class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center shrink-0"
                                        >
                                            <FileText
                                                class="w-4 h-4 text-white"
                                            />
                                        </span>
                                        <span>Permohonan Informasi Publik</span>
                                    </li>
                                    <li
                                        class="flex items-center gap-3 bg-white/10 rounded-xl px-3.5 py-2.5 text-xs font-semibold backdrop-blur-sm"
                                    >
                                        <span
                                            class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center shrink-0"
                                        >
                                            <Scale class="w-4 h-4 text-white" />
                                        </span>
                                        <span>Pengajuan Keberatan</span>
                                    </li>
                                    <li
                                        class="flex items-center gap-3 bg-white/10 rounded-xl px-3.5 py-2.5 text-xs font-semibold backdrop-blur-sm"
                                    >
                                        <span
                                            class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center shrink-0"
                                        >
                                            <MapPin
                                                class="w-4 h-4 text-white"
                                            />
                                        </span>
                                        <span>Lacak Status Progres</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section
                id="layanan"
                class="py-10 max-w-[1180px] mx-auto px-4 sm:px-5"
            >
                <div
                    class="bg-gradient-to-br from-[#E4F5EC] via-white to-white border border-[#E6EFE9] rounded-[28px] p-7 sm:p-10 relative overflow-hidden"
                >
                    <div
                        class="absolute w-[220px] h-[220px] rounded-full bg-[#FFD3A6] opacity-40 -right-16 -bottom-20 pointer-events-none"
                    ></div>

                    <div class="relative z-10">
                        <h2
                            class="text-xl sm:text-2xl font-bold font-serif mb-3 flex items-center gap-2"
                        >
                            Semua layanan PPID, dalam satu genggaman
                        </h2>
                        <p
                            :class="
                                isContrastMode
                                    ? 'text-zinc-300'
                                    : 'text-[#6b7a72]'
                            "
                            class="text-xs sm:text-sm leading-relaxed mb-6"
                        >
                            Nggak perlu datang ke kantor kalau nggak sempat —
                            ajukan permohonan, sampaikan keberatan, atau cek
                            status permohonanmu langsung dari sini.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div
                                class="group bg-white border border-[#E6EFE9] rounded-[28px] p-6 transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-green-600/10 flex flex-col justify-between"
                            >
                                <div>
                                    <div
                                        class="w-[52px] h-[52px] rounded-xl bg-[#B8E986] flex items-center justify-center text-green-600 mb-4 shrink-0"
                                    >
                                        <FileText class="w-6 h-6" />
                                    </div>
                                    <h3
                                        class="text-base sm:text-lg font-bold mb-2 font-serif"
                                    >
                                        Permohonan Informasi Publik
                                    </h3>
                                    <p
                                        :class="
                                            isContrastMode
                                                ? 'text-zinc-300'
                                                : 'text-[#6b7a72]'
                                        "
                                        class="text-xs leading-relaxed mb-5"
                                    >
                                        Ajukan permintaan akses dokumen atau
                                        data resmi secara mandiri dan online,
                                        kapan saja.
                                    </p>
                                </div>
                                <a
                                    :href="
                                        route('home.ppid.permohonan_informasi')
                                    "
                                    class="font-bold text-xs text-green-600 inline-flex items-center gap-1.5 group-hover:translate-x-1 transition-transform"
                                >
                                    Buka Formulir
                                    <ArrowRight class="w-3.5 h-3.5" />
                                </a>
                            </div>

                            <div
                                class="group bg-white border border-[#E6EFE9] rounded-[28px] p-6 transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#0B6E4F]/10 flex flex-col justify-between"
                            >
                                <div>
                                    <div
                                        class="w-[52px] h-[52px] rounded-xl bg-[#FFD3A6] flex items-center justify-center text-green-600 mb-4 shrink-0"
                                    >
                                        <Scale class="w-6 h-6" />
                                    </div>
                                    <h3
                                        class="text-base sm:text-lg font-bold mb-2 font-serif"
                                    >
                                        Pengajuan Keberatan Informasi
                                    </h3>
                                    <p
                                        :class="
                                            isContrastMode
                                                ? 'text-zinc-300'
                                                : 'text-[#6b7a72]'
                                        "
                                        class="text-xs leading-relaxed mb-5"
                                    >
                                        Nggak puas dengan jawaban permohonanmu?
                                        Ajukan keberatan resmi lewat form ini.
                                    </p>
                                </div>
                                <a
                                    :href="
                                        route('home.ppid.permohonan_keberatan')
                                    "
                                    class="font-bold text-xs text-green-600 inline-flex items-center gap-1.5 group-hover:translate-x-1 transition-transform"
                                >
                                    Buka Formulir
                                    <ArrowRight class="w-3.5 h-3.5" />
                                </a>
                            </div>
                        </div>

                        <div
                            class="mt-5 flex flex-wrap items-center gap-3 bg-[#E4F5EC] rounded-[20px] p-4 sm:p-5"
                        >
                            <p
                                class="text-xs font-semibold text-green-600 flex-1 min-w-[240px]"
                            >
                                Sudah punya nomor permohonan/keberatan? Lacak
                                progresnya di sini.
                            </p>
                            <form
                                @submit.prevent="handleTrack"
                                class="flex gap-2 flex-1 min-w-[280px]"
                            >
                                <input
                                    v-model="trackNumber"
                                    type="text"
                                    placeholder="Contoh: PPID-2026-0231"
                                    class="flex-1 border border-[#CDEEDD] rounded-full px-4 py-2 text-xs outline-none bg-white focus:ring-1 focus:ring-[#0B6E4F]"
                                />
                                <button
                                    type="submit"
                                    class="shrink-0 bg-green-600 hover:bg-green-600 text-white font-bold text-xs px-5 py-2 rounded-full transition-transform hover:-translate-y-0.5"
                                >
                                    Lacak
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <section id="kategori" class="py-12 max-w-[1180px] mx-auto px-5">
                <div class="mb-8 max-w-xl">
                    <p
                        class="text-green-600 font-bold text-xs uppercase tracking-wider mb-1"
                    >
                        KATEGORI INFORMASI
                    </p>
                    <h2 class="text-2xl font-bold font-serif">
                        Info publik, dikelompokkin biar gampang dicari
                    </h2>
                    <p
                        :class="
                            isContrastMode ? 'text-zinc-300' : 'text-[#6b7a72]'
                        "
                        class="mt-2 text-sm leading-relaxed"
                    >
                        Empat kategori sesuai UU Keterbukaan Informasi Publik
                        No. 14 Tahun 2008.
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4.5"
                >
                    <div
                        v-for="(item, index) in categories"
                        :key="index"
                        class="group bg-white border border-[#E6EFE9] rounded-[28px] p-6 transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-black/5 flex flex-col justify-between"
                    >
                        <div>
                            <div
                                :class="[
                                    item.color,
                                    'w-11 h-11 rounded-full flex items-center justify-center mb-4 shrink-0',
                                ]"
                            >
                                <component :is="item.icon" class="w-5 h-5" />
                            </div>
                            <h3 class="text-base font-bold mb-2 font-serif">
                                {{ item.title }}
                            </h3>
                            <p
                                :class="
                                    isContrastMode
                                        ? 'text-zinc-300'
                                        : 'text-[#6b7a72]'
                                "
                                class="text-xs leading-relaxed mb-4"
                            >
                                {{ item.description }}
                            </p>
                        </div>
                        <a
                            :href="route(item.href)"
                            class="font-bold text-xs text-green-600 inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform"
                        >
                            Lihat Info <ArrowRight class="w-3.5 h-3.5" />
                        </a>
                    </div>
                </div>
            </section>

            <section id="berita" class="py-12 max-w-[1180px] mx-auto px-5">
                <div class="mb-8 flex items-end justify-between gap-4">
                    <div>
                        <p
                            class="text-green-600 font-bold text-xs uppercase tracking-wider mb-1"
                        >
                            KABAR TERKINI
                        </p>
                        <h2 class="text-2xl font-bold font-serif">
                            Berita &amp; Kegiatan Kemenag Gresik
                        </h2>
                    </div>
                    <a
                        href="https://kemenaggresik.id/"
                        target="_blank"
                        class="font-bold text-xs text-green-600 whitespace-nowrap inline-flex items-center gap-1 hover:underline"
                    >
                        Lihat Semua <ArrowRight class="w-3.5 h-3.5" />
                    </a>
                </div>

                <div class="flex gap-4 overflow-x-auto pb-4 snap-x">
                    <a
                        v-for="(news, idx) in usePage().props.beritaTerkini"
                        :href="news.url"
                        target="_blank"
                        :key="idx"
                        class="snap-start pointer shrink-0 w-[270px] bg-white border border-[#E6EFE9] rounded-[20px] overflow-hidden transition-transform duration-200 hover:-translate-y-1"
                    >
                        <div
                            :class="[
                                'h-[130px] bg-gradient-to-br',
                                news.image ? '' : 'bg-gray-100',
                            ]"
                        >
                            <img
                                v-if="news.image"
                                :src="news.image"
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <div class="p-4">
                            <span
                                class="inline-block text-[0.68rem] font-bold text-green-600 bg-[#E4F5EC] px-2.5 py-1 rounded-full mb-2"
                            >
                                KEMENAG GRESIK
                            </span>
                            <h3
                                class="text-xs font-bold leading-snug mb-2 font-serif line-clamp-4"
                            >
                                {{ news.title }}
                            </h3>
                            <p
                                :class="
                                    isContrastMode
                                        ? 'text-zinc-400'
                                        : 'text-[#6b7a72]'
                                "
                                class="text-[0.72rem]"
                            >
                                {{ news.created }}
                            </p>
                        </div>
                    </a>
                </div>
            </section>
        </main>
    </PpidLayout>
</template>
