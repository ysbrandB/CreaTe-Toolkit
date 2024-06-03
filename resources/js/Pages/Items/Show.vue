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
        }[];
    };
}>();

import {ref} from 'vue'
import QrcodeVue, {Level, RenderAs} from 'qrcode.vue'
import {marked} from "marked";
import OpeningCard from "@/Pages/Items/components/OpeningCard.vue";

const value = ref(route('items.show', props.item.public_id))
const description = marked(props.item.description)
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
            </div>
        </template>
        <section class="w-full h-full mx-auto">
            <div class="grid md:grid-cols-12 gap-4">
                <div class="md:col-span-9 mt-4">

                    <OpeningCard title="Wiring">
                        <template #content>
                            <div class="float-right mb-4 text-black italic">
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
                            {{ item.example_code }}
                        </template>
                    </OpeningCard>
                </div>
                <div class="md:col-span-3 md:pt-0 mt-4">
                    <div class="sticky top-8 max-w-7xl mx-auto sm:px-6 lg:px-4">
                        <div
                            class="p-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg aspect-[63/88]">
                            <div class="font-bold text-2xl">{{ item.title }}</div>
                            <img :src="item.photo_url" class="rounded-lg" alt="item photo">
                            <div class="text-gray-600 text-sm mt-2">{{ item.description }}</div>
                            <div v-for="attribute in item.attributes" :key="attribute.id" class="pl-10">
                                <input type="checkbox" :id="attribute.id" :value="attribute.id" v-model="checkedAttributes">
                                <label :for="attribute.id">{{ attribute.title }}</label>
                            </div>
                            <qrcode-vue class="float-right float-end max-w-[80px]" :value="value" level="M" render-as="svg"/>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
