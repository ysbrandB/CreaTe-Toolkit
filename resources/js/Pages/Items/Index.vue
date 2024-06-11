<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";
import {router} from "@inertiajs/vue3";
import {AttributeType, Item} from "@/types";
import Card from "@/Components/Card.vue";
import AttributeFilter from "@/CustomComponents/AttributeFilter.vue";

const props = defineProps<{
    items: Item[],
    attributeTypes: AttributeType[],
    filters: string
}>();

const reloadWithFilters = (filters: object) => {
    router.reload({
        data: {
            filters: JSON.stringify(filters)
        }
    })
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Items</h2>
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
                                title="filters"
                                @update:checked-attributes="reloadWithFilters"
                                :attribute-types="attributeTypes"
                                :checked-attributes="JSON.parse(props.filters) ?? {}"/>
                        </div>
                    </card>
                </div>
                <div class="lg:col-span-9 col-span-3">
                    <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-4 mt-4">
                        <NavLink v-for="item in items" class="px-6" :href="route('items.show', item.public_id)">
                            <div
                                class="w-full bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg flex !flex-row justify-between">

                                <img :src="item.photo_url" class="w-[50%]" alt="item photo">
                                <div class="px-2">{{ item.title }}</div>
                            </div>
                        </NavLink>
                    </div>
                </div>
            </div>
        </section>

    </AuthenticatedLayout>
</template>
