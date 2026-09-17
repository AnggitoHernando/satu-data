<script setup>
import { Link } from "@inertiajs/vue3";
import dropdownHover from "@/Components/DropdownHover.vue";

defineProps({
    isContrastMode: {
        type: Boolean,
        default: false,
    },
});

const tentangkami = [
    { name: "Profil Kantor", link: "#" },
    { name: "Profil PPID", link: "#" },
];

const informasiPublik = [
    { name: "Informasi Berkala", link: "#" },
    { name: "Informasi Serta-Merta", link: "#" },
    { name: "Informasi Setiap Saat", link: "#" },
    { name: "Informasi Dikecualikan", link: "#" },
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
                >
                    <a
                        href="#tentang"
                        :class="
                            isContrastMode
                                ? 'hover:bg-zinc-800'
                                : 'hover:bg-[#E4F5EC] hover:text-[#085239]'
                        "
                        class="px-3.5 py-1.5 rounded-full transition-colors"
                        >Beranda</a
                    >
                    <dropdownHover :items="tentangkami" label="Tentang Kami" />
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
                        class="px-3.5 py-1.5 rounded-full transition-colors"
                        >Berita</a
                    >
                </nav>

                <a
                    href="#layanan"
                    class="inline-flex items-center gap-1 font-bold text-xs px-4 py-2.5 rounded-full bg-green-800 hover:bg-[#085239] text-white shadow-md shadow-[#0B6E4F]/25 transition-transform hover:-translate-y-0.5 whitespace-nowrap"
                >
                    Layanan E-form ✦
                </a>
            </div>
        </header>
    </div>
</template>
