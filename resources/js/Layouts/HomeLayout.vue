<script setup>
import NavbarHome from "@/Components/NavbarHome.vue";
import FooterHome from "@/Components/FooterHome.vue";
import KritikSaran from "@/Components/KritikSaran.vue";
</script>
<template>
    <div>
        <NavbarHome>
            <slot />
        </NavbarHome>
        <KritikSaran />
        <FooterHome />
    </div>
</template>
