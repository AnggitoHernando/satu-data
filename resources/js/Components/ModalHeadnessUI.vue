<script setup>
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    DialogOverlay,
    TransitionRoot,
    TransitionChild,
} from "@headlessui/vue";
const props = defineProps({
    judul_modal: {
        type: String,
        default: "",
    },
    openModal: {
        type: Boolean,
        default: false,
    },
});
defineEmits(["close"]);
</script>
<template>
    <TransitionRoot :show="openModal" as="template">
        <Dialog class="fixed inset-0 z-50 overflow-y-auto" static>
            <div class="flex min-h-screen items-center justify-center p-4">
                <DialogOverlay
                    class="fixed inset-0 bg-black bg-opacity-20 backdrop-blur-md z-40"
                />

                <TransitionChild
                    enter="ease-out duration-300"
                    enter-from="opacity-0 scale-95"
                    enter-to="opacity-100 scale-100"
                    leave="ease-in duration-200"
                    leave-from="opacity-100 scale-100"
                    leave-to="opacity-0 scale-95"
                >
                </TransitionChild>
                <DialogPanel
                    class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 z-50 relative"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">{{ judul_modal }}</h2>
                        <button
                            @click="$emit('close')"
                            class="p-2 rounded hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            ✕
                        </button>
                    </div>

                    <slot />
                </DialogPanel>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
