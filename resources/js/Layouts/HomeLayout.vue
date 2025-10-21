<script setup>
import NavbarHome from "@/Components/NavbarHome.vue";
import FooterHome from "@/Components/FooterHome.vue";
</script>
<template>
    <div>
        <NavbarHome>
            <slot />
        </NavbarHome>
        <FooterHome />
    </div>
</template>
