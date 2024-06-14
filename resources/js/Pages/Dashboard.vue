<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import Modal from "@/Components/Modal.vue";
import {ref} from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Card from "@/Components/Card.vue";
import OpeningCard from "@/CustomComponents/OpeningCard.vue";
</script>

<template>
    <Head title="About the kit"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="pt-12">
            <card>
                <div class="text-2xl font-bold p-6 text-gray-900 dark:text-gray-100">Sports toolkit</div>
            </card>
        </div>

        <opening-card title="">
            <template #content>
                <div class="text-2xl font-bold p-6 text-gray-900 dark:text-gray-100">Sports toolkit</div>
            </template>
        </opening-card>
    </AuthenticatedLayout>
</template>
