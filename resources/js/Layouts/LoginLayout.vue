<script setup>
import AuthSplitpanel from "@/Components/Login/AuthSplitpanel.vue";
</script>

<template>
    <div
        class="grid min-h-screen grid-cols-1 md:grid-cols-[1.05fr_1fr] bg-paper text-ink"
    >
        <AuthSplitpanel
            heading="Kerja jadi mengalir, bukan menumpuk."
            description="Alur menyusun tugas, jadwal, dan percakapan timmu jadi satu alur kerja yang jelas — tanpa perlu berpindah aplikasi."
            quote="Sejak pakai Alur, rapat mingguan kami turun dari lima jam jadi satu jam. Semua sudah tersusun sebelum kami bicara."
            quote-author="Naya Ardhana — Head of Ops, Studio Renjana"
        />
        <section
            class="flex items-center justify-center bg-paper px-6 py-10 md:px-8 md:py-12"
        >
            <slot />
        </section>
    </div>
</template>
