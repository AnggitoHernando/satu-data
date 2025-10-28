<script setup>
import LoginLayout from "@/Layouts/LoginLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Eye, EyeOff } from "lucide-vue-next";
import { ref } from "vue";
import Toastify from "toastify-js";
const showPassword = ref(false);

const form = useForm({
    username: "",
    password: "",
});

const showToast = (message, type = "info") => {
    Toastify({
        text: message,
        duration: 3000,
        gravity: "top", // posisi "top" atau "bottom"
        position: "right",
        close: true,
        backgroundColor:
            type === "success"
                ? "#22C55E"
                : type === "error"
                ? "#EF4444"
                : "#3B82F6",
    }).showToast();
};

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
        onError: (errors) => {
            // Jika ada error validasi dari Laravel
            Object.values(errors).forEach((message) => {
                Toastify({
                    text: message,
                    duration: 4000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#EF4444", // merah error
                    stopOnFocus: true,
                }).showToast();
            });
        },
    });
};
</script>

<template>
    <LoginLayout>
        <Head title="Log in" />

        <!-- <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div> -->

        <form @submit.prevent="submit">
            <div>
                <div id="logo" class="text-center">
                    <Link href="/login">
                        <ApplicationLogo class="w-36" />
                        <span class="block mb-2 text-xl font-medium text-white"
                            >MANDAT</span
                        >
                    </Link>
                </div>
                <InputLabel for="username" value="Username" />

                <TextInput
                    id="username"
                    type="text"
                    placeholder="Masukkan Username"
                    class="mt-1 block w-full"
                    v-model="form.username"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4 relative">
                <InputLabel for="password" value="Password" />

                <input
                    :type="showPassword ? 'text' : 'password'"
                    placeholder="Masukkan password"
                    id="password"
                    v-model="form.password"
                    class="border rounded w-full px-3 py-2 pr-10"
                />

                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute right-2 bottom-1 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                >
                    <Eye v-if="!showPassword" class="w-5 h-5" />
                    <EyeOff v-else class="w-5 h-5" />
                </button>

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                    class="w-full justify-center"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </LoginLayout>
</template>
