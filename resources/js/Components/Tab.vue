<script setup>
import { ref } from "vue";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
const props = defineProps({
    categories: {
        type: Object,
        default: () => ({}),
    },
});
</script>
<template>
    <div class="w-full px-2 sm:px-0">
        <TabGroup>
            <TabList class="flex space-x-1 rounded-xl bg-green-900 p-1">
                <Tab
                    v-for="category in categories"
                    as="template"
                    :key="category.name"
                    v-slot="{ selected }"
                >
                    <button
                        :class="[
                            'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
                            'ring-white/60 ring-offset-2 ring-offset-green-600 focus:outline-none focus:ring-2',
                            selected
                                ? 'bg-white text-green-800 shadow'
                                : 'text-green-100 hover:bg-white/[0.12] hover:text-white',
                        ]"
                    >
                        {{ category.name }}
                    </button>
                </Tab>
            </TabList>

            <TabPanels class="mt-2">
                <TabPanel v-for="category in categories" :key="category.key">
                    <slot :name="`tab-panel-${category.key}`"> </slot>
                </TabPanel>
            </TabPanels>
        </TabGroup>
    </div>
</template>
