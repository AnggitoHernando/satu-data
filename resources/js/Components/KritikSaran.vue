<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButtonAdmin.vue";

// state
const showKritik = ref(false);
const loadingKritik = ref(false);

const formKritik = ref({
    nama: "",
    pesan: "",
});

// submit form
function submitKritik() {
    loadingKritik.value = true;

    router.post(route("kritik.store"), formKritik.value, {
        onFinish: () => {
            loadingKritik.value = false;
            showKritik.value = false;

            formKritik.value = { nama: "", pesan: "" };

            Swal.fire(
                "Berhasil!",
                "Kritik dan Saran Berhasil Dikirim",
                "success"
            );
        },
    });
}
</script>

<template>
    <!-- Floating Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <button
            @click="showKritik = true"
            class="bg-green-700 text-white px-4 py-3 rounded-full shadow-xl hover:bg-green-800 transition flex items-center gap-2"
        >
            <span>Kritik & Saran</span>
        </button>
    </div>

    <!-- Modal -->
    <div
        v-if="showKritik"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[999]"
    >
        <div class="bg-white w-11/12 sm:w-96 rounded-xl shadow-xl p-6 relative">
            <!-- Close -->
            <button
                @click="showKritik = false"
                class="absolute right-3 top-3 text-gray-500 hover:text-gray-700"
            >
                ✕
            </button>

            <h2 class="text-xl font-bold text-gray-800 mb-4 text-center">
                Kritik & Saran
            </h2>

            <form @submit.prevent="submitKritik">
                <div class="mb-3">
                    <label class="text-sm font-medium text-gray-700"
                        >Nama (opsional)</label
                    >
                    <input
                        type="text"
                        placeholder="Masukan Nama Anda"
                        v-model="formKritik.nama"
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-green-700"
                    />
                </div>

                <div class="mb-3">
                    <label class="text-sm font-medium text-gray-700"
                        >Pesan</label
                    >
                    <textarea
                        placeholder="Masukkan Kritik dan Saran"
                        v-model="formKritik.pesan"
                        required
                        rows="4"
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-green-700"
                    ></textarea>
                </div>

                <PrimaryButton
                    class="w-full justify-center mt-2"
                    :disabled="loadingKritik"
                >
                    <span v-if="!loadingKritik">Kirim</span>
                    <span v-else>Mengirim...</span>
                </PrimaryButton>
            </form>
        </div>
    </div>
</template>
