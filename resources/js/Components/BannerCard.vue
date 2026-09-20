<script setup>
const props = defineProps({
    ukuranCard: {
        type: String,
        default: "max-w-7xl",
    },
    useCard: {
        type: Boolean,
        default: true,
    },
    fullHeight: {
        type: Boolean,
        default: true,
    },
});
</script>

<template>
    <div
        :class="[fullHeight ? 'min-h-screen' : '']"
        class="bg-slate-50 font-sans selection:bg-[#0B6E4F] selection:text-white pb-20"
    >
        <section
            class="relative bg-gradient-to-br from-[#0B6E4F] to-[#085239] pt-16 pb-28 sm:pt-20 sm:pb-36 px-4 sm:px-6 overflow-hidden"
        >
            <div
                class="absolute w-40 h-40 rounded-full bg-white/10 -top-12 -right-12 pointer-events-none"
            ></div>
            <div
                class="absolute w-[320px] h-[320px] rounded-full bg-[#B8E986]/30 -top-20 -right-20 blur-3xl pointer-events-none"
            ></div>
            <div
                class="absolute w-[280px] h-[280px] rounded-full bg-[#BFE3F2]/40 -bottom-10 -left-20 blur-3xl pointer-events-none"
            ></div>
            <div
                class="absolute w-40 h-40 rounded-full bg-white/10 -left-12 top-48 pointer-events-none"
            ></div>

            <div class="max-w-4xl mx-auto text-center relative z-10">
                <slot name="banner-content" />
            </div>
        </section>

        <section
            v-if="useCard"
            :class="[ukuranCard]"
            class="mx-auto px-4 sm:px-6 relative z-20 -mt-20 sm:-mt-24"
        >
            <div
                class="bg-white rounded-3xl shadow-2xl shadow-emerald-900/10 border border-emerald-100/80 p-6 sm:p-10 transition-all"
            >
                <div
                    class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100"
                >
                    <slot name="card-header" />
                </div>

                <slot name="card-body" />
            </div>
        </section>
    </div>
</template>
