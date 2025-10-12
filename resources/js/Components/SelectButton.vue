<script setup>
const model = defineModel({
    type: [String, Number],
    default: "",
    required: true,
});
</script>
<template>
    <select
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full"
        v-model="model"
    >
        <slot />
    </select>
</template>
