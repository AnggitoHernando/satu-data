<script setup>
defineProps({
    paths: {
        type: Array,
        default: () => [
            { d: "M -20 260 C 240 200, 380 340, 900 300", accent: false },
            { d: "M -20 480 C 260 560, 420 400, 900 460", accent: true },
            { d: "M -20 700 C 240 650, 400 770, 900 720", accent: false },
        ],
    },
});
</script>

<template>
    <div class="pointer-events-none absolute inset-0 z-0" aria-hidden="true">
        <svg
            viewBox="0 0 700 900"
            preserveAspectRatio="none"
            class="h-full w-full"
        >
            <path
                v-for="(path, i) in paths"
                :key="i"
                class="flow-line"
                :class="[path.accent && 'flow-line--accent']"
                :style="{ animationDelay: `${i * 0.15}s` }"
                :d="path.d"
            />
        </svg>
    </div>
</template>

<style scoped>
.flow-line {
    fill: none;
    stroke: white;
    stroke-width: 1.2;
    stroke-dasharray: 1000;
    stroke-dashoffset: 1000;
    animation: draw 2.6s cubic-bezier(0.22, 0.61, 0.36, 1) forwards;
}
.flow-line--accent {
    stroke: #b9803a;
    stroke-width: 1.6;
    opacity: 0.85;
}

@keyframes draw {
    to {
        stroke-dashoffset: 0;
    }
}

@media (prefers-reduced-motion: reduce) {
    .flow-line {
        animation: none;
        stroke-dashoffset: 0;
    }
}
</style>
