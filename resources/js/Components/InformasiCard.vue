<script setup>
import { computed, ref } from "vue";
import {
    Building2,
    CalendarDays,
    ChevronDown,
    Download,
    FileText,
    Paperclip,
} from "lucide-vue-next";

const props = defineProps({
    item: { type: Object, required: true },
    query: { type: String, default: "" },
});

const open = ref(false);

const KATEGORI = {
    berkala: "Informasi berkala",
    serta_merta: "Informasi serta merta",
    setiap_saat: "Informasi setiap saat",
    dikecualikan: "Dikecualikan",
};
const BENTUK = {
    hard_copy: "Hard copy",
    soft_copy: "Soft copy",
    keduanya: "Hard copy & soft copy",
};

function humanize(v) {
    const s = String(v ?? "")
        .replace(/[_-]+/g, " ")
        .trim();
    return s.charAt(0).toUpperCase() + s.slice(1);
}
const kategori = computed(
    () => KATEGORI[props.item.kategori] ?? humanize(props.item.kategori),
);
const bentuk = computed(
    () =>
        BENTUK[props.item.bentuk_dokumen] ??
        humanize(props.item.bentuk_dokumen),
);

// Pecah teks menjadi potongan biasa/cocok agar bisa di-highlight tanpa v-html.
function segments(text) {
    const value = text ?? "";
    const terms = props.query.split(/\s+/).filter(Boolean);
    if (!terms.length) return [{ t: value, hit: false }];
    const pattern = new RegExp(
        `(${terms.map((t) => t.replace(/[.*+?^${}()|[\]\\]/g, "\\$&")).join("|")})`,
        "gi",
    );
    return value
        .split(pattern)
        .filter((p) => p !== "")
        .map((p) => ({
            t: p,
            hit: terms.some((t) => t.toLowerCase() === p.toLowerCase()),
        }));
}

function formatSize(bytes) {
    const n = Number(bytes);
    if (!n) return "";
    if (n < 1024) return `${n} B`;
    if (n < 1024 ** 2) return `${(n / 1024).toFixed(0)} KB`;
    return `${(n / 1024 ** 2).toFixed(1)} MB`;
}

const lampiran = computed(() => props.item.lampiran ?? []);
const clamp =
    "display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden";
const btn =
    "inline-flex h-10 items-center rounded-full bg-green-600 px-5 text-sm font-bold text-white transition hover:bg-green-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2";
</script>

