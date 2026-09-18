<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, router, useForm } from "@inertiajs/vue3";
import { ref, onMounted, watch, computed, Text } from "vue";
import { Link } from "lucide-vue-next";
import Loading from "@/Components/Loading.vue";
import Card from "@/Components/Card.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import ComboBox from "@/Components/ComboBox.vue";
import RadioPilGroup from "@/Components/RadioPilGroup.vue";
import PrimaryButtonAdmin from "@/Components/PrimaryButtonAdmin.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    parentOptions: {
        type: [Array, Object],
        default: () => [],
    },
    selectedParentId: {
        type: [Number, String, null],
        default: null,
    },
    defaultUrutan: {
        type: [Number, String, null],
        default: "1",
    },
    mode: {
        type: String,
        default: "createMenu",
    },
    menu: {
        type: Object,
        default: () => ({}),
    },
});

const selectedParentId = ref(props.selectedParentId);

const form = useForm({
    id: props.menu?.id ?? null,
    nama_menu: props.menu?.nama_menu ?? "",
    slug: props.menu?.slug ?? "",
    tipe: props.menu?.tipe ?? "daftar_informasi",
    is_active: props.menu?.is_active ?? true,
    parent_id: props.menu?.parent ?? selectedParentId.value,
    urutan:
        String(
            props.menu.urutan?.length > 0
                ? props.menu?.urutan
                : props.defaultUrutan,
        ) ?? "1",
});
const slugPreview = computed(() => {
    return form.nama_menu.trim()
        ? form.nama_menu
              .trim()
              .toLowerCase()
              .replace(/[^a-z0-9]+/g, "-")
              .replace(/(^-|-$)/g, "")
        : "—";
});

const pageLoading = ref(true);
onMounted(() => {
    pageLoading.value = false;
});

