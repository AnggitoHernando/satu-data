<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Trash2, MessageSquare } from "lucide-vue-next";
import { ref } from "vue";
import { Head, Link, usePage, useForm, router } from "@inertiajs/vue3";

const props = defineProps({
    items: Object,
});

console.log(props.items);
const formatDate = (dateStr) => {
    if (!dateStr) return "-";
    const date = new Date(dateStr);
    return (
        date.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "long",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
            timeZone: "Asia/Jakarta",
        }) + " WIB"
    );
};
function deleteItem(id) {
    Swal.fire({
        title: "Hapus Kritik?",
        text: "Data yang dihapus tidak dapat dikembalikan.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus",
        cancelButtonText: "Batal",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.kritik.destroy", id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Kritik berhasil dihapus.",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false,
                    });
                },
            });
        }
    });
}
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Kritik dan Saran" />
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow p-5">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th
                                    class="p-3 text-sm font-semibold text-gray-600"
                                >
                                    Nama
                                </th>
                                <th
                                    class="p-3 text-sm font-semibold text-gray-600"
                                >
                                    Pesan
                                </th>
                                <th
                                    class="p-3 text-sm font-semibold text-gray-600"
                                >
                                    Waktu
                                </th>
                                <th
                                    class="p-3 text-sm font-semibold text-gray-600 text-right"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="item in items.data"
                                :key="item.id"
                                class="border-b hover:bg-gray-50"
                            >
                                <td class="p-3">{{ item.nama ?? "-" }}</td>
                                <td class="p-3">{{ item.pesan }}</td>
                                <td class="p-3 text-gray-600 text-sm">
                                    {{ formatDate(item.created_at) }}
                                </td>
                                <td class="p-3 text-right">
                                    <button
                                        @click="deleteItem(item.id)"
                                        class="text-red-600 hover:text-red-800"
                                    >
                                        <Trash2 class="w-5 h-5" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4 flex justify-center">
                        <Link
                            v-for="link in items.links"
                            :key="link.label"
                            :href="link.url || ''"
                            v-html="link.label"
                            class="mx-1 px-3 py-1 rounded text-sm"
                            :class="{
                                'bg-green-700 text-white': link.active,
                                'text-gray-600 hover:bg-gray-200': !link.active,
                            }"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
