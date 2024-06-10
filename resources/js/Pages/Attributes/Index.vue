<script setup lang="ts">
import {AttributeType} from "@/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";
import OpeningCard from "@/CustomComponents/OpeningCard.vue";
import Card from "@/Components/Card.vue";

const props = defineProps<{
    attributeTypes: AttributeType[];
}>();
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Attributes</h2>
        </template>
        <div class="grid sm:grid-cols-1 md:grid-cols-4 lg:grid-cols-6 mt-4">
           <card class="w-full" v-for="attributeType in props.attributeTypes" :color="attributeType.color">
               <NavLink v-for="attribute in attributeType.attributes" class="w-full"
                        :href="route('attributes.edit', attribute.id)">
                   <div
                       class="w-full bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg flex !flex-row justify-between">
                       <div class="px-4 py-2">
                           <div class="font-semibold text-lg text-gray-800 dark:text-gray-200">{{
                                   attribute.title
                               }}
                           </div>
                       </div>
                   </div>
               </NavLink>
           </card>

        </div>
    </AuthenticatedLayout>
</template>
