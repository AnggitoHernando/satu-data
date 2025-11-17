<script setup>
import { Pie } from "vue-chartjs";
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from "chart.js";

ChartJS.register(ArcElement, Tooltip, Legend);

const props = defineProps({
    title: String,
    labels: Array,
    data: Array,
});

// Generate warna random
const generateRandomColors = (count) => {
    return Array.from({ length: count }, () => {
        const r = Math.floor(Math.random() * 180) + 50;
        const g = Math.floor(Math.random() * 180) + 50;
        const b = Math.floor(Math.random() * 180) + 50;
        return `rgba(${r}, ${g}, ${b}, 0.85)`;
    });
};

const backgroundColors = generateRandomColors(props.data.length);

const chartData = {
    labels: props.labels,
    datasets: [
        {
            data: props.data,
            backgroundColor: backgroundColors,
            borderColor: "#fff",
            borderWidth: 2,
        },
    ],
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "bottom",
            labels: {
                font: {
                    size: 12, // kecil biar muat di mobile
                },
            },
        },
        tooltip: {
            callbacks: {
                label: (context) => {
                    const label = context.label || "";
                    const value = context.parsed?.toFixed(1) ?? 0;
                    return `${label}: ${value}%`;
                },
            },
        },
    },
};
</script>

<template>
    <div class="bg-white p-4 rounded-2xl shadow-sm border flex flex-col w-full">
        <h3
            class="text-center font-semibold text-gray-800 mb-3 text-sm sm:text-base"
        >
            {{ title }}
        </h3>

        <!-- Container chart -->
        <div
            class="relative flex-1 min-h-[200px] sm:min-h-[250px] md:min-h-[300px]"
        >
            <Pie :data="chartData" :options="chartOptions" />
        </div>
    </div>
</template>
