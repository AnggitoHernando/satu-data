<script setup>
import NavbarPpid from "@/Components/NavbarPpid.vue";
import FooterPpid from "@/Components/FooterPpid.vue";
import { isGlobalLoading } from "@/global-loading";
import GlobalLoading from "@/Components/GlobalLoading.vue";
defineProps({
    isContrastMode: {
        type: Boolean,
        default: false,
    },
});
</script>
<template>
    <GlobalLoading v-if="isGlobalLoading" />
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
