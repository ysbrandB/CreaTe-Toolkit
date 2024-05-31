<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";

defineProps<{
    items: any[];
}>();
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

        <div class="py-12" v-for="item in items">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex !flex-row justify-between">
                    <div class="p-6 text-gray-900 dark:text-gray-100">{{ item.name }}</div>
                    <img :src="item.photo_url" width="100" alt="item photo">

                    <div class="px-6 flex justify-center">
                        <NavLink class="px-6" :href="route('items.show', item.hashid)">
                            Show item
                        </NavLink>
                        <NavLink class="px-6" :href="route('items.edit', item.id)">
                            Edit item
                        </NavLink>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
