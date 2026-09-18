<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import { Link } from "lucide-vue-next";
import Table from "@/Components/Table.vue";
import Loading from "@/Components/Loading.vue";
import MenuTreeNode from "@/Components/MenuTreeNode.vue";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";

const pageLoading = ref(true);
const props = defineProps({
    tree: { type: Array, default: () => [] },
});
const handleLoading = (status) => {
    pageLoading.value = status;
};
onMounted(() => {
    pageLoading.value = false;
});
watch(
    () => usePage().props.flash,
    (flash) => {
        if (flash?.success) {
            Swal.fire({
                icon: "success",
                title: "Sukses",
                text: flash.success,
            });
            pageLoading.value = false;
        } else if (flash?.error) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: flash.error,
            });
            pageLoading.value = false;
        }
        usePage().props.flash.success = null;
        usePage().props.flash.error = null;
    },
    { immediate: true },
);
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Daftar Menu Informasi" />
        <Loading v-if="pageLoading" />
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow p-5">
                    <div
                        class="flex flex-col sm:flex-row justify-between gap-3"
                    >
                        <h1 class="text-2xl font-bold mb-4">Daftar Menu</h1>
                    </div>
                    <div
                        class="mb-4 rounded-md px-3 py-2.5 text-xs text-slate-500"
                    >
                        Menu bertanda
                        <span
                            class="rounded-full bg-blue-50 px-2.5 py-0.5 font-semibold text-blue-700"
                            >grup</span
                        >
                        masih punya sub-menu di dalamnya. Menu bertanda
                        <span
                            class="rounded-full bg-emerald-50 px-2.5 py-0.5 font-semibold text-emerald-700"
                            >dokumen</span
                        >
                        akan menampilkan daftar informasi/file saat diklik
                        pengunjung.
                    </div>

                    <div
                        class="rounded-xl border border-slate-200 bg-white p-6"
                    >
                        <div
                            class="mb-4 flex items-baseline justify-between gap-3"
                        >
                            <h3 class="text-base font-bold">Struktur Menu</h3>
                            <a
                                :href="
                                    route('admin.ppid.menu-informasi.create')
                                "
                            >
                                <PrimaryButtonAdmin>
                                    + Tambah Menu
                                </PrimaryButtonAdmin>
                            </a>
                        </div>

                        <div
                            v-if="tree.length === 0"
                            class="rounded-md border border-dashed border-slate-200 p-4 text-center text-sm text-slate-500"
                        >
                            Belum ada menu. Klik "Tambah Menu" untuk membuat
                            menu utama pertama.
                        </div>
                        <MenuTreeNode
                            v-for="node in tree"
                            :key="node.id"
                            :node="node"
                            @setLoading="handleLoading"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
