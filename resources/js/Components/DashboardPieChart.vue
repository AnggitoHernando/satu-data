<script setup>
import { Pie } from "vue-chartjs";
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from "chart.js";

ChartJS.register(ArcElement, Tooltip, Legend);

const props = defineProps({
    title: String,
    labels: Array,
    data: Array,
});

// Fungsi buat warna random sesuai jumlah data
const generateRandomColors = (count) => {
    const colors = [];
    for (let i = 0; i < count; i++) {
        const r = Math.floor(Math.random() * 180) + 50;
        const g = Math.floor(Math.random() * 180) + 50;
        const b = Math.floor(Math.random() * 180) + 50;
        colors.push(`rgba(${r}, ${g}, ${b}, 0.85)`);
    }
    return colors;
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
    plugins: {
        legend: { position: "bottom" },
        tooltip: {
            callbacks: {
                label: (context) => {
                    const label = context.label || "";
                    const value = context.parsed.toFixed(1);
                    return `${label}: ${value}%`;
                },
            },
        },
    },
    maintainAspectRatio: false,
};
</script>

<template>
    <div class="bg-white p-4 rounded-2xl shadow-sm border flex flex-col">
        <h3 class="text-center font-semibold text-gray-800 mb-3">
            {{ title }}
        </h3>
        <div class="flex-1">
            <Pie :data="chartData" :options="chartOptions" class="h-100" />
        </div>
    </div>
</template>