const submit = () => {
    selectedParentId.value = form.parent_id ?? null;

    form.slug = slugPreview.value;
    form.parent_id = form.parent_id ? form.parent_id.id : null;
    if (form.id) {
        form.put(route("admin.ppid.menu-informasi.update", form.id), {
            onLoading: () => {
                pageLoading.value = true;
            },
            onSuccess: () => {},
            onError: (errors) => {
                console.error("Form submission errors:", errors);
                form.parent_id = selectedParentId.value ?? null;
            },
            onFinish: () => {
                pageLoading.value = false;
            },
        });
    } else {
        form.post(route("admin.ppid.menu-informasi.simpan"), {
            onLoading: () => {
                pageLoading.value = true;
            },
            onSuccess: () => {},
            onError: (errors) => {
                console.error("Form submission errors:", errors);
                form.parent_id = selectedParentId ?? null;
                form.urutan = form.urutan ? Number(form.urutan) : 1;
            },
            onFinish: () => {
                pageLoading.value = false;
            },
        });
    }
};
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Tambah Menu Informasi" />
        <Loading v-if="pageLoading" />
        <div class="py-16 relative z-40">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow p-5">
                    <div
                        class="mb-4 flex flex-col sm:flex-row justify-between gap-3"
                    >
                        <h1 class="text-2xl font-bold mb-4">
                            {{
                                props.mode === "createSubMenu"
                                    ? "Tambah Sub Menu"
                                    : props.mode === "edit"
                                      ? "Edit Menu"
                                      : "Tambah Menu"
                            }}
                        </h1>
                    </div>
                    <div>
                        <form @submit.prevent="submit">
                            <Card number="1" class="mb-4" header="Menu">
                                <template #body>
                                    <div class="mb-4">
                                        <InputLabel value="Nama Menu" />
                                        <TextInput
                                            id="nama_menu"
                                            type="text"
                                            placeholder="Masukkan Nama Menu"
                                            class="mt-1 block w-full"
                                            v-model="form.nama_menu"
                                            required
                                            autocomplete="nama_menu"
                                        />
                                        <div
                                            class="mt-2 text-xs text-slate-500"
                                        >
                                            Slug:
                                            <span class="font-mono">{{
                                                slugPreview
                                            }}</span>
                                        </div>
                                        <InputError
                                            :message="form.errors.nama_menu"
                                            class="mt-2"
                                        />
                                    </div>
                                    <div class="mb-4">
                                        <InputLabel
                                            value="Menu Induk (Parent)"
                                        />
                                        <ComboBox
                                            v-if="mode !== 'createSubMenu'"
                                            v-model="form.parent_id"
                                            :emit-object="true"
                                            class="mt-2 block w-full"
                                            search-url="admin.ppid.get-menu-informasi"
                                            label-key="nama_menu"
                                            value-key="id"
                                            placeholder="Cari Parent Menu..."
                                            :minChars="0"
                                            :menu_id="form.id"
                                        />
                                        <TextInput
                                            v-else-if="mode === 'createSubMenu'"
                                            id="nama_menu"
                                            :disabled="true"
                                            type="text"
                                            class="mt-1 block w-full bg-gray-300 text-gray-400 border-gray-200 cursor-not-allowed shadow-none"
                                            v-model="
                                                props.parentOptions.nama_menu
                                            "
                                            required
                                            autocomplete="nama_menu"
                                        />
                                        <div
                                            v-if="mode !== 'createSubMenu'"
                                            class="mt-1 text-xs text-slate-500"
                                        >
                                            Jika Menu Adalah Parent Menu, maka
                                            tidak perlu isi
                                        </div>
                                        <InputError
                                            :message="form.errors.parent_id"
                                            class="mt-2"
                                        />
                                    </div>
                                    <div class="mb-4">
                                        <InputLabel value="Urutan Menu" />
                                        <TextInput
                                            id="tipe"
                                            type="text"
                                            placeholder="Masukkan Urutan Menu"
                                            class="mt-1 block w-full"
                                            v-model="form.urutan"
                                            @keypress="
                                                $event.key.match(/^[0-9]$/) ||
                                                $event.preventDefault()
                                            "
                                            maxlength="4"
                                            required
                                            autocomplete="tipe"
                                        />
                                        <div
                                            class="mt-1 text-xs text-slate-500"
                                        >
                                            Urutan Untuk Tampil Pada Halaman
                                            Depan
                                        </div>
                                        <InputError
                                            :message="form.errors.urutan"
                                            class="mt-2"
                                        />
                                    </div>
                                    <div class="mb-4">
                                        <InputLabel value="Tipe Menu" />
                                        <RadioPilGroup
                                            v-model="form.tipe"
                                            class="mt-4"
                                            :options="[
                                                {
                                                    label: 'Halaman Statis',
                                                    value: 'halaman_statis',
                                                },
                                                {
                                                    label: 'Daftar Informasi',
                                                    value: 'daftar_informasi',
                                                },
                                            ]"
                                            :cols="2"
                                        />
                                        <div
                                            class="mt-2 grid grid-cols-1 gap-2"
                                        >
                                            <span
                                                class="text-xs text-slate-500"
                                            >
                                                Kalau isinya
                                                berkas/surat/laporan yang mau
                                                ditaruh di sini → pilih "Daftar
                                                Informasi". Contoh: mau taruh
                                                PDF Laporan Keuangan, taruh
                                                Peraturan, taruh Surat
                                                Keputusan. Nanti pengunjung
                                                tinggal klik dan bisa membuka
                                                atau menyimpan berkasnya.
                                            </span>
                                            <span
                                                class="text-xs text-slate-500"
                                            >
                                                Kalau Bapak/Ibu mau menulis
                                                langsung di sini (bukan menaruh
                                                berkas) → pilih "Halaman
                                                Cerita/Profil". Contoh: menulis
                                                tentang sejarah kantor, visi
                                                misi, atau profil kantor lengkap
                                                dengan foto. Isinya seperti
                                                mengetik surat biasa, nanti
                                                otomatis jadi halaman yang rapi.
                                            </span>
                                        </div>
                                        <InputError
                                            :message="form.errors.is_active"
                                            class="mt-2"
                                        />
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <SecondaryButton
                                            @click="
                                                () =>
                                                    $inertia.get(
                                                        route(
                                                            'admin.ppid.menu-informasi',
                                                        ),
                                                    )
                                            "
                                        >
                                            Kembali
                                        </SecondaryButton>
                                        <PrimaryButtonAdmin
                                            >Simpan</PrimaryButtonAdmin
                                        >
                                    </div>
                                </template>
                            </Card>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
