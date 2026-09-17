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
</script>

<template>
    <div class="max-w-full px-5">
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
                        <div class="grid">
                            <span class="font-bold text-base text-white"
                                >PPID
                            </span>
                            <span class="font-bold text-xs text-white"
                                >Kantor Kementerian Agama Kabupaten Gresik
                            </span>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed text-white/75 max-w-sm">
                        Menyediakan akses informasi publik yang transparan,
                        akurat, dan dapat dipertanggung untuk semua kalangan
                        masyarakat Gresik. Dengan keterlibatan, memperoleh dan
                        tata kelola pemerintahan yang baik.
                    </p>
                </div>

                <div>
                    <h4 class="text-sm font-bold mb-3.5">Informasi Kontak</h4>
                    <div class="flex flex-wrap gap-2">
                        <div class="flex gap-2">
                            <MapPin class="w-5 h-5 text-white" />
                            <span
                                class="text-xs leading-relaxed text-white/75 mb-2"
                            >
                                Jl. Dr. Wahidin Sudirohusodo No. 1<br />Gresik,
                                Jawa Timur 61111
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <PhoneCall class="w-5 h-5 text-white" />
                            <span class="text-xs leading-relaxed text-white/75">
                                (031) 398-xxxx
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <Mail class="w-5 h-5 text-white" />
                            <span class="text-xs leading-relaxed text-white/75">
                                ppid.gresik@kemenag.go.id
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
                class="border-t border-white/15 mt-8 pt-5 text-xs text-white/55 flex flex-wrap justify-center gap-2"
            >
                <span>© 2026 PPID Kementerian Agama Kabupaten Gresik.</span>
            </div>
        </footer>
    </div>
</template>
