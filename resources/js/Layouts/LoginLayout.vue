<script setup>
import AuthSplitpanel from "@/Components/Login/AuthSplitpanel.vue";
import Card from "@/Components/Card.vue";
</script>

<template>
    <div
        class="grid min-h-screen grid-cols-1 md:grid-cols-[1.05fr_1fr] bg-paper text-ink"
    >
        <AuthSplitpanel
            heading="Informasi terbuka, umat percaya."
            description="Satu wadah transparan untuk mengakses seluruh data publik dan kebijakan Kementerian Agama."
            quote="Transparan layanannya, jelas alurnya.Buka akses informasi publik tanpa syarat yang membingungkan."
            quote-author="Kantor Kementerian Agama Kabupaten Gresik"
            brand="Kantor Kementerian Agama Kabupaten Gresik"
        />
        <section
            class="flex items-center justify-center bg-paper px-6 py-10 md:px-8 md:py-12"
        >
            <div class="w-full max-w-sm">
                <slot />
            </div>
        </section>
    </div>
</template>
