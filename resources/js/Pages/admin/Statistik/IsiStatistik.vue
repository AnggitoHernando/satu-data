<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Isi Statistik" />
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow p-5">
                    <h1 class="text-2xl font-bold mb-4">Isi Statistik</h1>
                    <p>Ini adalah halaman untuk melihat isi statistik.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
