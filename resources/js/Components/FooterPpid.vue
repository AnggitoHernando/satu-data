<script setup>
import {
    Instagram,
    Youtube,
    Facebook,
    Twitter,
    MapPin,
    PhoneCall,
    Mail,
} from "lucide-vue-next";
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import PpidLogo from "@/Components/PpidLogo.vue";
const isContrastMode = ref(false);
import SpanLaporLogo from "@/Components/SpanLaporLogo.vue";
</script>

<template>
    <div class="max-w-full px-5">
        <footer
            class="bg-green-600 text-white rounded-t-[28px] p-8 sm:p-12 pb-7 mt-5"
        >
            <div
                class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-[1fr_1fr_1fr] gap-8"
            >
                <div>
                    <Link
                        :href="route('Beranda')"
                        class="flex items-center gap-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-[#0B6E4F] focus:ring-offset-2"
                        aria-label="PPID Kantor Kementerian Agama Kabupaten Gresik - Kembali ke Beranda"
                    >
                        <PpidLogo
                            aria-hidden="true"
                            class="bg-white rounded-full"
                        />
                        <span class="flex flex-col">
                            <span class="font-bold text-sm leading-tight"
                                >Pejabat Pengelola Informasi dan
                                Dokumentasi</span
                            >
                            <span
                                :class="
                                    isContrastMode
                                        ? 'text-yellow-200'
                                        : 'text-yellow-400'
                                "
                                class="text-[0.68rem]"
                                >Kantor Kementerian Agama Kabupaten Gresik</span
                            >
                        </span>
                    </Link>
                    <p
                        class="mt-4 text-xs leading-relaxed text-white/75 max-w-sm"
                    >
                        Menyediakan akses informasi publik yang transparan,
                        akurat, dan dapat dipertanggung untuk semua kalangan
                        masyarakat Gresik. Dengan keterlibatan, memperoleh dan
                        tata kelola pemerintahan yang baik.
                    </p>
                </div>

                <div>
                    <div class="grid grid-cols-1 gap-3.5">
                        <h4 class="text-sm font-bold mb-3.5">
                            Informasi Kontak
                        </h4>
                        <div class="flex gap-2">
                            <MapPin class="w-5 h-5 text-white" />
                            <span
                                class="text-xs leading-relaxed text-white/75 mb-2"
                            >
                                Jl. Jaksa Agung Suprapto No.39, Sumursango,
                                Bedilan, Kecamatan Gresik, Kabupaten Gresik,
                                Jawa Timur 61111
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <PhoneCall class="w-5 h-5 text-white" />
                            <span class="text-xs leading-relaxed text-white/75">
                                085111344066
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <Mail class="w-5 h-5 text-white" />
                            <span class="text-xs leading-relaxed text-white/75">
                                ppidkemenaggresik@gmail.com
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-bold mb-3.5">Ikuti Kami</h4>
                    <p class="text-xs leading-relaxed text-white/75 mb-3">
                        Update layanan &amp; kegiatan terbaru dari PPID Kemenag
                        Gresik.
                    </p>
                    <div class="flex gap-2">
                        <a
                            href="https://www.instagram.com/kemenaggresik_/"
                            target="_blank"
                            class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors"
                            aria-label="Instagram"
                        >
                            <Instagram class="w-4 h-4" />
                        </a>
                        <a
                            href="http://www.youtube.com/@kemenaggresik"
                            target="_blank"
                            class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors"
                            aria-label="YouTube"
                        >
                            <Youtube class="w-4 h-4" />
                        </a>
                        <a
                            href="https://www.facebook.com/kemenaggresikofficiall"
                            target="_blank"
                            class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors"
                            aria-label="Facebook"
                        >
                            <Facebook class="w-4 h-4" />
                        </a>
                        <a
                            href="https://x.com/kemenaggresik_"
                            target="_blank"
                            class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors"
                            aria-label="Twitter"
                        >
                            <Twitter class="w-4 h-4" />
                        </a>
                        <a
                            href="https://www.lapor.go.id/"
                            target="_blank"
                            class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors"
                            aria-label="Lapor"
                        >
                            <SpanLaporLogo />
                        </a>
                    </div>
                </div>
            </div>

            <div
                class="border-t border-white/15 mt-8 pt-5 text-xs text-white/55 flex flex-wrap justify-center gap-2"
            >
                <span
                    >© {{ new Date().getFullYear() }} PPID Kementerian Agama
                    Kabupaten Gresik.</span
                >
            </div>
        </footer>
    </div>
</template>
