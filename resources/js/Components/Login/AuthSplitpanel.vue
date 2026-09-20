<script setup>
import FlowIllustration from "./FlowIllustration .vue";

defineProps({
    brand: { type: String, default: "Alur" },
    heading: { type: String, required: true },
    description: { type: String, required: true },
    quote: { type: String, default: null },
    quoteAuthor: { type: String, default: null },
});
</script>

<template>
    <section
        class="relative flex flex-col justify-between overflow-hidden bg-panel px-7 py-10 text-[#F1EFE7] md:px-14 md:py-12 min-h-[44vh] md:min-h-0"
    >
        <FlowIllustration />

        <div class="relative z-10 flex items-center gap-2 font-display text-xl">
            <span
                class="h-[26px] w-[26px] flex-shrink-0 rounded-full bg-gradient-to-br from-brass-soft to-brass"
            ></span>
            {{ brand }}
        </div>

        <div class="relative z-10 mt-[6vh] max-w-lg">
            <h1
                class="mb-4 font-display text-[clamp(2.1rem,3.4vw,2.9rem)] font-medium leading-[1.12] tracking-tight"
            >
                {{ heading }}
            </h1>
            <p class="max-w-md text-base leading-relaxed text-[#F1EFE7]/70">
                {{ description }}
            </p>
        </div>

        <div
            v-if="quote"
            class="relative z-10 hidden max-w-md border-t border-white/10 pt-6 text-sm leading-relaxed text-[#F1EFE7]/80 md:block"
        >
            “{{ quote }}”
            <span
                v-if="quoteAuthor"
                class="mt-2 block text-[13px] text-[#F1EFE7]/50"
                >{{ quoteAuthor }}</span
            >
        </div>
    </section>
</template>
