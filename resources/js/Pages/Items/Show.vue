<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";

const props = defineProps<{
    item: any;
}>();

import {ref} from 'vue'
import QrcodeVue, {Level, RenderAs} from 'qrcode.vue'
import {marked} from "marked";
import OpeningCard from "@/Pages/Items/components/OpeningCard.vue";

const value = ref(route('items.show', props.item.hashid))
const level = ref<Level>('M')
const renderAs = ref<RenderAs>('svg')
const description = marked(props.item.description)
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex !flex-row justify-between items-center">

                <div>
                    <h2 class="font-semibold text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight">{{ item.name }}</h2>
                    <div class="text-gray-600">{{ item.description }}</div>
                </div>
                <div class="flex flex-row">
                    <qrcode-vue :value="value" :level="level" :render-as="renderAs"/>
                    <NavLink class="px-6" :href="route('items.edit', item)">
                        Edit item {{ item.id }}
                    </NavLink>
                </div>
            </div>
        </template>
        <section class="w-full h-full mx-auto border-2 border-red-600">
            <div class="grid md:grid-cols-12 gap-4">
                <div class="md:col-span-9">

                    <OpeningCard title="Wiring">
                        <template #content>
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
                <div class="md:col-span-3 md:pt-0 border-2 border-blue-600">
                    <div class="sticky top-8 max-w-7xl mx-auto sm:px-6 lg:px-4">
                        <div class="p-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="font-bold text-2xl">{{ item.name }}</div>
                            <img :src="item.photo_url" class="rounded-lg" alt="item photo">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
