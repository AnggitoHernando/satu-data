<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import PpidLayout from "@/Layouts/PpidLayout.vue";
import BannerCard from "@/Components/BannerCard.vue";
import { ShieldCheck } from "lucide-vue-next";
import HalamanDepanTreeNode from "@/Components/HalamanDepanTreeNode.vue";
const handleLoading = (status) => {
    pageLoading.value = status;
};
</script>

<template>
    <Head :title="`PPID Kemenag Gresik - ${usePage().props.judul_banner}`">
        <meta name="description" content="Informasi Publik" />
    </Head>
    <PpidLayout>
        <BannerCard>
            <template #banner-content>
                <span
                    class="inline-flex items-center gap-1.5 bg-gradient-to-b from-[#EBF7F1] via-[#F4FAF6] to-slate-50 text-xs font-bold px-4 py-1.5 rounded-full mb-4 border border-[#0B6E4F]/20"
                >
                    <ShieldCheck class="w-4 h-4" />
                    Informasi publik
                </span>

                <h1
                    class="text-3xl sm:text-4xl md:text-5xl font-bold font-serif tracking-tight leading-tight"
                >
                    {{ usePage().props.judul_banner }}
                </h1>

                <p class="mt-3.5 text-xs text-white mx-auto leading-relaxed">
                    {{ usePage().props.sub_judul_banner }}
                </p>
            </template>

            <template #card-header>
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 font-serif">
                            Daftar {{ usePage().props.judul_banner }}
                        </h2>
                    </div>
                </div>
            </template>

            <template #card-body>
                <div v-if="usePage().props.listInformasi.length > 0">
                    <HalamanDepanTreeNode
                        v-for="(node, index) in usePage().props.listInformasi"
                        :key="node.id"
                        :currentIndex="index + 1"
                        :totalCount="usePage().props.listInformasi.length"
                        :node="node"
                        @setLoading="handleLoading"
                    />
                </div>
                <div v-else>
                    <div class="text-center text-slate-600 text-xs">
                        Belum ada informasi yang ditemukan.
                    </div>
                </div>
            </template>
        </BannerCard>
    </PpidLayout>
</template>
