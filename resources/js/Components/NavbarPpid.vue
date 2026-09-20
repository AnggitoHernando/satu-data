<script setup>
import { Link } from "@inertiajs/vue3";
import dropdownHover from "@/Components/DropdownHover.vue";
import PpidLogo from "@/Components/PpidLogo.vue";

defineProps({
    isContrastMode: {
        type: Boolean,
        default: false,
    },
});

const informasiPublik = [
    { name: "Informasi Berkala", link: "home.ppid.informasi_berkala" },
    { name: "Informasi Serta-Merta", link: "home.ppid.informasiSertaMerta" },
    { name: "Informasi Setiap Saat", link: "home.ppid.informasiSetiapSaat" },
    { name: "Informasi Dikecualikan", link: "home.ppid.informasiDikecualikan" },
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
                                : 'hover:bg-[#E4F5EC] hover:text-[#085239]'
                        "
                        class="px-3.5 py-1.5 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-[#0B6E4F] focus:ring-offset-2"
                        >Beranda</a
                    >
                    <a
                        :href="route('PortalData.search')"
                        :class="
                            isContrastMode
                                ? 'hover:bg-zinc-800'
                                : 'hover:bg-[#E4F5EC] hover:text-[#085239]'
                        "
                        class="px-3.5 py-1.5 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-[#0B6E4F] focus:ring-offset-2"
                        >Mandat</a
                    >
                    <dropdownHover
                        :items="informasiPublik"
                        label="Informasi Publik"
                    />
                    <a
                        href="#berita"
                        :class="
                            isContrastMode
                                ? 'hover:bg-zinc-800'
                                : 'hover:bg-[#E4F5EC] hover:text-[#085239]'
                        "
                        class="px-3.5 py-1.5 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-[#0B6E4F] focus:ring-offset-2"
                        >Berita</a
                    >
                </nav>

                <a
                    href="#layanan"
                    class="inline-flex items-center gap-1 font-bold text-xs px-4 py-2.5 rounded-full bg-green-800 hover:bg-[#085239] text-white shadow-md shadow-[#0B6E4F]/25 transition-transform hover:-translate-y-0.5 whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-[#0B6E4F] focus:ring-offset-2"
                    aria-label="Layanan E-form - Ajukan permohonan informasi publik secara mandiri"
                >
                    Layanan E-form <span aria-hidden="true">✦</span>
                </a>
            </div>
        </header>
    </div>
</template>
