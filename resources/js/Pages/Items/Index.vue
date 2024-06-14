<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";
import {router} from "@inertiajs/vue3";
import {AttributeType, Item} from "@/types";
import Card from "@/Components/Card.vue";
import AttributeFilter from "@/CustomComponents/AttributeFilter.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {computed, Ref, ref, UnwrapRef, watch} from "vue";
import DangerButton from "@/Components/DangerButton.vue";
import Pill from "@/CustomComponents/Pill.vue";
import axios from "axios";
import se from "../../../../public/build/assets/Index-D-tLGpWy";

const props = defineProps<{
    items: Item[],
    oldSelectedItems?: Item[],
    attributeTypes: AttributeType[],
    filters: string
}>();

const selectedItems = ref(new Set<Item>());
const reloadWithFilters = (filters: object) => {
    router.reload({
        data: {
            filters: JSON.stringify(filters),
        }
    })
};

watch(selectedItems.value, (selected) => {
    console.log(`selectedItems is ${Array.from(selected).map((item: Item) => item.id)}`)
    axios.post(route('graph.syncSelected'), {
        selected: Array.from(selected).map((item: Item) => item.id)
    });
})
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Items</h2>
                <div class="flex flex-row justify-end">
                    <primary-button
                        @click="router.get(route('graph.index'), {'selected': Array.from(selectedItems).map((item: Item) => item.id)})">
                        See overview
                    </primary-button>
                    <pill :color="''" v-for="item in selectedItems" class="cursor-pointer bg-red-100"
                          @click="selectedItems.delete(item)">
                        {{ item.title }} <span class="ms-2 text-red-600">x</span>
                    </pill>
                </div>
                <div class="flex flex-row gap-2" v-if="$page.props.auth.user">
                    <NavLink :href="route('items.create')">
                        New item
                    </NavLink>
                </div>
            </div>
        </template>

        <section class="w-full h-full mx-auto">
            <div class="grid grid-cols-3 md:grid-cols-6 lg:grid-cols-12  gap-4">
                <div class="col-span-3 md:pt-0">
                    <card class="mt-4">
                        <div class="w-full text-center">
                            <attribute-filter
                                @update:checked-attributes="reloadWithFilters"
                                :attribute-types="attributeTypes"
                                :checked-attributes="JSON.parse(props.filters) ?? {}"/>
                        </div>
                    </card>
                </div>
                <div class="lg:col-span-9 col-span-3">
                    <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-4 mt-4">
                        <div v-for="item in items"
                             class="w-full bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg flex !flex-row">
                            <NavLink class="w-[50%]" style="margin: 0; padding: 0"
                                     :href="route('items.show', item.public_id)">
                                <img :src="item.photo_url" class="w-full h-full object-cover object-center"
                                     alt="item photo">
                            </NavLink>
                            <div
                                class="w-[50%] h-full text-center flex flex-col justify-between">
                                <NavLink class="px-0 pt-0 w-full mt-2" :href="route('items.show', item.public_id)">
                                    <div class="w-full h-full">{{ item.title }}</div>
                                </NavLink>
                                <danger-button v-if="selectedItems.has(item)" @click="selectedItems.delete(item)"
                                               class="w-min text-sm px-0 py-0 mb-2 mr-2 self-end"> -
                                </danger-button>
                                <secondary-button v-else @click="selectedItems.add(item)"
                                                  class="w-min text-sm px-0 py-0  mb-2 mr-2 self-end"> +
                                </secondary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </AuthenticatedLayout>
</template>
