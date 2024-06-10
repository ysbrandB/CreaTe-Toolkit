<script setup lang="ts">

import OpeningComponent from "@/CustomComponents/OpeningComponent.vue";

const props = defineProps<{
    title: string;
    open?: boolean;
}>();
</script>
<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-4 h-min">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg h-full flex flex-col">
           <OpeningComponent :title="title" :open="props.open">
                <template #content>
                    <div class="px-4 h-full">
                   <slot name="content"/>
                    </div>
                </template>
           </OpeningComponent>
        </div>
    </div>
</template>
