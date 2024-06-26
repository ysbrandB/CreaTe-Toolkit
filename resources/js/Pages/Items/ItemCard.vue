<script setup lang="ts">
import QrcodeVue from "qrcode.vue";
import Pill from "@/CustomComponents/Pill.vue";
import {Item} from "@/types";
import {Link} from "@inertiajs/vue3";

const props = defineProps<{
    item: Item
}>();
</script>

<template>
    <div style="container-type: inline-size;">
        <div
            style="font-size: 4cqw; position:relative; margin-inline: auto; padding: 2em; width:23em; background-color: white; color: #1a202c; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); border-radius: 0.5em; aspect-ratio: 63/88; overflow: hidden;">
            <Link :href="route('items.show',{public_id: item.public_id})">
                <div
                    style="overflow: hidden; text-wrap: nowrap; text-overflow: ellipsis; font-weight: bold; max-height:1.5em; max-width: 100%; font-size: 2em; margin-bottom: 0.3em;">
                    {{ item.title }}
                </div>
                <img :src="item.photo_url"
                     style="border-radius: 0.5em; height:11em; max-height: 11em; max-width: 15em; display: block; margin: 0 auto;"
                     alt="item photo">
            </Link>
            <div style="display: flex; flex-direction: column; height:13.2em; margin-top: .3em; gap:.3em">
                <div style="color: #2D3748; font-size: 0.92em; overflow: hidden; flex-grow: 1">
                    {{ item.card_description }}
                </div>
                <div style="display: flex; flex-wrap:wrap;">
                    <pill v-for="attribute in item.attributes?.slice(6)" :key="attribute.id"
                          :color="attribute.attribute_type?.color??''" :disable-classes="true"
                          style="display: inline-block;font-size: 0.65em; font-weight: 500; padding: 0.125em 0.625em; margin: 0.1em; border-radius: 9999em; min-height: 0; height: fit-content">
                        {{ attribute.title }}
                    </pill>

                    <div style="display: flex; flex-direction: row; gap: 0.1em; width: 100%">
                        <div style="display: inline-block; max-width:13em">
                            <pill v-for="attribute in item.attributes?.slice(0,6)" :key="attribute.id"
                                  :color="attribute.attribute_type?.color??''" :disable-classes="true"
                                  style="text-wrap: nowrap;display: inline-block;font-size: 0.65em; font-weight: 500; padding: 0.125em 0.625em; margin: 0.1em; border-radius: 9999em; min-height: 0; height: fit-content">
                                {{ attribute.title }}
                            </pill>
                        </div>
                        <div style="margin-top:auto; margin-left: auto; max-width: 4.5em; max-height: 4.5em;">
                            <slot name="qr">
                                <qrcode-vue
                                    style=" background-color: #1a202c; color: #f7fafc; width: 100%; height: 100%;"
                                    :value="route('items.show', props.item.public_id)"
                                    :margin="2"
                                    :size="500"
                                    level="Q"
                                    render-as="canvas"/>
                            </slot>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
