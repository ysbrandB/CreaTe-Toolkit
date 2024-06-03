<script setup lang="ts">
const props = defineProps<{
    title: string;
}>();
</script>
<template>
    <details class="p-4 [&_svg]:open:-rotate-180">
        <summary class="flex cursor-pointer list-none items-center gap-4">
            <svg class="rotate-0 transform text-blue-700 transition-all duration-300" fill="none"
                 height="20" width="20" stroke="currentColor" stroke-linecap="round"
                 stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
            <div class="font-bold text-2xl">{{ props.title }}</div>
        </summary>
        <div class="text-gray-600">
            <slot name="content"/>
        </div>
    </details>
</template>
