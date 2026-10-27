<script setup>
const model = defineModel({
    type: [String, Number],
    default: "",
    required: true,
});
</script>
<template>
    <select
        class="rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 w-full"
        v-model="model"
    >
        <slot />
    </select>
</template>
