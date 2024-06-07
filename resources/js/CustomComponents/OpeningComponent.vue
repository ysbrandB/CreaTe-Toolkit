<script setup lang="ts">
const props = defineProps<{
    title: string;
}>();
</script>
<template>
    <details class="p-2 m-2 block [&_svg]:open:-rotate-180 border border-gray-200 rounded-lg flex-1">
        <summary class="flex cursor-pointer list-none items-center gap-4 bg-white dark:bg-gray-800 dark:text-gray-100">
            <svg class="rotate-0 transform text-blue-700 duration-300" fill="none"
                 height="20" width="20" stroke="currentColor" stroke-linecap="round"
                 stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
            <slot name="title">
                <div class="font-bold text-2xl">{{title}}</div>
            </slot>
        </summary>
        <div class="text-gray-600 dark:text-gray-400">
            <slot name="content"/>
        </div>
    </details>
</template>
