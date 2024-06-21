<script setup lang="ts">
const props = withDefaults(defineProps<{
    color?: string,
    additionalClasses?: string
}>(), {
    additionalClasses: 'p-4 mx-2'
})
</script>
<template>
    <div class="w-full my-2">
        <div :class="[
            color?`bg-${color}-100`:'bg-white',
            color?`text-${color}-800`:'text-gray-800',
            color?`dark:bg-${color}-900`:'dark:bg-gray-800',
            props.additionalClasses?props.additionalClasses:'']"
             class="overflow-hidden shadow-sm rounded-lg h-full flex flex-col">
            <slot></slot>
        </div>
    </div>
</template>