<template>
    <article
        class="overflow-hidden rounded-2xl border border-green-100 bg-white shadow-sm transition hover:border-green-300 hover:shadow-md"
    >
        <div class="p-5 sm:p-6">
            <div class="flex items-start justify-between gap-4">
                <div
                    class="flex flex-wrap items-center gap-2 text-xs font-semibold"
                >
                    <span
                        class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-green-100 text-green-700"
                    >
                        <FileText class="h-4 w-4" aria-hidden="true" />
                    </span>
                    <span
                        v-if="kategori"
                        class="rounded-full bg-green-100 px-2.5 py-1 text-green-900"
                        >{{ kategori }}</span
                    >
                    <span
                        v-if="bentuk"
                        class="rounded-full bg-slate-100 px-2.5 py-1 text-slate-700"
                        >{{ bentuk }}</span
                    >
                </div>
                <span
                    v-if="item.tahun"
                    class="inline-flex shrink-0 items-center text-sm font-medium text-slate-500"
                >
                    <CalendarDays class="mr-1.5 h-4 w-4" aria-hidden="true" />{{
                        item.tahun
                    }}
                </span>
            </div>

            <h2
                class="mt-3 text-base font-bold leading-snug text-slate-900 sm:text-lg"
            >
                <template
                    v-for="(s, i) in segments(item.nama_informasi)"
                    :key="i"
                >
                    <mark
                        v-if="s.hit"
                        class="rounded bg-yellow-200 px-0.5 text-inherit"
                        >{{ s.t }}</mark
                    >
                    <template v-else>{{ s.t }}</template>
                </template>
            </h2>

            <p
                v-if="item.ringkasan"
                class="mt-1.5 text-sm leading-relaxed text-slate-600"
                :style="clamp"
            >
                <template v-for="(s, i) in segments(item.ringkasan)" :key="i">
                    <mark
                        v-if="s.hit"
                        class="rounded bg-yellow-200 px-0.5 text-inherit"
                        >{{ s.t }}</mark
                    >
                    <template v-else>{{ s.t }}</template>
                </template>
            </p>
        </div>

        <div
            class="flex flex-wrap items-center justify-between border-t border-green-100 bg-green-50/50 px-5 py-3 sm:px-6"
        >
            <span class="mr-4 inline-flex items-center text-sm text-slate-600">
                <Building2 class="mr-2 h-4 w-4 shrink-0" aria-hidden="true" />
                Kantor Kementerian Agama Kabupaten Gresik
            </span>

            <div v-if="lampiran.length === 1" class="gap-2 flex-shrink-0">
                <a :href="lampiran[0].url" download :class="[btn, 'ml-auto']">
                    <Download class="mr-2 h-4 w-4" aria-hidden="true" />Unduh
                </a>
                <a
                    :href="
                        route('home.ppid.detail_dokumen', {
                            fullPath:
                                item.menu?.slug || item.menu?.parent?.slug,
                            dokumenId: item.id,
                        })
                    "
                    class="ml-2 inline-flex items-center rounded-full bg-gray-100 border px-4 py-2 text-sm font-semibold hover:bg-green-200"
                >
                    <FileText class="mr-2 h-4 w-4" aria-hidden="true" />Lihat
                    Detail
                </a>
            </div>
            <button
                v-else-if="lampiran.length > 1"
                type="button"
                :class="[btn, 'ml-auto']"
                :aria-expanded="open"
                @click="open = !open"
            >
                <Paperclip class="mr-2 h-4 w-4" aria-hidden="true" />{{
                    lampiran.length
                }}
                lampiran
                <ChevronDown
                    class="ml-2 h-4 w-4 transition-transform"
                    :class="{ 'rotate-180': open }"
                    aria-hidden="true"
                />
            </button>
            <span v-else class="ml-auto text-sm text-slate-500"
                >Belum ada lampiran</span
            >
        </div>

        <ul
            v-if="open && lampiran.length > 1"
            class="divide-y divide-green-100 border-t border-green-100"
        >
            <li
                v-for="f in lampiran"
                :key="f.id"
                class="flex flex-wrap items-center px-5 py-3 sm:px-6"
            >
                <FileText
                    class="mr-3 h-5 w-5 shrink-0 text-green-700"
                    aria-hidden="true"
                />
                <div class="mr-4 min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-slate-900">
                        {{ f.nama_file }}
                    </p>
                    <p class="text-xs text-slate-600">
                        {{
                            [
                                String(f.tipe_file ?? "").toUpperCase(),
                                formatSize(f.ukuran_file),
                            ]
                                .filter(Boolean)
                                .join(", ")
                        }}
                    </p>
                </div>

                <div class="mt-2 flex-shrink-0 sm:mt-0">
                    <a
                        :href="f.url"
                        download
                        class="inline-flex h-9 items-center rounded-full border border-green-700 px-4 text-sm font-bold text-green-700 transition hover:bg-green-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-700"
                    >
                        <Download
                            class="mr-2 h-4 w-4"
                            aria-hidden="true"
                        />Unduh
                    </a>
                    <a
                        :href="
                            route('home.ppid.detail_dokumen', {
                                fullPath:
                                    item.menu?.slug || item.menu?.parent?.slug,
                                dokumenId: item.id,
                            })
                        "
                        class="ml-2 inline-flex items-center rounded-full bg-gray-100 border px-4 py-2 text-sm font-semibold hover:bg-green-200"
                    >
                        <FileText
                            class="mr-2 h-4 w-4"
                            aria-hidden="true"
                        />Lihat Detail
                    </a>
                </div>
            </li>
        </ul>
    </article>
</template>
