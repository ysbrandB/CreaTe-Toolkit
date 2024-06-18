<script setup lang="ts">
const props = defineProps<{
    color?: string;
}>();
</script>
<template>
    <div class="w-full my-2">
        <div :class="color?`bg-${color}-100 dark:bg-${color}-900`:'bg-white dark:bg-gray-80'"
             class="overflow-hidden shadow-sm rounded-lg h-full flex flex-col p-4 mx-2">
            <slot></slot>
            </div>
        </div>
</template>
