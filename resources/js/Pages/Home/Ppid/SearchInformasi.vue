<script setup>
import { computed, ref, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import {
    ArrowRight,
    ChevronLeft,
    ChevronRight,
    FilePlus2,
    Search,
    SearchX,
} from "lucide-vue-next";
import PpidLayout from "@/Layouts/PpidLayout.vue";
import InformasiCard from "@/Components/InformasiCard.vue";
import SelectButton from "@/Components/SelectButton.vue";

const props = defineProps({
    informasi: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

const PERMOHONAN_URL = "/permohonan-informasi";
const POPULER = [
    "laporan keuangan",
    "SOP layanan",
    "daftar informasi publik",
    "regulasi",
];

const q = ref(props.filters.q ?? "");
const sort = ref(props.filters.sort ?? "relevan");
const loading = ref(false);

function visit() {
    router.get(
        route("ppid.search"),
        {
            q: q.value || undefined,
            sort: sort.value !== "relevan" ? sort.value : undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ["informasi", "filters"],
            onStart: () => (loading.value = true),
            onFinish: () => (loading.value = false),
        },
    );
}

let timer;
watch(q, () => {
    clearTimeout(timer);
    timer = setTimeout(visit, 400);
});
watch(sort, visit);

function pilih(kata) {
    q.value = kata;
}

const total = computed(() => props.informasi.total ?? 0);
const halaman = computed(() => props.informasi.links.slice(1, -1));
const prev = computed(() => props.informasi.links[0]);
const next = computed(
    () => props.informasi.links[props.informasi.links.length - 1],
);
const adaHasil = computed(() => props.informasi.data.length > 0);
</script>

<template>
    <Head title="Pencarian Informasi Publik" />

    <PpidLayout>
        <section class="relative overflow-hidden">
            <div
                class="pointer-events-none absolute -right-20 -top-32 h-[340px] w-[340px] rounded-full bg-[#B8E986] opacity-55 blur-[2px]"
                aria-hidden="true"
            ></div>
            <div
                class="pointer-events-none absolute -bottom-16 -left-16 h-[220px] w-[220px] rounded-full bg-[#BFE3F2] opacity-45 blur-[2px]"
                aria-hidden="true"
            ></div>

            <div
                class="relative mx-auto max-w-4xl px-4 pb-20 pt-40 sm:px-6 lg:pt-44"
            >
                <div class="text-center">
                    <span
                        class="inline-flex mt-6 rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-900"
                        >Pencarian informasi publik</span
                    >
                    <h1
                        class="mt-5 font-serif text-3xl font-bold leading-tight text-green-700 sm:text-5xl"
                    >
                        Cari dokumen dan informasi publik
                    </h1>
                    <p
                        class="mx-auto mt-4 max-w-xl text-base leading-relaxed text-slate-600"
                    >
                        Temukan laporan, regulasi, dan dokumen resmi Kantor
                        Kementerian Agama Kabupaten Gresik secara daring,
                        transparan, dan akuntabel.
                    </p>

                    <form
                        class="mx-auto mt-8 flex max-w-2xl items-center text-left gap-3 rounded-full border border-green-200 bg-white py-2 pl-5 pr-2 shadow-lg shadow-green-900/10 focus-within:ring-2 focus-within:ring-green-700"
                        role="search"
                        @submit.prevent="visit"
                    >
                        <Search
                            class="h-5 w-5 ml-4 shrink-0 text-slate-500"
                            aria-hidden="true"
                        />
                        <label for="q" class="sr-only"
                            >Kata kunci pencarian</label
                        >
                        <input
                            id="q"
                            v-model="q"
                            type="search"
                            autocomplete="off"
                            placeholder="Cari, mis. laporan anggaran 2025…"
                            class="min-w-0 flex-1 border-0 bg-transparent py-3 text-base text-slate-900 placeholder:text-slate-500 focus:outline-none focus:ring-0"
                        />
                        <button
                            type="submit"
                            class="h-12 rounded-full bg-green-600 px-7 text-sm font-bold text-white transition hover:bg-green-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2"
                        >
                            Cari
                        </button>
                    </form>
                </div>

                <section class="mt-12" aria-live="polite">
                    <div
                        class="mb-5 flex flex-wrap items-center justify-between gap-3"
                    >
                        <p class="text-sm text-slate-600">
                            <template v-if="filters.q">
                                {{ total }} hasil untuk
                                <strong class="text-slate-900"
                                    >“{{ filters.q }}”</strong
                                >
                            </template>
                            <template v-else
                                >{{ total }} informasi publik</template
                            >
                        </p>
                        <div class="flex items-center gap-2 text-sm">
                            <label for="urut" class="text-slate-600"
                                >Urutkan:</label
                            >
                            <SelectButton
                                id="urut"
                                name="urut"
                                v-model="sort"
                                class="h-11 rounded-full border border-green-200 bg-white py-2 pl-4 pr-9 text-sm text-slate-900 focus:border-green-700 focus:ring-green-700"
                            >
                                <option value="relevan">Paling relevan</option>
                                <option value="terbaru">Terbaru</option>
                                <option value="terlama">Terlama</option>
                            </SelectButton>
                        </div>
                    </div>

                    <div
                        v-if="adaHasil"
                        class="flex flex-col gap-4 transition-opacity"
                        :class="{ 'opacity-60': loading }"
                    >
                        <InformasiCard
                            v-for="item in informasi.data"
                            :key="item.id"
                            :item="item"
                            :query="filters.q ?? ''"
                        />
                    </div>

                    <div
                        v-else
                        class="rounded-3xl border border-dashed border-green-200 bg-white/80 px-6 py-14 text-center"
                    >
                        <SearchX
                            class="mx-auto h-10 w-10 text-green-700"
                            aria-hidden="true"
                        />
                        <h2 class="mt-3 text-lg font-bold text-slate-900">
                            Tidak ada hasil untuk “{{ filters.q }}”
                        </h2>
                        <p class="mx-auto mt-1 max-w-md text-sm text-slate-600">
                            Coba kata kunci yang lebih umum, periksa ejaan, atau
                            ajukan permohonan informasi.
                        </p>
                    </div>

                    <nav
                        v-if="informasi.last_page > 1"
                        class="mt-8 flex flex-wrap items-center justify-center gap-2"
                        aria-label="Paginasi"
                    >
                        <component
                            :is="prev.url ? Link : 'span'"
                            :href="prev.url ?? undefined"
                            preserve-scroll
                            aria-label="Halaman sebelumnya"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-green-200 bg-white text-slate-900"
                            :class="
                                prev.url ? 'hover:bg-green-50' : 'opacity-40'
                            "
                        >
                            <ChevronLeft class="h-5 w-5" />
                        </component>

                        <component
                            :is="p.url ? Link : 'span'"
                            v-for="p in halaman"
                            :key="p.label"
                            :href="p.url ?? undefined"
                            preserve-scroll
                            :aria-current="p.active ? 'page' : undefined"
                            class="inline-flex h-11 min-w-[2.75rem] items-center justify-center rounded-full px-3 text-sm font-semibold"
                            :class="
                                p.active
                                    ? 'bg-green-600 text-white'
                                    : p.url
                                      ? 'border border-green-200 bg-white text-slate-900 hover:bg-green-50'
                                      : 'text-slate-500'
                            "
                            v-text="p.label"
                        />

                        <component
                            :is="next.url ? Link : 'span'"
                            :href="next.url ?? undefined"
                            preserve-scroll
                            aria-label="Halaman berikutnya"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-green-200 bg-white text-slate-900"
                            :class="
                                next.url ? 'hover:bg-green-50' : 'opacity-40'
                            "
                        >
                            <ChevronRight class="h-5 w-5" />
                        </component>
                    </nav>

                    <div
                        class="mt-12 flex flex-col gap-6 rounded-3xl bg-green-600 p-6 sm:p-8 md:flex-row md:items-center md:justify-between"
                    >
                        <div class="flex items-start gap-4">
                            <div
                                class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-white sm:flex"
                            >
                                <FilePlus2 class="h-6 w-6" aria-hidden="true" />
                            </div>
                            <div class="max-w-xl">
                                <h2
                                    class="font-serif text-xl font-bold leading-snug text-white sm:text-2xl"
                                >
                                    Tidak menemukan yang Anda cari?
                                </h2>
                                <p
                                    class="mt-1 text-sm leading-relaxed text-green-50"
                                >
                                    Ajukan permohonan informasi publik dari HP
                                    atau laptop, lalu lacak statusnya kapan
                                    saja.
                                </p>
                            </div>
                        </div>
                        <a
                            :href="PERMOHONAN_URL"
                            class="inline-flex h-12 shrink-0 items-center justify-center gap-2 whitespace-nowrap rounded-full bg-white px-6 text-sm font-bold !text-green-900 no-underline transition hover:bg-green-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-green-700"
                        >
                            Ajukan permohonan
                            <ArrowRight class="h-4 w-4" aria-hidden="true" />
                        </a>
                    </div>
                </section>
            </div>
        </section>
    </PpidLayout>
</template>
