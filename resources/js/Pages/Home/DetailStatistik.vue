<script setup>
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { ref, computed, onMounted, watch } from "vue";
import { ArrowLeft, BarChart2, TrendingUp } from "lucide-vue-next";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

const page = usePage();
const kategori = computed(() => page.props.kategori);
const groups = computed(() => page.props.groups ?? []);
const tahunList = computed(() => page.props.tahun_list ?? []);

const activeGroup = ref(groups.value[0] ?? null);
const activeTahun = ref(tahunList.value[tahunList.value.length - 1] ?? null);
const chartType = ref("bar");
const chartRef = ref(null);
let chartInstance = null;

const activeItems = computed(() => {
    if (!activeGroup.value) return [];
    return activeGroup.value.items.map((item) => ({
        ...item,
        value:
            item.statistik.find((s) => s.tahun == activeTahun.value)?.value ??
            0,
    }));
});

const totalItems = computed(() =>
    groups.value.reduce((acc, g) => acc + g.items.length, 0),
);

const maxValue = computed(() =>
    Math.max(...activeItems.value.map((i) => i.value), 1),
);

const COLORS = [
    "#1D9E75",
    "#5DCAA5",
    "#9FE1CB",
    "#C6EDE0",
    "#0F6E56",
    "#085041",
];

// ─── Chart ────────────────────────────────────────────────────────
const buildChart = () => {
    if (!chartRef.value) return;
    if (chartInstance) chartInstance.destroy();

    const labels = activeItems.value.map((i) => i.nama_item);
    const data = activeItems.value.map((i) => i.value);
    const colors = activeItems.value.map(
        (_, idx) => COLORS[idx % COLORS.length],
    );

    chartInstance = new Chart(chartRef.value, {
        type: chartType.value,
        data: {
            labels,
            datasets: [
                {
                    label: activeGroup.value?.nama_group ?? "",
                    data,
                    backgroundColor:
                        chartType.value === "bar" ? colors : "transparent",
                    borderColor: colors[0],
                    borderWidth: chartType.value === "line" ? 2 : 0,
                    pointBackgroundColor: colors[0],
                    pointRadius: chartType.value === "line" ? 4 : 0,
                    borderRadius: chartType.value === "bar" ? 6 : 0,
                    tension: 0.3,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) =>
                            ` ${Number(ctx.parsed.y).toLocaleString("id-ID")}`,
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } },
                },
                y: {
                    grid: { color: "rgba(0,0,0,0.05)" },
                    ticks: {
                        font: { size: 11 },
                        callback: (v) => Number(v).toLocaleString("id-ID"),
                    },
                },
            },
        },
    });
};

onMounted(() => buildChart());
watch([activeGroup, activeTahun, chartType], () => buildChart());

const kembali = () => router.visit(route("PortalData"));
</script>

