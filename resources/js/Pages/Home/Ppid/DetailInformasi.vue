<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import PpidLayout from "@/Layouts/PpidLayout.vue";
import BannerCard from "@/Components/BannerCard.vue";
import { ShieldCheck, ChevronRight, ChevronDown } from "lucide-vue-next";
import YearAccordion from "@/Components/YearAccordion.vue";
import DropdownHover from "@/Components/DropdownHover.vue";
import PhotoGalerySlider from "@/Components/PhotoGalerySlider.vue";
import MenuNav from "@/Components/MenuNav.vue";
import { ArrowRight } from "lucide-vue-next";

const open = ref(true);
const page = usePage();

const props = defineProps({
    lampiran: { type: Array, required: true },
});
const query = ref("");
const activeYear = ref("all");

const listMenu = computed(() => page.props.listMenu || []);
const hasMenu = computed(() => listMenu.value.length > 0);
const selectedMenu = computed(() => page.props.selectedMenu);
const halamanStatis = computed(() => page.props.halamanStatis);
const menu = computed(() => page.props.menu);

const availableYears = computed(() => props.lampiran.map((y) => y.year));

// Filter by year chip, then by search text across document names/tags.
const filteredYears = computed(() => {
    return props.lampiran
        .filter(
            (group) =>
                activeYear.value === "all" || group.year === activeYear.value,
        )
        .map((group) => {
            const q = query.value.trim().toLowerCase();
            if (!q) return group;

            const documents = group.documents.filter((doc) =>
                `${doc.name} ${doc.tag ?? ""}`.toLowerCase().includes(q),
            );
            return { ...group, documents };
        })
        .filter((group) => group.documents.length > 0);
});
</script>

<template>
    <Head :title="`PPID Kemenag Gresik - ${usePage().props.menu.nama_menu}`">
        <meta name="description" content="Informasi Publik" />
    </Head>
    <PpidLayout>
        <BannerCard>
            <template #banner-content>
                <span
                    class="inline-flex items-center gap-1.5 bg-gradient-to-b from-[#EBF7F1] via-[#F4FAF6] to-slate-50 text-xs font-bold px-4 py-1.5 rounded-full mb-4 border border-[#0B6E4F]/20"
                >
                    <ShieldCheck class="w-4 h-4" />
                    {{
                        usePage().props.menu?.parent?.nama_menu ||
                        usePage().props.menu.nama_menu
                    }}
                </span>

                <h1
                    class="text-3xl text-yellow-400 sm:text-4xl md:text-5xl font-bold font-serif tracking-tight leading-tight"
                >
                    {{ usePage().props.menu.nama_menu }}
                </h1>
            </template>

            <template #card-body>
                <div
                    class="mx-auto grid max-w-6xl gap-7 px-6 py-8 sm:px-10"
                    :class="[
                        hasMenu
                            ? 'grid-cols-1 md:grid-cols-[240px_1fr]'
                            : 'grid-cols-1',
                    ]"
                    v-if="
                        hasMenu ||
                        halamanStatis !== null ||
                        filteredYears.length > 0
                    "
                >
                    <MenuNav
                        :items="usePage().props.listMenu"
                        :selected="usePage().props.selectedMenu"
                    />
                    <main
                        id="konten-utama"
                        tabindex="-1"
                        class="focus:outline-none"
                    >
                        <YearAccordion
                            v-for="group in filteredYears"
                            :key="group.year"
                            :year="group.year"
                            :documents="group.documents"
                            :menu="usePage().props.menu"
                        />

                        <p
                            v-if="
                                filteredYears.length === 0 &&
                                usePage().props.halamanStatis === null
                            "
                            class="py-10 text-center text-sm text-stone-500"
                        >
                            Tidak ada dokumen yang cocok dengan pencarian.
                        </p>

                        <section
                            v-if="usePage().props?.beritaTerkini?.length > 0"
                            id="berita"
                            class="py-12 max-w-[1180px] mx-auto px-5"
                        >
                            <div
                                class="mb-8 flex items-end justify-between gap-4"
                            >
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
                                    Lihat Semua
                                    <ArrowRight class="w-3.5 h-3.5" />
                                </a>
                            </div>

                            <div class="flex gap-4 overflow-x-auto pb-4 snap-x">
                                <a
                                    v-for="(news, idx) in usePage().props
                                        .beritaTerkini"
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
                                            class="text-[#6b7a72] text-[0.72rem]"
                                        >
                                            {{ news.created }}
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </section>

                        <div
                            v-if="
                                filteredYears.length === 0 &&
                                usePage().props.halamanStatis !== null
                            "
                        >
                            <img
                                v-if="
                                    usePage().props.halamanStatis.gambar_utama
                                "
                                :src="`/storage/${usePage().props.halamanStatis.gambar_utama}`"
                                :alt="usePage().props.halamanStatis.judul"
                                class="mb-8 h-full w-full rounded-lg"
                            />

                            <div
                                class="rich-text-content prose max-w-none"
                                v-html="
                                    usePage().props.halamanStatis.isi_konten
                                "
                            ></div>

                            <PhotoGalerySlider
                                v-if="
                                    usePage().props.halamanStatis.galeri
                                        .length > 0
                                "
                                class="mt-4"
                                :photos="
                                    usePage().props.halamanStatis.galeri ?? []
                                "
                            />
                        </div>
                    </main>
                </div>

                <div
                    v-else
                    class="flex flex-col items-center justify-center gap-4 py-10 text-center text-sm text-stone-500"
                >
                    <p>Tidak ada menu yang tersedia untuk kategori ini.</p>
                </div>
            </template>
        </BannerCard>
    </PpidLayout>
</template>
