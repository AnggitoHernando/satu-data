<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import dropdownHover from "@/Components/DropdownHover.vue";
import PpidLogo from "@/Components/PpidLogo.vue";
import ModalHeadnessUI from "@/Components/ModalHeadnessUI.vue";
import { FileText } from "lucide-vue-next";

defineProps({
    isContrastMode: {
        type: Boolean,
        default: false,
    },
});

const isOpen = ref(false);

const informasiPublik = [
    { name: "Informasi Berkala", link: "home.ppid.informasi_berkala" },
    { name: "Informasi Serta-Merta", link: "home.ppid.informasiSertaMerta" },
    { name: "Informasi Setiap Saat", link: "home.ppid.informasiSetiapSaat" },
    { name: "Informasi Dikecualikan", link: "home.ppid.informasiDikecualikan" },
];

const layanan = [
    { name: "Manajemen Data Terpadu", link: "PortalData" },
    { name: "Layanan Informasi", link: "PortalData.search" },
];
</script>

<template>
    <div class="sticky top-3 z-40 px-4">
        <header
            :class="
                isContrastMode
                    ? 'bg-black border-yellow-300'
                    : 'bg-white/85 backdrop-blur-md border-[#E6EFE9]'
            "
            class="max-w-[1180px] mx-auto border rounded-full shadow-lg shadow-[#0B6E4F]/10"
            role="banner"
        >
            <div class="flex items-center justify-between gap-3 px-4 py-2">
                <Link
                    :href="route('Beranda')"
                    class="flex items-center gap-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-[#0B6E4F] focus:ring-offset-2"
                    aria-label="PPID Kantor Kementerian Agama Kabupaten Gresik - Kembali ke Beranda"
                >
                    <PpidLogo aria-hidden="true" />
                    <span class="flex flex-col">
                        <span class="font-bold text-sm leading-tight"
                            >Pejabat Pengelola Informasi dan Dokumentasi</span
                        >
                        <span
                            :class="
                                isContrastMode
                                    ? 'text-yellow-200'
                                    : 'text-[#6b7a72]'
                            "
                            class="text-[0.68rem]"
                            >Kantor Kementerian Agama Kabupaten Gresik</span
                        >
                    </span>
                </Link>

                <nav
                    class="hidden md:flex items-center gap-1 text-sm font-semibold"
                    aria-label="Navigasi Utama"
                >
                    <a
                        :href="route('Beranda')"
                        :class="
                            isContrastMode
                                ? 'hover:bg-zinc-800'
                                : 'hover:bg-green-100 hover:bg-green-600'
                        "
                        class="px-3.5 py-1.5 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-[#0B6E4F] focus:ring-offset-2"
                        >Beranda</a
                    >
                    <dropdownHover :items="layanan" label="Layanan" />
                    <dropdownHover
                        :items="informasiPublik"
                        label="Informasi Publik"
                    />
                    <a
                        href="#berita"
                        :class="
                            isContrastMode
                                ? 'hover:bg-zinc-800'
                                : 'hover:bg-green-100 hover:bg-green-600'
                        "
                        class="px-3.5 py-1.5 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-[#0B6E4F] focus:ring-offset-2"
                        >Profil PPID</a
                    >
                </nav>

                <button
                    @click="isOpen = true"
                    class="inline-flex items-center gap-1 font-bold text-xs px-4 py-2.5 rounded-full bg-green-600 hover:bg-green-700 text-white shadow-md shadow-green-600/25 transition-transform hover:-translate-y-0.5 whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
                    aria-label="Layanan E-form - Ajukan permohonan informasi publik secara mandiri"
                >
                    Layanan E-form <span aria-hidden="true">✦</span>
                </button>
            </div>
        </header>
    </div>
    <ModalHeadnessUI :open-modal="isOpen" @close="isOpen = false">
        <div class="relative z-10">
            <div class="text-center mb-8 sm:mb-10">
                <h2
                    class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight font-sans"
                >
                    Pilih Layanan E-Form
                </h2>
                <p class="text-slate-500 text-sm sm:text-base mt-2 font-normal">
                    Silakan pilih jenis pengajuan layanan publik yang Anda
                    butuhkan:
                </p>
            </div>
            <Link
                as="div"
                class="group bg-[#f8fbf9] hover:bg-white border border-slate-200/80 hover:border-emerald-400 rounded-2xl p-5 sm:p-6 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-900/5 mb-4 sm:mb-5 cursor-pointer relative"
                :href="route('home.ppid.permohonan_informasi')"
            >
                <div class="flex items-start gap-4 sm:gap-5">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-[#10b981] text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-600/20 group-hover:scale-105 transition-transform duration-300"
                    >
                        <FileText class="w-6 h-6" />
                    </div>

                    <div class="flex-1 min-w-0">
                        <div
                            class="flex items-center justify-between gap-2 flex-wrap sm:flex-nowrap"
                        >
                            <h3
                                class="text-base sm:text-xl font-bold text-slate-900 group-hover:text-emerald-700 transition-colors"
                            >
                                Permohonan Informasi Publik
                            </h3>
                            <button
                                type="button"
                                aria-label="Buka Form Permohonan Informasi Publik"
                                class="inline-flex items-center gap-1 text-sm sm:text-base font-bold text-[#10b981] hover:text-emerald-700 group-hover:translate-x-0.5 transition-all whitespace-nowrap ml-auto"
                            >
                                <span>Buka Form</span>
                                <span aria-hidden="true">→</span>
                            </button>
                        </div>

                        <p
                            class="text-slate-500 text-xs sm:text-sm mt-1 sm:mt-1.5 leading-relaxed"
                        >
                            Formulir permohonan akses dokumen & informasi publik
                            secara mandiri & online.
                        </p>
                    </div>
                </div>
            </Link>

            <Link
                as="div"
                class="group bg-[#fdfaf6] hover:bg-white border border-slate-200/80 hover:border-amber-400 rounded-2xl p-5 sm:p-6 transition-all duration-300 hover:shadow-lg hover:shadow-amber-900/5 cursor-pointer relative"
                :href="route('home.ppid.permohonan_keberatan')"
            >
                <div class="flex items-start gap-4 sm:gap-5">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-[#d97706] text-white flex items-center justify-center shrink-0 shadow-md shadow-amber-600/20 group-hover:scale-105 transition-transform duration-300"
                    >
                        <FileText class="w-6 h-6" />
                    </div>

                    <div class="flex-1 min-w-0">
                        <div
                            class="flex items-center justify-between gap-2 flex-wrap sm:flex-nowrap"
                        >
                            <h3
                                class="text-base sm:text-xl font-bold text-slate-900 group-hover:text-amber-700 transition-colors"
                            >
                                Pengajuan Keberatan Informasi
                            </h3>
                            <button
                                type="button"
                                aria-label="Buka Form Pengajuan Keberatan Informasi"
                                class="inline-flex items-center gap-1 text-sm sm:text-base font-bold text-[#d97706] hover:text-amber-700 group-hover:translate-x-0.5 transition-all whitespace-nowrap ml-auto"
                            >
                                <span>Buka Form</span>
                                <span aria-hidden="true">→</span>
                            </button>
                        </div>

                        <p
                            class="text-slate-500 text-xs sm:text-sm mt-1 sm:mt-1.5 leading-relaxed"
                        >
                            Formulir pengajuan keberatan resmi atas permohonan
                            informasi publik.
                        </p>
                    </div>
                </div>
            </Link>

            <Link
                as="div"
                :href="route('home.ppid.lacak_permohonan_informasi')"
                class="flex items-center justify-center text-center mb-4 mt-4"
            >
                <button
                    type="button"
                    class="inline-flex items-center justify-center gap-2 text-xs sm:text-sm font-semibold text-emerald-700 hover:text-emerald-800 transition-all hover:underline group flex-wrap"
                >
                    <span>Sudah punya nomor permohonan / keberatan?</span>
                    <span
                        class="font-bold underline decoration-emerald-500 decoration-2 underline-offset-2 flex items-center gap-1"
                    >
                        Lacak Progres Status
                        <span
                            class="group-hover:translate-x-1 transition-transform inline-block"
                            aria-hidden="true"
                            >→</span
                        >
                    </span>
                </button>
            </Link>
        </div>
    </ModalHeadnessUI>
</template>
