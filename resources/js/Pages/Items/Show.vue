<script setup lang="ts">
import {Item} from "@/types";

const props = defineProps<{
    item: Item
}>();

import OpeningCard from "@/CustomComponents/OpeningCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";

import QrcodeVue from 'qrcode.vue'

import Pill from "@/CustomComponents/Pill.vue";
import HighlightJSVuePlugin from '@highlightjs/vue-plugin';
import 'highlight.js/lib/common';

const VHighlightjs = HighlightJSVuePlugin.component;
import 'highlight.js/styles/atom-one-dark.css';

import {marked} from 'marked';

const renderMarkdown = (markdown: string | undefined) => marked(markdown ?? '');

</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex !flex-row justify-between items-center">

                <div>
                    <h2 class="font-semibold text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight">
                        {{ item.title }}</h2>
                </div>
                <div v-if="$page.props.auth.user">
                    <NavLink class="px-6" :href="route('items.edit', item.id)">
                        Edit item {{ item.id }}
                    </NavLink>
                    <NavLink class="px-6" :href="route('items.destroy', item.id)" method="delete">
                        Delete item {{ item.id }}
                    </NavLink>
                </div>
            </div>
        </template>
        <section class="w-full h-full mx-auto">
            <div class="grid grid-cols-3 md:grid-cols-12">
                <div class="col-span-3 md:col-span-9 mt-4">
                    <OpeningCard title="Description" :open="true">
                        <template #content v-if="item.description">
                            <div v-html="renderMarkdown(item.description)"></div>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Pros" v-if="item.pros">
                        <template #content>
                            <div v-html="renderMarkdown(item.pros)"></div>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Cons" v-if="item.cons">
                        <template #content>
                            <div v-html="renderMarkdown(item.cons)"></div>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Hardware considerations" v-if="item.hardware_considerations">
                        <template #content>
                            <div v-html="renderMarkdown(item.hardware_considerations)"></div>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Software considerations" v-if="item.software_considerations">
                        <template #content>
                            <div v-html="renderMarkdown(item.software_considerations)"></div>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Wiring" v-if="item.wiring_instructions">
                        <template #content>
                            <div class="float-right mb-2 text-black italic dark:text-gray-100">
                                <img :src="item.wiring_photo_url" width="400" class="rounded-lg" alt="wiring diagram">
                                Wiring diagram
                            </div>
                            <div v-html="renderMarkdown(item.wiring_instructions)"></div>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Example code" v-if="item.example_code">
                        <template #content>
                            <v-highlightjs autodetect :code="item.example_code??''" class="w-full sm:text-sm"/>
                        </template>
                    </OpeningCard>
                </div>
                <div class="col-span-3 md:pt-0 mt-4">
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
                                          style="font-size: 0.75em; font-weight: 500; padding: 0.125em 0.625em; margin: 0.25em; border-radius: 9999em; min-height: 0; text-wrap: none">
                                        {{ attribute.title }}
                                    </pill>
                                    <pill v-for="attribute in item.attributes" :key="attribute.id"
                                          :color="attribute.attribute_type?.color??''" :disable-classes="true"
                                          style="font-size: 0.75em; font-weight: 500; padding: 0.125em 0.625em; margin: 0.25em; border-radius: 9999em; min-height: 0; text-wrap: none">
                                        {{ attribute.title }}
                                    </pill>
                                    <pill v-for="attribute in item.attributes" :key="attribute.id"
                                          :color="attribute.attribute_type?.color??''" :disable-classes="true"
                                          style="font-size: 0.75em; font-weight: 500; padding: 0.125em 0.625em; margin: 0.25em; border-radius: 9999em; min-height: 0; text-wrap: none">
                                        {{ attribute.title }}
                                    </pill>
                                    <pill v-for="attribute in item.attributes" :key="attribute.id"
                                          :color="attribute.attribute_type?.color??''" :disable-classes="true"
                                          style="font-size: 0.75em; font-weight: 500; padding: 0.125em 0.625em; margin: 0.25em; border-radius: 9999em; min-height: 0; text-wrap: none">
                                        {{ attribute.title }}
                                    </pill>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>

<style scoped>
code {
    border-radius: 20px;
}

.parent-container {
    display: flex;
    overflow: hidden;
}

.bottom-corner {
    float: right;
    height: 100%;
    display: flex;
    align-items: flex-end;
    shape-outside: inset(calc(100% - 5em) 0 0);
}
</style>
