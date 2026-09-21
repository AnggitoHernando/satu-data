<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import PpidLayout from "@/Layouts/PpidLayout.vue";
import BannerCard from "@/Components/BannerCard.vue";
import { ShieldCheck, ChevronRight, ChevronDown } from "lucide-vue-next";
import YearAccordion from "@/Components/YearAccordion.vue";
import DropdownHover from "@/Components/DropdownHover.vue";

const open = ref(true);

const props = defineProps({
    lampiran: { type: Array, required: true },
});
const query = ref("");
const activeYear = ref("all");

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
                    {{ usePage().props.menu.parent.nama_menu }}
                </span>

                <h1
                    class="text-3xl text-yellow-400 sm:text-4xl md:text-5xl font-bold font-serif tracking-tight leading-tight"
                >
                    {{ usePage().props.menu.nama_menu }}
                </h1>
            </template>

            <template #card-body>
                <div
                    class="mx-auto grid max-w-6xl grid-cols-1 gap-7 px-6 py-8 sm:px-10 md:grid-cols-[240px_1fr]"
                >
                    <nav
                        aria-label="Navigasi laporan"
                        class="flex gap-2 overflow-x-auto md:sticky md:top-5 md:h-fit md:flex-col md:overflow-visible"
                    >
                        <div
                            v-for="menu in usePage().props.listMenu"
                            :key="menu.id"
                        >
                            <a
                                v-if="menu.children_recursive.length === 0"
                                :href="`/${menu.full_path}`"
                                :class="
                                    usePage().props.selectedMenu === menu.slug
                                        ? 'flex items-center  gap-2 bg-green-600 text-white shadow-md shadow-emerald-900/20'
                                        : 'text-stone-500 hover:bg-stone-100 hover:text-stone-900 transition'
                                "
                                aria-current="page"
                                class="flex items-center justify-between gap-2 whitespace-nowrap rounded-xl px-4 py-3 text-sm font-semibold focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600"
                            >
                                {{ menu.nama_menu }}
                                <ChevronRight
                                    aria-hidden="true"
                                    class="h-4 w-4"
                                />
                            </a>
                            <div v-else class="relative w-full">
                                <button
                                    :class="
                                        usePage().props.selectedMenu ===
                                        menu.slug
                                            ? 'flex items-center  gap-2 bg-emerald-700 text-white shadow-md shadow-emerald-900/20'
                                            : 'text-stone-500 hover:bg-stone-100 hover:text-stone-900 transition'
                                    "
                                    class="w-full flex items-center justify-between gap-2 whitespace-nowrap rounded-xl px-4 py-3 text-sm font-semibold focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600"
                                    @click="open = !open"
                                >
                                    {{ menu.nama_menu }}
                                    <ChevronDown
                                        v-if="open"
                                        aria-hidden="true"
                                        class="h-4 w-4"
                                    />
                                    <ChevronRight
                                        v-else
                                        aria-hidden="true"
                                        class="h-4 w-4"
                                    />
                                </button>
                                <div v-if="open">
                                    <a
                                        v-for="child in menu.children_recursive"
                                        :key="child.id"
                                        :href="`/${child.full_path}`"
                                        :class="
                                            usePage().props.selectedMenu ===
                                            child.slug
                                                ? 'flex items-center  gap-2 bg-emerald-700 text-white shadow-md shadow-emerald-900/20'
                                                : 'text-stone-500 hover:bg-stone-100 hover:text-stone-900 transition'
                                        "
                                        aria-current="page"
                                        class="ml-6 flex items-center justify-between gap-2 whitespace-nowrap rounded-xl px-4 py-3 text-sm font-semibold focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600"
                                    >
                                        {{ child.nama_menu }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </nav>
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
                                class="mb-6 h-64 w-full rounded-lg object-cover sm:h-80"
                            />

                            <div
                                class="rich-text-content"
                                v-html="
                                    usePage().props.halamanStatis.isi_konten
                                "
                            ></div>
                        </div>
                    </main>
                </div>
            </template>
        </BannerCard>
    </PpidLayout>
</template>
