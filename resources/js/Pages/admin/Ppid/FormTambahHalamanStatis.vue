<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, useForm } from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import ComboBox from "@/Components/ComboBox.vue";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";
import { ref, onMounted, computed } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import FileUpload from "@/Components/FileUpload.vue";
import TextArea from "@/Components/TextArea.vue";
import RichTextEditor from "@/Components/RichTextEditor.vue";
import Loading from "@/Components/Loading.vue";

const props = defineProps({
    menuId: String,
});

const selectedMenu = ref(null);
const pageLoading = ref(true);

const form = useForm({
    id: usePage().props.menu.halaman_statis?.id ?? "",
    menu_id: usePage().props.menu ?? "",
    judul: usePage().props.menu.halaman_statis?.judul ?? "",
    isi_konten: usePage().props.menu.halaman_statis?.isi_konten ?? "",
    gambar_utama: usePage().props.menu.halaman_statis?.gambar_utama ?? null,
    meta_deskripsi: usePage().props.menu.halaman_statis?.meta_deskripsi ?? "",
});

const submit = () => {
    selectedMenu.value = form.menu_id ?? null;
    form.menu_id = form.menu_id ? form.menu_id.id : null;
    pageLoading.value = true;
    if (form.id) {
        if (!(form.gambar_utama instanceof File)) {
            form.gambar_utama = null;
        }
        form.transform((data) => ({
            ...data,
            _method: "put",
        })).post(route("admin.ppid.halaman-statis.update", form.id), {
            onLoading: () => {
                pageLoading.value = true;
            },
            onSuccess: () => {},
            onError: (errors) => {
                console.error("Form submission errors:", errors);
                form.menu_id = selectedMenu.value;
                form.gambar_utama =
                    usePage().props.menu.halaman_statis?.gambar_utama ?? null;
            },
            onFinish: () => {
                pageLoading.value = false;
            },
        });
    } else {
        form.post(route("admin.ppid.halaman-statis.simpan"), {
            onLoading: () => {
                pageLoading.value = true;
            },
            onSuccess: () => {},
            onError: (errors) => {
                console.error("Form submission errors:", errors);
                form.menu_id = selectedMenu.value;
            },
            onFinish: () => {
                pageLoading.value = false;
            },
        });
    }
};
onMounted(() => {
    pageLoading.value = false;
});
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Tambah Halaman Statis" />
        <Loading v-if="pageLoading" />
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow p-5">
                    <div
                        class="mb-4 flex flex-col sm:flex-row justify-between gap-3"
                    >
                        <h1 class="text-2xl font-bold mb-4">
                            Tambah Halaman Statis
                        </h1>
                    </div>
                    <div>
                        <form @submit.prevent="submit">
                            <Card
                                number="1"
                                class="mb-4"
                                header="Informasi Dasar"
                            >
                                <template #body>
                                    <div class="mb-4">
                                        <InputLabel value="Menu" />
                                        <TextInput
                                            id="nama_menu"
                                            :disabled="true"
                                            type="text"
                                            class="mt-1 block w-full bg-gray-300 text-gray-400 border-gray-200 cursor-not-allowed shadow-none"
                                            v-model="
                                                usePage().props.menu.nama_menu
                                            "
                                            required
                                            autocomplete="nama_menu"
                                        />
                                        <InputError
                                            :message="form.errors.menu_id"
                                            class="mt-2"
                                        />
                                    </div>
                                    <div class="mb-4">
                                        <InputLabel value="Judul" />
                                        <TextInput
                                            id="judul"
                                            type="text"
                                            placeholder="Masukkan Judul"
                                            class="mt-1 block w-full"
                                            v-model="form.judul"
                                            autocomplete="judul"
                                        />
                                        <InputError
                                            :message="form.errors.judul"
                                            class="mt-2"
                                        />
                                    </div>
                                    <div class="mb-4">
                                        <InputLabel value="Gambar Utama" />
                                        <FileUpload
                                            v-model="form.gambar_utama"
                                            accept=".png,.jpg,.jpeg,.gif"
                                            :maxSize="5"
                                            class="mt-1 block w-full"
                                        />
                                        <InputError
                                            :message="form.errors.gambar_utama"
                                            class="mt-2"
                                        />
                                    </div>
                                    <div class="mb-4">
                                        <InputLabel value="Isi Konten" />
                                        <RichTextEditor
                                            v-model="form.isi_konten"
                                        />
                                        <InputError
                                            :message="form.errors.isi_konten"
                                            class="mt-2"
                                        />
                                    </div>
                                    <div class="mb-4">
                                        <InputLabel value="Meta Deskripsi" />
                                        <TextArea
                                            id="meta_deskripsi"
                                            type="text"
                                            placeholder="Masukkan Meta Deskripsi"
                                            class="mt-1 block w-full"
                                            v-model="form.meta_deskripsi"
                                            required
                                            autocomplete="meta_deskripsi"
                                            rows="3"
                                            maxlength="255"
                                        />
                                        <InputError
                                            :message="
                                                form.errors.meta_deskripsi
                                            "
                                            class="mt-2"
                                        />
                                    </div>
                                </template>
                            </Card>
                            <div class="flex justify-end gap-2">
                                <SecondaryButton
                                    @click="
                                        () =>
                                            $inertia.get(
                                                route(
                                                    'admin.ppid.halaman-statis',
                                                ),
                                            )
                                    "
                                >
                                    Kembali
                                </SecondaryButton>
                                <PrimaryButtonAdmin>Simpan</PrimaryButtonAdmin>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
