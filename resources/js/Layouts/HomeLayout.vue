<script setup>
import NavbarHome from "@/Components/NavbarHome.vue";
import FooterHome from "@/Components/FooterHome.vue";
import KritikSaran from "@/Components/KritikSaran.vue";
import GlobalLoading from "@/Components/GlobalLoading.vue";
import { isGlobalLoading } from "@/global-loading";

</script>
<template>
    <GlobalLoading v-if="isGlobalLoading" />
    <div>
        <NavbarHome>
            <slot />
        </NavbarHome>
        <KritikSaran />
        <FooterHome />
    </div>
</template>
