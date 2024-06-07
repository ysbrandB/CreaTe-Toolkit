<script setup lang="ts">
const props = defineProps<{
    color: string;
}>();
</script>
<template>
    <span class="text-xs font-medium px-2.5 py-0.5 m-1 rounded-full h-min" :class="`bg-${color}-100 text-${color}-800 dark:bg-${color}-900 dark:text-${color}-300`"><slot/></span>
</template>
