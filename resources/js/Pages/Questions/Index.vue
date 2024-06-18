<script setup lang="ts">
import {Question} from "@/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";

const props = defineProps<{
    questions: Question[];
}>();
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Items</h2>
                <div class="flex flex-row gap-2">
                    <NavLink :href="route('questions.create')">
                        New Question
                    </NavLink>
                </div>
            </div>
        </template>
        <div class="grid sm:grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-4">
            <NavLink v-for="question in questions" class="px-6"
                     :href="route('questions.edit', question.id)">
                <div
                    class="w-full bg-blue-100 dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg flex !flex-row justify-between">
                    <div class="px-4 py-2">
                        <div class="font-semibold text-lg text-gray-800 dark:text-gray-200">{{
                                question.title
                            }}
                        </div>
                    </div>
                </div>
            </NavLink>
        </div>
    </AuthenticatedLayout>
</template>
