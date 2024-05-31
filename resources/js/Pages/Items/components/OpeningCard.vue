<script setup lang="ts">
import {defineProps} from 'vue';

const props = defineProps<{
    title: string;
    description: string;
}>();
</script>
<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-4">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <details class="p-4 [&_svg]:open:-rotate-180">
                <summary class="flex cursor-pointer list-none items-center gap-4">
                    <svg class="rotate-0 transform text-blue-700 transition-all duration-300" fill="none"
                         height="20" width="20" stroke="currentColor" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                    <div class="font-bold text-2xl mb-2">{{ props.title }}</div>
                </summary>
                <div class="px-8 text-gray-600">
                    <slot name="content"/>
                </div>
            </details>
        </div>
    </div>
</template>
