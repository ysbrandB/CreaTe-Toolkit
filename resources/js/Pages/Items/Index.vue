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

        <section class="w-full h-full mx-auto border-2 border-red-600">
            <div class="grid md:grid-cols-12 gap-4">
                <div class="md:col-span-3 md:pt-0 border-2 border-blue-600">
                    <details class="border-2 border-dashed border-stone-500 p-4 [&_svg]:open:-rotate-180">
                        <summary class="flex cursor-pointer list-none items-center gap-4">
                            <svg class="rotate-0 transform text-blue-700 transition-all duration-300" fill="none"
                                 height="20" width="20" stroke="currentColor" stroke-linecap="round"
                                 stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                            I have keys but no doors.
                        </summary>
                        A keyboard.
                    </details>
                </div>
                <div class="md:col-span-9 border-2 border-green-600">
                    <div class="grid grid-cols-4 gap-4 mt-4">
                        <NavLink v-for="item in items" class="px-6" :href="route('items.show', item.hashid)">
                            <div
                                class="w-full bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex !flex-row justify-between">

                                <img :src="item.photo_url" width="100" height="100" alt="item photo">
                                <div class="px-2">{{ item.name }}</div>
                            </div>
                        </NavLink>
                    </div>
                </div>
            </div>
        </section>

    </AuthenticatedLayout>
</template>
