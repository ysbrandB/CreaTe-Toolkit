<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import Modal from "@/Components/Modal.vue";
import {ref} from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
const showModal = ref(true);
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="pt-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">Choice helper</div>
                </div>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-row justify-center items-center">
                    <div class="w-full p-8 mr-3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 text-2xl dark:text-gray-100">Actuating</div>
                    </div>
                    <div class="ml-3 w-full p-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 text-2xl dark:text-gray-100">Sensing</div>
                    </div>
                    <secondary-button @click="showModal=true">hallo allemaal 1</secondary-button>
                </div>
            </div>
        </div>

        <Modal :show="showModal">
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="showModal=false"> hallo allemaal dit wordt gecancelled </SecondaryButton>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
