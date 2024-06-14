<script setup lang="ts">
import QrcodeVue from "qrcode.vue";
import Pill from "@/CustomComponents/Pill.vue";
import {Item} from "@/types";
const props = defineProps<{
    item: Item
}>();
</script>

<template>
    <div class="sticky top-8 sm:px-6 lg:px-4 relative flex-col flex items-center justify-items-center"
         id="wrapper" style="font-size: 80%">
        <div id="resizeCard"
             style="position:relative ; padding: 2em; width:23em; background-color: white; color: #1a202c; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); border-radius: 0.5em; aspect-ratio: 63/88; overflow: hidden;">
            <div style="font-weight: bold; font-size: 2em; margin-bottom: 0.3em;">
                {{ item.title }}
            </div>
            <img :src="item.photo_url"
                 style="border-radius: 0.5em; max-width: 15em; max-height: 15em; display: block; margin: 0 auto;"
                 alt="item photo">
            <div style="color: #718096; font-size: 0.875em; margin: 1em 0;">
                {{ item.card_description }}
            </div>
            <div class="parent-container">
                <div class="child-container">
                                    <span class="bottom-corner">
                                         <qrcode-vue
                                             style="float: right; max-width: 5em; max-height: 5em; background-color: #1a202c; color: #f7fafc; margin: 0.5em;"
                                             :value="route('items.show', props.item.public_id)"
                                             :margin="2"
                                             :size="500"
                                             level="Q"
                                             render-as="canvas"/>
                                    </span>
                    <pill v-for="attribute in item.attributes" :key="attribute.id"
                          :color="attribute.attribute_type?.color??''" :disable-classes="true"
                          style="font-size: 0.75em; font-weight: 500; padding: 0.125em 0.625em; margin: 0.25em; border-radius: 9999em; min-height: 0; text-wrap: nowrap">
                        {{ attribute.title }}
                    </pill>
                </div>
            </div>
        </div>
    </div>
</template>
