<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";
import OpeningCard from "@/Pages/Items/components/OpeningCard.vue";
import OpeningComponent from "@/Pages/Items/components/OpeningComponent.vue";
import {computed, ref} from "vue";

defineProps<{
    items: {
        public_id: string;
        title: string;
        photo_url: string;
        wiring_photo_url: string;
    }[],
    attributeTypes: {
        id: number;
        title: string;
    }[],
}>();

const checkedAttributes = ref([])

const newChecked=computed(()=>{
    console.log(checkedAttributes.value);
    return checkedAttributes.value
});
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Items</h2>
                <NavLink :href="route('items.create')" :active="route().current('items.create')">
                    New item
                </NavLink>
            </div>
        </template>

        <section class="w-full h-full mx-auto">
            <div class="grid md:grid-cols-12 gap-4">
                <div class="md:col-span-3 md:pt-0">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-4">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                            <div class="w-full text-2xl text-center font-semibold mt-4">Filters</div>
                            <OpeningComponent v-for="attributeType in attributeTypes" :title="attributeType.title">
                                <template #content>
                                    <div v-for="attribute in attributeType.attributes" :key="attribute.id" class="pl-10">
                                        <input type="checkbox" :id="attribute.id" :value="attribute.id" v-model="checkedAttributes">
                                        <label :for="attribute.id">{{ attribute.title }}</label>
                                    </div>
                                </template>
                            </OpeningComponent>
                        </div>
                    </div>
                    {{newChecked}}
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
