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
import ItemCard from "@/Pages/Items/ItemCard.vue";

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
                    <item-card :item="item"/>
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
