<script setup lang="ts">
const props = defineProps<{
    color: string;
    disableClasses?:boolean;
}>();
const standardClasses = "text-xs font-medium px-2.5 py-0.5 m-1 rounded-full h-min"
</script>
<template>
    <span :class="[`bg-${color}-100 text-${color}-800 dark:bg-${color}-900 dark:text-${color}-300`, disableClasses?'':standardClasses]"><slot/></span>
</template>
