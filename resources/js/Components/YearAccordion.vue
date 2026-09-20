<script setup>
import { ref } from "vue";
import { ChevronDown } from "lucide-vue-next";
import DocumentRow from "@/Components/DocumentRow.vue";

const props = defineProps({
    year: { type: [String, Number], required: true },
    documents: { type: Array, required: true },
    defaultOpen: { type: Boolean, default: true },
});

const open = ref(props.defaultOpen);

// id unik per instance supaya aria-controls/aria-labelledby tidak bentrok
// kalau ada beberapa YearAccordion dalam satu halaman.
const uid = `year-${props.year}-${Math.random().toString(36).slice(2, 8)}`;
const buttonId = `${uid}-trigger`;
const panelId = `${uid}-panel`;
</script>

<template>
    <section
        class="mb-4 overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm shadow-emerald-950/5"
    >
        <h2 class="m-0">
            <button
                :id="buttonId"
                type="button"
                class="flex w-full items-center justify-between gap-3 px-5 py-4 text-left focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-emerald-600"
                :aria-expanded="open"
                :aria-controls="panelId"
                @click="open = !open"
            >
                <span class="flex items-baseline gap-3">
                    <span
                        class="font-serif text-xl font-semibold text-stone-900"
                        >{{ year }}</span
                    >
                    <span class="text-xs font-medium text-stone-500"
                        >{{ documents.length }} dokumen</span
                    >
                </span>
                <ChevronDown
                    aria-hidden="true"
                    class="h-5 w-5 flex-shrink-0 text-stone-400 transition-transform duration-200 motion-reduce:transition-none"
                    :class="{ 'rotate-180': open }"
                />
            </button>
        </h2>

        <div
            :id="panelId"
            role="region"
            :aria-labelledby="buttonId"
            v-show="open"
        >
            <DocumentRow
                v-for="(doc, i) in documents"
                :key="i"
                :name="doc.name"
                :tag="doc.tag"
                :url="doc.url"
                :type="doc.type"
            />
        </div>
    </section>
</template>
