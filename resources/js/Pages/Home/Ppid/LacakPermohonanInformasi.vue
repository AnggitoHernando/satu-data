<script setup>
import PpidLayout from "@/Layouts/PpidLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import InputWithButton from "@/Components/InputWithButton.vue";
import { SendIcon } from "lucide-vue-next";
import { QrCode } from "lucide-vue-next";

const form = useForm({
    nomor_permohonan: "",
});
const isContrastMode = ref(false);
</script>
<template>
    <Head title="PPID Kemenag Gresik — Lacak Permohonan Informasi" />
    <PpidLayout :is-contrast-mode="isContrastMode">
        <section
            class="bg-green-800 text-white py-16 shadow-md md:py-20 text-center transition-all"
        >
            <div class="max-w-4xl mx-auto px-4">
                <h1
                    class="text-2xl md:text-3xl text-white font-bold text-slate-800 tracking-tight mb-2"
                >
                    Lacak Status Permohonan Informasi Atau Keberatan
                </h1>

                <p
                    class="text-sm md:text-base text-slate-600 max-w-2xl mx-auto mb-3 text-white"
                >
                    Masukkan Nomor Registrasi Permohonan Anda (contoh:
                    PPID1234567 atau KBR1234567) untuk memantau progres tindak
                    lanjut.
                </p>
                <div class="mt-5 flex flex-col gap-4">
                    <form class="mt-5 flex flex-col gap-4">
                        <InputWithButton
                            v-model="form.nomor_permohonan"
                            placeholder="Masukkan Nomor Registrasi Permohonan"
                            button-text="Lacak Progres"
                            class="w-full"
                        >
                            <template #icon>
                                <QrCode class="w-6 h-6 stroke-[2.2]" />
                            </template>
                        </InputWithButton>
                    </form>
                </div>
            </div>
        </section>
    </PpidLayout>
</template>
