<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";

const props = defineProps<{
    item: any;
}>();

import {ref} from 'vue'
import QrcodeVue, {Level, RenderAs} from 'qrcode.vue'
import {marked} from "marked";

const value = ref(route('items.show', props.item.hashid))
const level = ref<Level>('M')
const renderAs = ref<RenderAs>('svg')
const markdownToHtml = marked(props.item.description)
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex !flex-row justify-between">
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">{{ item.name }}</h2>
                <div class="flex flex-row">
                    <qrcode-vue :value="value" :level="level" :render-as="renderAs"/>
                    <NavLink class="px-6" :href="route('items.edit', item)">
                        Edit item {{ item.id }}
                    </NavLink>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div v-html="markdownToHtml"></div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
