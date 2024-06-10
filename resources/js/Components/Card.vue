<script setup lang="ts">
const props = defineProps<{
    color?: string;
}>();
</script>
<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-4 h-min w-full mx-2">
        <div :class="color?`bg-${color}-100 dark:bg-${color}-900`:'bg-white dark:bg-gray-80'"
             class="overflow-hidden shadow-sm rounded-lg h-full flex flex-col p-4">
            <slot></slot>
            </div>
        </div>
</template>
