<script setup>
import NavbarPpid from "@/Components/NavbarPpid.vue";
import FooterPpid from "@/Components/FooterPpid.vue";
defineProps({
    isContrastMode: {
        type: Boolean,
        default: false,
    },
});
</script>
<template>
    <div
        :class="[
            'min-h-screen font-sans transition-colors duration-200 selection:bg-[#0B6E4F] selection:text-white',
        ]"
    >
        <NavbarPpid :is-contrast-mode="isContrastMode" />

        <main>
            <slot />
        </main>

        <FooterPpid />
    </div>
</template>
