<script setup lang="ts">

import OpeningComponent from "@/Pages/Items/components/OpeningComponent.vue";

const props = defineProps<{
    title: string;
}>();
</script>
<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-4">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
           <OpeningComponent :title="title">
                <template #content>
                    <div class="px-4">
                   <slot name="content"/>
                    </div>
                </template>
           </OpeningComponent>
        </div>
    </div>
</template>
