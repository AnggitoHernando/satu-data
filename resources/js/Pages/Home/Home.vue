<script setup>
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { Search } from "lucide-vue-next";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import subagtu from "@assets/icon/subbag1.svg";
import haji from "@assets/icon/haji1.svg";
import bimas from "@assets/icon/bimas.svg";
import pais from "@assets/icon/pais.svg";
import pdpontren from "@assets/icon/pdpontren.svg";
import pendma from "@assets/icon/pendma1.svg";
import zawa from "@assets/icon/zawa.svg";
import { ref } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const list_seksi = [
    { nama: "SUBBAG TU", desc: "Sub Bagian Tata Usaha", img: subagtu },
    { nama: "PENDMA", desc: "Pendidikan Madrasah", img: pendma },
    { nama: "PHU", desc: "Penyelenggara Haji dan Umroh", img: haji },
    { nama: "BIMAS", desc: "Bimbingan Masyarakat Islam", img: bimas },
    { nama: "PAIS", desc: "Pendidikan Agama Islam", img: pais },
    {
        nama: "PD-PONTREN",
        desc: "Pendidikan Diniyah dan Pondok Pesantren",
        img: pdpontren,
    },
    { nama: "PENZAWA", desc: "Penyelenggara Zakat dan Wakaf", img: zawa },
];

const stats = [
    { label: "Data", value: 238 },
    { label: "Seksi", value: 7 },
    { label: "Publikasi Dokumen", value: 5 },
];

const search = ref("");
function handleSearch() {
    if (search.value.trim() !== "") {
        router.visit(route("PortalData.search"), {
            data: { q: search.value },
        });
    }
}
</script>
<template>
    <Head title="Home" />
    <HomeLayout>
        <div class="min-h-screen">
            <!-- Hero / Banner -->
            <section
                class="max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-16 text-center"
            >
                <h1
                    class="text-3xl sm:text-5xl font-extrabold text-gray-800 leading-tight"
                >
                    <span class="text-green-800 block">MANDAT GRESIK</span>
                    <span class="block"
                        >Manajemen Data Terpadu Kemenag Gresik</span
                    >
                </h1>

                <p
                    class="mt-3 sm:mt-4 text-gray-600 text-base sm:text-lg max-w-2xl mx-auto px-2"
                >
                    "Satu Portal Data Terpadu Kementerian Agama Kabupaten
                    Gresik."
                </p>

                <div class="mt-6 sm:mt-8">
                    <div class="relative max-w-md mx-auto">
                        <Search
                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"
                        />
                        <input
                            type="text"
                            v-model="search"
                            placeholder="Cari data..."
                            @keyup.enter="handleSearch"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-700 focus:border-green-700 focus:outline-none text-sm sm:text-base"
                        />
                        <PrimaryButton
                            @click="handleSearch"
                            class="absolute right-0 top-1/2 -translate-y-1/2"
                        >
                            Cari
                        </PrimaryButton>
                    </div>
                </div>
            </section>

            <!-- Swiper Section -->
            <section class="max-w-7xl mx-auto text-center px-4">
                <Swiper
                    :modules="[Navigation, Pagination]"
                    :slidesPerView="4"
                    :breakpoints="{
                        640: { slidesPerView: 2 },
                        1024: { slidesPerView: 4 },
                    }"
                    :spaceBetween="20"
                    pagination
                    navigation
                    class="w-full h-64 sm:h-64 md:h-72 rounded-xl overflow-hidden"
                >
                    <swiper-slide
                        class="bg-gray-200 rounded-xl"
                        v-for="(seksi, index) in list_seksi"
                        :key="index"
                    >
                        <div
                            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 cursor-pointer flex flex-col items-center justify-between h-[15rem] sm:h-[15.5rem]"
                        >
                            <div
                                class="h-1/2 flex mt-2 items-center justify-center w-full bg-gray-50"
                            >
                                <img
                                    :src="seksi.img"
                                    :alt="seksi.nama"
                                    class="object-contain w-2/3 sm:w-1/2 h-full"
                                />
                            </div>
                            <div
                                class="p-3 sm:p-4 text-center h-1/2 flex flex-col justify-center"
                            >
                                <h3
                                    class="text-base sm:text-lg font-semibold text-gray-800"
                                >
                                    {{ seksi.nama }}
                                </h3>
                                <p
                                    class="text-gray-600 text-xs sm:text-sm mt-1"
                                >
                                    {{ seksi.desc }}
                                </p>
                            </div>
                        </div>
                    </swiper-slide>
                </Swiper>
            </section>

            <!-- Statistik -->
            <section
                class="bg-gradient-to-b from-gray-50 to-white py-10 sm:py-12"
            >
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div
                        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 text-center gap-6 sm:gap-8"
                    >
                        <div
                            v-for="(item, index) in stats"
                            :key="index"
                            class="flex flex-col items-center"
                        >
                            <h3
                                class="text-3xl sm:text-5xl font-bold text-gray-700"
                            >
                                {{ item.value }}
                            </h3>
                            <p
                                class="mt-1 sm:mt-2 text-xs sm:text-sm font-semibold tracking-widest text-gray-500 uppercase"
                            >
                                {{ item.label }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </HomeLayout>
</template>
