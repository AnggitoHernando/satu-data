<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <!-- <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template> -->

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Halo {{ $page.props.auth.user.name }} , Kamu Sudah
                        Login!
                    </div>
                </div>
                <Link :href="route('Beranda')">
                    <PrimaryButtonAdmin> Beranda</PrimaryButtonAdmin>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
