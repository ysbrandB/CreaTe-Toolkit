<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";
import OpeningComponent from "@/CustomComponents/OpeningComponent.vue";
import {nextTick, ref} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps<{
    items: {
        public_id: string;
        title: string;
        photo_url: string;
        wiring_photo_url: string;
    }[],
    attributeTypes: {
        id: number;
        title: string;
        attributes: {
            id: number;
            title: string;
        }[]
    }[],
}>();
const checkedAttributes = ref(new Map<number, number[]>());

const check = (typeId: number, attributeId: number, checked: boolean) => nextTick(() => {
    if (checked) {
        checkedAttributes.value.set(typeId, [...(checkedAttributes.value.get(typeId) || []), attributeId])
    } else {
        checkedAttributes.value.set(typeId, (checkedAttributes.value.get(typeId) || []).filter(id => id !== attributeId))
    }
    checkedAttributes.value = new Map([...checkedAttributes.value].filter(([_, value]) => value.length > 0))

    router.reload({
        data: {
            filters: Object.fromEntries(checkedAttributes.value)
        }
    })
});

console.log(route('attribute_types.create'));
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Items</h2>
                <div class="flex flex-row gap-2">
                    <NavLink :href="route('items.create')">
                        New item
                    </NavLink>
                    <NavLink :href="route('attribute_types.create')">
                        New Attribute Type
                    </NavLink>
                </div>
            </div>
        </template>

        <section class="w-full h-full mx-auto">
            <div class="grid md:grid-cols-12 gap-4">
                <div class="md:col-span-3 md:pt-0">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-4">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                            <div class="w-full text-2xl text-center font-semibold mt-4">Filters</div>
                            <OpeningComponent v-for="attributeType in attributeTypes" :title="attributeType.title">
                                <template #title>
                                    <div class="font-bold text-2xl p-3 rounded rounded-lg"
                                         :class="`bg-${attributeType.color}-100`">{{ attributeType.title }}
                                    </div>
                                </template>
                                <template #content>
                                    <ul class="w-full mt-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <li v-for="attribute in attributeType.attributes" :key="attribute.id"
                                            class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                            <div class="flex items-center ps-3">
                                                <input type="checkbox"
                                                       @change="check(attributeType.id, attribute.id, ($event as HTMLInputElement).target.checked)"
                                                       :id="attribute.id.toString()" :value="attribute.id"
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                <label :for="attribute.id.toString()"
                                                       class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{
                                                        attribute.title
                                                    }}</label>
                                            </div>
                                        </li>
                                    </ul>
                                </template>
                            </OpeningComponent>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-9">
                    <div class="grid grid-cols-4 gap-4 mt-4">
                        <NavLink v-for="item in items" class="px-6" :href="route('items.show', item.public_id)">
                            <div
                                class="w-full bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex !flex-row justify-between">

                                <img :src="item.photo_url" width="100" height="100" alt="item photo">
                                <div class="px-2">{{ item.title }}</div>
                            </div>
                        </NavLink>
                    </div>
                </div>
            </div>
        </section>

    </AuthenticatedLayout>
</template>
