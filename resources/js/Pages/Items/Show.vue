<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";

const props = defineProps<{
    item: {
        id: number;
        public_id: string;
        title: string;
        description: string;
        wiring_instructions: string;
        pros: string;
        cons: string;
        hardware_considerations: string;
        software_considerations: string;
        example_code: string;
        photo_url: string;
        attributes: {
            id: number;
            title: string;
            color: string;
            attribute_type:{
                id: number;
                title: string;
                description: string;
                color: string;
            }
        }[];
    };
}>();

import QrcodeVue from 'qrcode.vue'
import OpeningCard from "@/CustomComponents/OpeningCard.vue";
import Pill from "@/CustomComponents/Pill.vue";
import HighlightJSVuePlugin from '@highlightjs/vue-plugin';
import 'highlight.js/lib/common';
const VHighlightjs = HighlightJSVuePlugin.component;
import 'highlight.js/styles/atom-one-dark.css';
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex !flex-row justify-between items-center">

                <div>
                    <h2 class="font-semibold text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight">
                        {{ item.title }}</h2>

                </div>
                <NavLink class="px-6" :href="route('items.edit', item)">
                    Edit item {{ item.id }}
                </NavLink>
                <NavLink class="px-6" :href="route('items.destroy', item)">
                    Delete item {{ item.id }}
                </NavLink>
            </div>
        </template>
        <section class="w-full h-full mx-auto">
            <div class="grid md:grid-cols-12">
                <div class="md:col-span-9 mt-4">

                    <OpeningCard title="Wiring">
                        <template #content>
                            <div class="float-right mb-2 text-black italic dark:text-gray-100">
                                <img :src="item.photo_url" width="400" class="rounded-lg" alt="wiring diagram">
                                Wiring diagram
                            </div>
                            {{ item.wiring_instructions }}
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Pros">
                        <template #content>
                            {{ item.pros }}
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Cons">
                        <template #content>
                            {{ item.cons }}
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Hardware considerations">
                        <template #content>
                            {{ item.cons }}
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Software considerations">
                        <template #content>
                            {{ item.cons }}
                        </template>
                    </OpeningCard>

                    <OpeningCard title="Example code">
                        <template #content>
                            <v-highlightjs autodetect :code="item.example_code" />
                        </template>
                    </OpeningCard>
                </div>
                <div class="md:col-span-3 md:pt-0 mt-4">
                    <div class="sticky top-8 max-w-7xl mx-auto sm:pr-6 lg:pr-4">
                        <div
                            class="p-8 bg-white dark:bg-gray-800 shadow-sm rounded-lg aspect-[63/88] overflow-hidden">
                            <div class="dark:text-gray-100 font-bold text-2xl mb-2">{{ item.title }}</div>
                            <img :src="item.photo_url" class="rounded-lg w-[75%] mx-auto" alt="item photo">
                            <div class="text-gray-600 text-sm my-2">{{ item.description }}</div>
                            <qrcode-vue class="float-right max-w-[80px] max-h-[80px] dark:bg-gray-800 dark:text-gray-100"
                                        :value="route('items.show', props.item.public_id)" level="M"
                                        render-as="svg"/>
                            <div class="flex flex-wrap">
                                <pill v-for="attribute in item.attributes" :key="attribute.id" :color="attribute.attribute_type.color">
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
