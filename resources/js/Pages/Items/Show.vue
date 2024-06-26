<script setup lang="ts">
import {Item} from "@/types";

const props = defineProps<{
    item: Item,
    initialSelectedItems: Item[],
}>();

import OpeningCard from "@/CustomComponents/OpeningCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";

import QrcodeVue from 'qrcode.vue'

import Pill from "@/CustomComponents/Pill.vue";
import HighlightJSVuePlugin from '@highlightjs/vue-plugin';
import 'highlight.js/lib/common';
import 'highlight.js/styles/atom-one-dark.css';
import hljs from 'highlight.js';
import {marked} from 'marked';
import ItemCard from "@/Pages/Items/ItemCard.vue";
import SelectedItemDropdown from "@/CustomComponents/SelectedItemDropdown.vue";
import {usePage} from "@inertiajs/vue3";
import {onMounted, ref, watch} from "vue";
import axios from "axios";

const renderMarkdown = (markdown: string | undefined) => marked(markdown ?? '');

const page = usePage();
const selectedItems = ref(new Set<Item>(props.initialSelectedItems ?? []));
onMounted(() => {
    watch(selectedItems.value, (selected) => {
        axios.post(route('graph.syncSelected'), {
            selected: Array.from(selected).map((item: Item) => item.id)
        });
    })
});

onMounted(()=>{
    document.querySelectorAll('pre code').forEach((block) => {
        hljs.highlightElement(block as HTMLElement);
    });
})
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
                <selected-item-dropdown :selected-items="selectedItems"/>
            </div>
        </template>
        <section class="w-full h-full mx-auto">
            <div class="grid grid-cols-3 md:grid-cols-12">
                <div class="col-span-3 md:col-span-9 mt-4">
                    <OpeningCard title="Description" :open="true">
                        <template #content v-if="item.description">
                            <article class="mx-auto mt-3 w-full prose" v-html="renderMarkdown(item.description)"></article>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Pros" v-if="item.pros">
                        <template #content>
                            <article class="mx-auto mt-3 prose" v-html="renderMarkdown(item.pros)"></article>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Cons" v-if="item.cons">
                        <template #content>
                            <article class="mx-auto mt-3 prose" v-html="renderMarkdown(item.cons)"></article>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Hardware considerations" v-if="item.hardware_considerations">
                        <template #content>
                            <article class="mx-auto mt-3 prose" v-html="renderMarkdown(item.hardware_considerations)"></article>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Software considerations" v-if="item.software_considerations">
                        <template #content>
                            <article class="mx-auto mt-3 prose" v-html="renderMarkdown(item.software_considerations)"></article>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Wiring" v-if="item.wiring_instructions">
                        <template #content>
                            <div class="float-right mb-2 text-black italic dark:text-gray-100" v-if="item.wiring_photo_url">
                                <img :src="item.wiring_photo_url" width="400" class="rounded-lg" alt="wiring diagram">
                                Wiring diagram
                            </div>
                            <article class="mx-auto mt-3 prose" v-html="renderMarkdown(item.wiring_instructions)"></article>
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Example code" v-if="item.example_code">
                        <template #content>
                            <article class="mx-auto mt-3 prose" v-html="renderMarkdown(item.example_code)"></article>
                        </template>
                    </OpeningCard>
                </div>
                <div class="col-span-3">
                    <div class="sticky top-[18px] mt-4 sm:mx-auto md:mx-0 md:max-w-full sm:max-w-[50%]">
                    <item-card :item="item"/>
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