<template>
    <Head :title="`Statistik - ${kategori.nama_kategori}`" />
    <HomeLayout>
        <div class="min-h-screen bg-gray-50">
            <div class="bg-white border-b border-gray-100">
                <div
                    class="max-w-5xl mx-auto px-4 sm:px-6 py-3 flex items-center gap-2 text-xs text-gray-400"
                >
                    <button
                        @click="kembali"
                        class="flex items-center gap-1 hover:text-green-700 transition-colors"
                    >
                        <ArrowLeft class="w-3.5 h-3.5" />
                        Portal Data
                    </button>
                    <span>›</span>
                    <span>Statistik</span>
                    <span>›</span>
                    <span class="text-gray-700">{{
                        kategori.nama_kategori
                    }}</span>
                </div>
            </div>

            <div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 space-y-5">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="p-1.5 bg-green-50 rounded-lg">
                            <BarChart2 class="w-4 h-4 text-green-700" />
                        </div>
                        <span
                            class="text-xs font-medium text-green-700 bg-green-50 border border-green-100 px-2 py-0.5 rounded-md"
                        >
                            STATISTIK
                        </span>
                    </div>
                    <h1
                        class="text-xl sm:text-2xl font-bold text-gray-800 mt-2"
                    >
                        {{ kategori.nama_kategori }}
                    </h1>
                    <p
                        class="text-sm text-gray-400 mt-1 flex items-center gap-2"
                    >
                        <span>{{ kategori.seksi?.nama_seksi }}</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                        <span>{{ groups.length }} Kategori</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                        <span>{{ totalItems }} Rincian Data</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                        <span>{{ tahunList.join(", ") }}</span>
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-400 mb-1">
                            Jumlah Kategori
                        </p>
                        <p class="text-2xl font-bold text-green-700">
                            {{ groups.length }}
                        </p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-400 mb-1">
                            Jumlah Rincian Data
                        </p>
                        <p class="text-2xl font-bold text-green-700">
                            {{ totalItems }}
                        </p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-400 mb-1">Tahun tersedia</p>
                        <p class="text-2xl font-bold text-green-700">
                            {{ tahunList.length }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ tahunList[0] }} –
                            {{ tahunList[tahunList.length - 1] }}
                        </p>
                    </div>
                </div>

                <div
                    class="bg-white border border-gray-200 rounded-2xl overflow-hidden"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-gray-100"
                    >
                        <span class="text-sm font-semibold text-gray-800"
                            >Grafik statistik</span
                        >
                        <div class="flex items-center gap-2 flex-wrap">
                            <!-- Filter tahun -->
                            <div class="flex gap-1">
                                <button
                                    v-for="t in tahunList"
                                    :key="t"
                                    class="text-xs px-2.5 py-1 rounded-full border transition-all"
                                    :class="
                                        activeTahun == t
                                            ? 'bg-green-700 text-white border-green-700'
                                            : 'border-gray-200 text-gray-500 hover:border-green-400'
                                    "
                                    @click="activeTahun = t"
                                >
                                    {{ t }}
                                </button>
                            </div>
                            <div
                                class="flex bg-gray-100 rounded-lg p-0.5 gap-0.5"
                            >
                                <button
                                    class="text-xs px-3 py-1 rounded-md transition-all"
                                    :class="
                                        chartType === 'bar'
                                            ? 'bg-white text-gray-800 shadow-sm'
                                            : 'text-gray-500'
                                    "
                                    @click="chartType = 'bar'"
                                >
                                    Bar
                                </button>
                                <button
                                    class="text-xs px-3 py-1 rounded-md transition-all"
                                    :class="
                                        chartType === 'line'
                                            ? 'bg-white text-gray-800 shadow-sm'
                                            : 'text-gray-500'
                                    "
                                    @click="chartType = 'line'"
                                >
                                    Line
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="px-5 pt-4">
                        <div class="flex gap-2 flex-wrap mb-4">
                            <button
                                v-for="group in groups"
                                :key="group.id"
                                class="text-xs px-3 py-1.5 rounded-full border transition-all"
                                :class="
                                    activeGroup?.id === group.id
                                        ? 'bg-green-700 text-white border-green-700'
                                        : 'border-gray-200 text-gray-500 hover:border-green-400'
                                "
                                @click="activeGroup = group"
                            >
                                {{ group.nama_group }}
                            </button>
                        </div>

                        <div class="flex flex-wrap gap-3 mb-4">
                            <span
                                v-for="(item, idx) in activeItems"
                                :key="item.id"
                                class="flex items-center gap-1.5 text-xs text-gray-500"
                            >
                                <span
                                    class="w-2.5 h-2.5 rounded-sm flex-shrink-0"
                                    :style="{
                                        background: COLORS[idx % COLORS.length],
                                    }"
                                ></span>
                                {{ item.nama_item }}:
                                {{ Number(item.value).toLocaleString("id-ID") }}
                            </span>
                        </div>

                        <div class="relative w-full" style="height: 240px">
                            <canvas
                                ref="chartRef"
                                role="img"
                                :aria-label="`Grafik ${chartType} ${activeGroup?.nama_group} tahun ${activeTahun}`"
                            ></canvas>
                        </div>
                    </div>
                    <div class="px-5 py-3"></div>
                </div>

                <div
                    class="bg-white border border-gray-200 rounded-2xl overflow-hidden"
                >
                    <div
                        class="flex items-center justify-between px-5 py-4 border-b border-gray-100"
                    >
                        <span class="text-sm font-semibold text-gray-800"
                            >Tabel data</span
                        >
                        <span class="text-xs text-gray-400"
                            >{{ activeGroup?.nama_group }} ·
                            {{ activeTahun }}</span
                        >
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="text-left px-5 py-3 text-xs font-medium text-gray-400 border-b border-gray-100"
                                    >
                                        Item
                                    </th>
                                    <th
                                        class="text-left px-5 py-3 text-xs font-medium text-gray-400 border-b border-gray-100"
                                    >
                                        Nilai
                                    </th>
                                    <th
                                        class="text-left px-5 py-3 text-xs font-medium text-gray-400 border-b border-gray-100"
                                    >
                                        Proporsi
                                    </th>
                                    <th
                                        class="text-left px-5 py-3 text-xs font-medium text-gray-400 border-b border-gray-100 w-40"
                                    >
                                        Distribusi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="item in activeItems"
                                    :key="item.id"
                                    class="hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0"
                                >
                                    <td class="px-5 py-3 text-gray-700">
                                        {{ item.nama_item }}
                                    </td>
                                    <td
                                        class="px-5 py-3 font-medium text-gray-800"
                                    >
                                        {{
                                            Number(item.value).toLocaleString(
                                                "id-ID",
                                            )
                                        }}
                                    </td>
                                    <td class="px-5 py-3 text-gray-400 text-xs">
                                        {{
                                            item.value
                                                ? (
                                                      (item.value /
                                                          activeItems.reduce(
                                                              (a, b) =>
                                                                  a + b.value,
                                                              0,
                                                          )) *
                                                      100
                                                  ).toFixed(1)
                                                : 0
                                        }}%
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden"
                                            >
                                                <div
                                                    class="h-1.5 bg-green-500 rounded-full transition-all"
                                                    :style="{
                                                        width: `${Math.round((item.value / maxValue) * 100)}%`,
                                                    }"
                                                ></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </HomeLayout>
</template>
