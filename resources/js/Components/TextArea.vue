<script setup>
const model = defineModel({ type: String });
</script>
<template>
    <textarea
        class="rounded-md border-gray-300 shadow-sm focus:border-green-800 focus:ring-green-800 w-full"
        v-model="model"
    ></textarea>
</template>
