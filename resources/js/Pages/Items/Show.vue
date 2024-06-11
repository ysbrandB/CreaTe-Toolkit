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

const renderMarkdown = (markdown: string) => marked(markdown);

</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex !flex-row justify-between items-center">

                <div>
                    <h2 class="font-semibold text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight">
                        {{ item.title }}</h2>
                <div class="text-gray-600 text-sm mt-4">{{ item.description }}</div>
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

                    <OpeningCard title="Wiring">
                        <template #content>
                            <div class="float-right mb-2 text-black italic dark:text-gray-100">
                                <img :src="item.wiring_photo_url" width="400" class="rounded-lg" alt="wiring diagram">
                                Wiring diagram
                            </div>
                            <div v-html="renderMarkdown(item.wiring_instructions)"></div>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Pros">
                        <template #content>
                            <div v-html="renderMarkdown(item.pros)"></div>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Cons">
                        <template #content>
                            <div v-html="renderMarkdown(item.cons)"></div>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Hardware considerations">
                        <template #content>
                            <div v-html="renderMarkdown(item.hardware_considerations)"></div>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Software considerations">
                        <template #content>
                            <div v-html="renderMarkdown(item.software_considerations)"></div>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Example code">
                        <template #content>
                            <v-highlightjs autodetect :code="item.example_code" class="w-full sm:text-sm"/>
                        </template>
                    </OpeningCard>
                </div>
                <div class="col-span-3 md:pt-0 mt-4">
                    <div class="sticky top-8 max-w-7xl mx-auto sm:pr-6 lg:pr-4" id="wrapper">
                        <div
                            id="resizeCard"
                            class="p-8 bg-white dark:bg-gray-800 shadow-sm rounded-lg aspect-[63/88] overflow-hidden">
                            <div class="dark:text-gray-100 font-bold text-2xl mb-2">{{ item.title }}</div>
                            <img :src="item.photo_url" class="rounded-lg w-[75%] mx-auto" alt="item photo">
                            <div class="text-gray-600 text-sm my-2">{{ item.card_description }}</div>
                            <qrcode-vue
                                class="float-right max-w-[80px] max-h-[80px] dark:bg-gray-800 dark:text-gray-100 m-2"
                                :value="route('items.show', props.item.public_id)"
                                :margin="2"
                                :size="500"
                                level="Q"
                                render-as="canvas"/>
                            <div class="flex flex-wrap">
                                <pill v-for="attribute in item.attributes" :key="attribute.id"
                                      :color="attribute.attribute_type?.color??''">
                                    {{ attribute.title }}
                                </pill>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
