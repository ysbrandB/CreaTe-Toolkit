<template>
    <VMenu>
        <button type="button" class="text-center h-full border-dashed border-b-2 border-gray-500">{{
                selectedItems.size
            }}
            items selected
        </button>
        <template #popper>
            <div class="p-4 w-full flex flex-col">
                <div class="max-w-[40vw] md:max-w-[20vw] mx-2" v-if="selectedItems.size<=0">
                    <slot name="empty">
                        <p> Select some items to use in
                            your system using the + icon on the cards!</p>
                    </slot>
                </div>
                <div v-else>
                    <div class="p-1 flex justify-between" v-for="item in selectedItems">
                        <span> {{ item.title }} </span>
                        <button type="button" @click="selectedItems.delete(item)" class="mx-2 text-red-600">
                            x
                        </button>
                    </div>
                </div>
                <slot name="button">
                    <primary-button
                        class="mt-2 w-fit self-end"
                        @click="router.get(route('graph.index'))">
                        See item overview
                    </primary-button>
                </slot>
            </div>
        </template>
    </VMenu>
</template>
<script setup lang="ts">
import {router} from "@inertiajs/vue3";
import {Menu as VMenu} from 'floating-vue'
import 'floating-vue/dist/style.css'
import {Item} from "@/types";
import {defineProps, watch} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps<{
    selectedItems: Set<Item>
}>()
console.log(props.selectedItems)
watch(props.selectedItems, (selected) => {
    console.log('selected', selected)
})
</script>


