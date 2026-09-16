<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
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

// State Fitur Aksesibilitas
const fontScale = ref(1);
const isContrastMode = ref(false);

// State Form
const searchQuery = ref("");
const trackNumber = ref("");

const changeFontSize = (delta) => {
    if (delta > 0 && fontScale.value < 1.25) fontScale.value += 0.125;
    if (delta < 0 && fontScale.value > 1) fontScale.value -= 0.125;
};

const resetFontSize = () => {
    fontScale.value = 1;
};

const toggleContrast = () => {
    isContrastMode.value = !isContrastMode.value;
};

const handleSearch = () => {
    if (!searchQuery.value) return;
};

const handleTrack = () => {
    if (!trackNumber.value) return;
};

// Data Kategori Informasi
const categories = [
    {
        title: "Informasi Berkala",
        description:
            "Diumumkan rutin: kinerja, kegiatan, dan laporan keuangan Kemenag Gresik.",
        icon: BarChart3,
        color: "bg-[#B8E986] text-[#085239]",
    },
    {
        title: "Informasi Serta-Merta",
        description:
            "Disampaikan tanpa penundaan kalau menyangkut hajat hidup orang banyak.",
        icon: Zap,
        color: "bg-[#BFE3F2] text-[#085239]",
    },
    {
        title: "Informasi Setiap Saat",
        description:
            "Selalu tersedia: regulasi, struktur organisasi, dan prosedur layanan.",
        icon: Folder,
        color: "bg-[#FFD3A6] text-[#085239]",
    },
    {
        title: "Informasi Dikecualikan",
        description:
            "Info yang dibatasi aksesnya sesuai ketentuan Bab V UU No. 14/2008.",
        icon: Lock,
        color: "bg-[#F6C9D8] text-[#085239]",
    },
];

// Data Berita Terkini
const newsList = [
    {
        tag: "Berita Utama",
        title: "Kemenag Gresik Perkuat Layanan Bimbingan Perkawinan di KUA",
        date: "16 September 2026",
        gradient: "from-[#B8E986] to-[#E4F5EC]",
    },
    {
        tag: "Pendidikan",
        title: "Siswa Madrasah Gresik Latihan Tilawah Jelang MTQ Pelajar",
        date: "16 September 2026",
        gradient: "from-[#BFE3F2] to-[#E4F5EC]",
    },
    {
        tag: "Layanan Publik",
        title: "Penyuluh Agama Layani Warga di Mal Pelayanan Publik Gresik",
        date: "15 September 2026",
        gradient: "from-[#FFD3A6] to-[#E4F5EC]",
    },
    {
        tag: "Haji & Umrah",
        title: "Update Daftar Tunggu Jemaah Haji Kabupaten Gresik 2026",
        date: "14 September 2026",
        gradient: "from-[#F6C9D8] to-[#E4F5EC]",
    },
];
</script>

<template>
    <Head title="PPID Kemenag Gresik — Informasi Publik" />

    <div
        :class="[
            'min-h-screen font-sans transition-colors duration-200 selection:bg-[#0B6E4F] selection:text-white',
            isContrastMode
                ? 'bg-black text-yellow-300'
                : 'bg-[#FBFDFC] text-[#1B2420]',
        ]"
        :style="{ fontSize: `${fontScale * 16}px` }"
    >
        <!-- ============ UTIL BAR ============ -->
        <div
            :class="
                isContrastMode
                    ? 'bg-zinc-900 border-b border-yellow-300'
                    : 'bg-[#E4F5EC]'
            "
            class="text-xs"
        >
            <div
                class="max-w-[1180px] mx-auto px-5 py-1.5 flex flex-wrap items-center justify-between gap-2"
            >
                <p
                    :class="
                        isContrastMode ? 'text-yellow-300' : 'text-[#085239]'
                    "
                    class="font-semibold flex items-center gap-1.5"
                >
                    🌿 Situs Resmi PPID Kemenag Gresik
                </p>
                <div class="flex items-center gap-2">
                    <div
                        class="flex items-center bg-white rounded-full p-0.5 shadow-sm"
                    >
                        <button
                            type="button"
                            @click="changeFontSize(-1)"
                            class="border-0 bg-transparent font-bold text-xs text-[#085239] hover:bg-[#CDEEDD] px-2 py-0.5 rounded-full"
                            aria-label="Perkecil huruf"
                        >
                            A-
                        </button>
                        <button
                            type="button"
                            @click="resetFontSize"
                            class="border-0 bg-transparent font-bold text-xs text-[#085239] hover:bg-[#CDEEDD] px-2 py-0.5 rounded-full"
                            aria-label="Huruf normal"
                        >
                            A
                        </button>
                        <button
                            type="button"
                            @click="changeFontSize(1)"
                            class="border-0 bg-transparent font-bold text-xs text-[#085239] hover:bg-[#CDEEDD] px-2 py-0.5 rounded-full"
                            aria-label="Perbesar huruf"
                        >
                            A+
                        </button>
                    </div>
                    <button
                        type="button"
                        @click="toggleContrast"
                        class="flex items-center gap-1 bg-white font-bold text-xs text-[#085239] hover:bg-[#CDEEDD] px-3 py-1 rounded-full shadow-sm"
                    >
                        <Sun class="w-3.5 h-3.5" /> Kontras
                    </button>
                </div>
            </div>
        </div>

        <!-- ============ NAVBAR ============ -->
        <div class="sticky top-3 z-40 px-4">
            <header
                :class="
                    isContrastMode
                        ? 'bg-black border-yellow-300'
                        : 'bg-white/85 backdrop-blur-md border-[#E6EFE9]'
                "
                class="max-w-[1180px] mx-auto border rounded-full shadow-lg shadow-[#0B6E4F]/10"
            >
                <div class="flex items-center justify-between gap-3 px-4 py-2">
                    <Link href="/" class="flex items-center gap-2.5">
                        <span
                            class="w-10 h-10 rounded-full bg-[#0B6E4F] text-white flex items-center justify-center font-bold text-sm shrink-0"
                        >
                            KG
                        </span>
                        <span class="flex flex-col">
                            <span class="font-bold text-sm leading-tight"
                                >PPID Kemenag Gresik</span
                            >
                            <span
                                :class="
                                    isContrastMode
                                        ? 'text-yellow-200'
                                        : 'text-[#6b7a72]'
                                "
                                class="text-[0.68rem]"
                                >Informasi Publik yang Ramah &amp; Terbuka</span
                            >
                        </span>
                    </Link>

                    <nav
                        class="hidden md:flex items-center gap-1 text-sm font-semibold"
                    >
                        <a
                            href="#tentang"
                            :class="
                                isContrastMode
                                    ? 'hover:bg-zinc-800'
                                    : 'hover:bg-[#E4F5EC] hover:text-[#085239]'
                            "
                            class="px-3.5 py-1.5 rounded-full transition-colors"
                            >Tentang</a
                        >
                        <a
                            href="#layanan"
                            :class="
                                isContrastMode
                                    ? 'hover:bg-zinc-800'
                                    : 'hover:bg-[#E4F5EC] hover:text-[#085239]'
                            "
                            class="px-3.5 py-1.5 rounded-full transition-colors"
                            >Layanan</a
                        >
                        <a
                            href="#kategori"
                            :class="
                                isContrastMode
                                    ? 'hover:bg-zinc-800'
                                    : 'hover:bg-[#E4F5EC] hover:text-[#085239]'
                            "
                            class="px-3.5 py-1.5 rounded-full transition-colors"
                            >Informasi Publik</a
                        >
                        <a
                            href="#berita"
                            :class="
                                isContrastMode
                                    ? 'hover:bg-zinc-800'
                                    : 'hover:bg-[#E4F5EC] hover:text-[#085239]'
                            "
                            class="px-3.5 py-1.5 rounded-full transition-colors"
                            >Berita</a
                        >
                    </nav>

                    <a
                        href="#layanan"
                        class="inline-flex items-center gap-1 font-bold text-xs px-4 py-2.5 rounded-full bg-[#0B6E4F] hover:bg-[#085239] text-white shadow-md shadow-[#0B6E4F]/25 transition-transform hover:-translate-y-0.5 whitespace-nowrap"
                    >
                        Ajukan ✦
                    </a>
                </div>
            </header>
        </div>

        <main>
            <!-- ============ HERO ============ -->
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
                                class="inline-flex items-center gap-1.5 bg-[#E4F5EC] text-[#085239] font-bold text-xs px-4 py-1.5 rounded-full mb-4"
                            >
                                <Sparkles class="w-3.5 h-3.5 text-[#0B6E4F]" />
                                Layanan Informasi Publik Terintegrasi
                            </span>
                            <h1
                                class="text-3xl sm:text-4xl lg:text-[2.5rem] font-bold leading-tight font-serif"
                            >
                                Cari info Kemenag Gresik?
                                <span class="text-[#0B6E4F]"
                                    >Gampang &amp; santai.</span
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
                                Akses dokumen, ajukan permohonan, atau lacak
                                status permohonanmu — semuanya online,
                                transparan, dan nggak ribet.
                            </p>

                            <!-- Search Bar -->
                            <form
                                @submit.prevent="handleSearch"
                                class="mt-6 flex items-center justify-between gap-2 max-w-md bg-white border border-[#E6EFE9] rounded-full p-1.5 pl-4 shadow-lg shadow-[#0B6E4F]/8"
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
                                    class="shrink-0 bg-[#0B6E4F] hover:bg-[#085239] text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-full transition-transform hover:-translate-y-0.5"
                                >
                                    Cari
                                </button>
                            </form>

                            <!-- Hero Actions -->
                            <div class="flex flex-wrap gap-2.5 mt-5">
                                <a
                                    href="#layanan"
                                    class="inline-flex items-center gap-1.5 font-bold text-xs px-4 py-2.5 rounded-full bg-white text-[#085239] border border-[#CDEEDD] hover:bg-[#E4F5EC] transition-transform hover:-translate-y-0.5 shadow-sm"
                                >
                                    <FileText class="w-4 h-4" /> Lihat Dokumen
                                    Publik
                                </a>
                                <a
                                    href="#kategori"
                                    class="inline-flex items-center gap-1.5 font-bold text-xs px-4 py-2.5 rounded-full bg-[#E4F5EC] text-[#085239] hover:bg-[#CDEEDD] transition-transform hover:-translate-y-0.5"
                                >
                                    <FolderKanban class="w-4 h-4" /> Jelajahi
                                    Kategori
                                </a>
                            </div>

                            <!-- Stat Chips -->
                            <div class="flex flex-wrap gap-2.5 mt-6">
                                <div
                                    class="flex items-center gap-2 bg-white border border-[#E6EFE9] rounded-full px-3.5 py-1.5 shadow-sm"
                                >
                                    <span
                                        class="font-bold font-serif text-[#085239] text-xs sm:text-sm"
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
                                        class="font-bold font-serif text-[#085239] text-xs sm:text-sm"
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
                                        class="font-bold font-serif text-[#085239] text-xs sm:text-sm"
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
                                        class="font-bold font-serif text-[#085239] text-xs sm:text-sm"
                                        >UU KIP</span
                                    >
                                    <span class="text-[0.7rem] text-[#6b7a72]"
                                        >No.14/2008</span
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Hero Visual Card -->
                        <div class="relative">
                            <div
                                class="bg-gradient-to-br from-[#0B6E4F] to-[#085239] rounded-[28px] p-7 sm:p-8 text-white relative overflow-hidden shadow-2xl shadow-[#0B6E4F]/25"
                            >
                                <div
                                    class="absolute w-40 h-40 rounded-full bg-white/10 -top-12 -right-12 pointer-events-none"
                                ></div>

                                <span
                                    class="inline-flex items-center gap-1.5 bg-white/20 text-[0.72rem] font-bold px-3 py-1 rounded-full backdrop-blur-sm"
                                >
                                    🚀 Layanan E-Form
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

            <!-- ============ LAYANAN E-FORM ============ -->
            <section id="layanan" class="py-12 max-w-[1180px] mx-auto px-5">
                <div class="mb-8 max-w-xl">
                    <p
                        class="text-[#0B6E4F] font-bold text-xs uppercase tracking-wider mb-1"
                    >
                        LAYANAN MANDIRI
                    </p>
                    <h2 class="text-2xl font-bold font-serif">
                        Ajukan sendiri, tanpa antre
                    </h2>
                    <p
                        :class="
                            isContrastMode ? 'text-zinc-300' : 'text-[#6b7a72]'
                        "
                        class="mt-2 text-sm leading-relaxed"
                    >
                        Pilih layanan yang kamu perlukan, isi formulirnya
                        online, terus tinggal pantau progresnya.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Card 1 -->
                    <div
                        class="group bg-white border border-[#E6EFE9] rounded-[28px] p-6 transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#0B6E4F]/10 flex flex-col justify-between"
                    >
                        <div>
                            <div
                                class="w-[52px] h-[52px] rounded-xl bg-[#B8E986] flex items-center justify-center text-[#085239] mb-4 shrink-0"
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
                                Ajukan permintaan akses dokumen atau data resmi
                                secara mandiri dan online, kapan saja.
                            </p>
                        </div>
                        <a
                            href="#"
                            class="font-bold text-xs text-[#085239] inline-flex items-center gap-1.5 group-hover:translate-x-1 transition-transform"
                        >
                            Buka Formulir <ArrowRight class="w-3.5 h-3.5" />
                        </a>
                    </div>

                    <!-- Card 2 -->
                    <div
                        class="group bg-white border border-[#E6EFE9] rounded-[28px] p-6 transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#0B6E4F]/10 flex flex-col justify-between"
                    >
                        <div>
                            <div
                                class="w-[52px] h-[52px] rounded-xl bg-[#FFD3A6] flex items-center justify-center text-[#085239] mb-4 shrink-0"
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
                                Nggak puas dengan jawaban permohonanmu? Ajukan
                                keberatan resmi lewat form ini.
                            </p>
                        </div>
                        <a
                            href="#"
                            class="font-bold text-xs text-[#085239] inline-flex items-center gap-1.5 group-hover:translate-x-1 transition-transform"
                        >
                            Buka Formulir <ArrowRight class="w-3.5 h-3.5" />
                        </a>
                    </div>
                </div>

                <!-- Track Bar -->
                <div
                    class="mt-5 flex flex-wrap items-center gap-3 bg-[#E4F5EC] rounded-[20px] p-4 sm:p-5"
                >
                    <p
                        class="text-xs font-semibold text-[#085239] flex-1 min-w-[240px]"
                    >
                        📍 Sudah punya nomor permohonan/keberatan? Lacak
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
                            class="shrink-0 bg-[#0B6E4F] hover:bg-[#085239] text-white font-bold text-xs px-5 py-2 rounded-full transition-transform hover:-translate-y-0.5"
                        >
                            Lacak
                        </button>
                    </form>
                </div>
            </section>

            <!-- ============ KATEGORI INFORMASI ============ -->
            <section id="kategori" class="py-12 max-w-[1180px] mx-auto px-5">
                <div class="mb-8 max-w-xl">
                    <p
                        class="text-[#0B6E4F] font-bold text-xs uppercase tracking-wider mb-1"
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
                            href="#"
                            class="font-bold text-xs text-[#085239] inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform"
                        >
                            Lihat Info <ArrowRight class="w-3.5 h-3.5" />
                        </a>
                    </div>
                </div>
            </section>

            <!-- ============ MANDIRI BANNER ============ -->
            <section class="py-12 max-w-[1180px] mx-auto px-5">
                <div
                    class="bg-gradient-to-br from-[#E4F5EC] via-white to-white border border-[#E6EFE9] rounded-[28px] p-7 sm:p-10 relative overflow-hidden"
                >
                    <div
                        class="absolute w-[220px] h-[220px] rounded-full bg-[#FFD3A6] opacity-40 -right-16 -bottom-20 pointer-events-none"
                    ></div>

                    <div class="relative z-10 max-w-2xl">
                        <h2
                            class="text-xl sm:text-2xl font-bold font-serif mb-3 flex items-center gap-2"
                        >
                            Semua layanan PPID, dalam satu genggaman
                            <Smartphone class="w-6 h-6 text-[#0B6E4F]" />
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

                        <div class="flex flex-wrap items-center gap-3">
                            <a
                                href="#"
                                class="inline-flex items-center justify-center font-bold text-xs px-5 py-2.5 rounded-full bg-[#0B6E4F] hover:bg-[#085239] text-white transition-all hover:-translate-y-0.5 shadow-md shadow-[#0B6E4F]/20 whitespace-nowrap"
                            >
                                Buat Permohonan →
                            </a>
                            <a
                                href="#"
                                class="inline-flex items-center justify-center font-bold text-xs px-5 py-2.5 rounded-full bg-white text-[#085239] border border-[#CDEEDD] hover:bg-[#E4F5EC] transition-all hover:-translate-y-0.5 whitespace-nowrap"
                            >
                                Ajukan Keberatan
                            </a>
                            <a
                                href="#"
                                class="inline-flex items-center justify-center font-bold text-xs px-5 py-2.5 rounded-full bg-[#E4F5EC] text-[#085239] hover:bg-[#CDEEDD] transition-all hover:-translate-y-0.5 whitespace-nowrap"
                            >
                                Lacak Status
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============ BERITA ============ -->
            <section id="berita" class="py-12 max-w-[1180px] mx-auto px-5">
                <div class="mb-8 flex items-end justify-between gap-4">
                    <div>
                        <p
                            class="text-[#0B6E4F] font-bold text-xs uppercase tracking-wider mb-1"
                        >
                            KABAR TERKINI
                        </p>
                        <h2 class="text-2xl font-bold font-serif">
                            Berita &amp; Kegiatan Kemenag Gresik
                        </h2>
                    </div>
                    <a
                        href="#"
                        class="font-bold text-xs text-[#085239] whitespace-nowrap inline-flex items-center gap-1 hover:underline"
                    >
                        Lihat Semua <ArrowRight class="w-3.5 h-3.5" />
                    </a>
                </div>

                <div class="flex gap-4 overflow-x-auto pb-4 snap-x">
                    <div
                        v-for="(news, idx) in newsList"
                        :key="idx"
                        class="snap-start shrink-0 w-[270px] bg-white border border-[#E6EFE9] rounded-[20px] overflow-hidden transition-transform duration-200 hover:-translate-y-1"
                    >
                        <div
                            :class="[
                                'h-[130px] bg-gradient-to-br',
                                news.gradient,
                            ]"
                        ></div>
                        <div class="p-4">
                            <span
                                class="inline-block text-[0.68rem] font-bold text-[#085239] bg-[#E4F5EC] px-2.5 py-1 rounded-full mb-2"
                            >
                                {{ news.tag }}
                            </span>
                            <h3
                                class="text-sm font-bold leading-snug mb-2 font-serif line-clamp-2"
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
                                {{ news.date }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- ============ FOOTER ============ -->
        <div class="max-w-[1180px] mx-auto px-5">
            <footer
                class="bg-[#085239] text-white rounded-t-[28px] p-8 sm:p-12 pb-7 mt-5"
            >
                <div
                    class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-[2fr_1fr_1fr] gap-8"
                >
                    <div>
                        <div class="flex items-center gap-2.5 mb-3.5">
                            <span
                                class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center font-bold text-sm"
                                >KG</span
                            >
                            <span class="font-bold text-base text-white"
                                >PPID Kemenag Gresik</span
                            >
                        </div>
                        <p
                            class="text-xs leading-relaxed text-white/75 max-w-sm"
                        >
                            Menyediakan akses informasi publik yang transparan,
                            akurat, dan ramah untuk semua kalangan masyarakat
                            Gresik.
                        </p>
                    </div>

                    <div>
                        <h4 class="text-sm font-bold mb-3.5">Kontak</h4>
                        <p class="text-xs leading-relaxed text-white/75 mb-2">
                            Jl. Dr. Wahidin Sudirohusodo No. 1<br />Gresik, Jawa
                            Timur 61111
                        </p>
                        <p class="text-xs leading-relaxed text-white/75">
                            (031) 398-xxxx<br />ppid.gresik@kemenag.go.id
                        </p>
                    </div>

                    <div>
                        <h4 class="text-sm font-bold mb-3.5">Ikuti Kami</h4>
                        <p class="text-xs leading-relaxed text-white/75 mb-3">
                            Update layanan &amp; kegiatan terbaru dari PPID
                            Kemenag Gresik.
                        </p>
                        <div class="flex gap-2">
                            <a
                                href="#"
                                class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors"
                                aria-label="Instagram"
                            >
                                <Instagram class="w-4 h-4" />
                            </a>
                            <a
                                href="#"
                                class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors"
                                aria-label="YouTube"
                            >
                                <Youtube class="w-4 h-4" />
                            </a>
                            <a
                                href="#"
                                class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors"
                                aria-label="Facebook"
                            >
                                <Facebook class="w-4 h-4" />
                            </a>
                            <a
                                href="#"
                                class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors"
                                aria-label="Twitter"
                            >
                                <Twitter class="w-4 h-4" />
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    class="border-t border-white/15 mt-8 pt-5 text-xs text-white/55 flex flex-wrap justify-between gap-2"
                >
                    <span>© 2026 PPID Kementerian Agama Kabupaten Gresik.</span>
                    <span>Dibuat ramah, dibuat terbuka 🌿</span>
                </div>
            </footer>
        </div>
    </div>
</template>
