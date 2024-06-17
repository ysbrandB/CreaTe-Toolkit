<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";
import {router} from "@inertiajs/vue3";
import {Attribute, AttributeType, Item} from "@/types";
import Card from "@/Components/Card.vue";
import AttributeFilter from "@/CustomComponents/AttributeFilter.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {computed, onMounted, Ref, ref, watch} from "vue";
import DangerButton from "@/Components/DangerButton.vue";
import Pill from "@/CustomComponents/Pill.vue";
import axios from "axios";
import ItemCard from "@/Pages/Items/ItemCard.vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps<{
    items: Item[],
    initialSelectedItems?: Item[],
    attributeTypes: AttributeType[],
    initialFilters: Record<number, number[]>,
    questions: {
        questions: {
            title: string,
            description: string,
            answers: {
                text: string,
                filters: number[]
            }[]
        }[],
    },
}>();
const selectedItems = ref(new Set<Item>(props.initialSelectedItems ?? []));
const reloadWithFilters = (filters: Record<number, number[]>) => {
    router.reload({
        data: {
            filters: filters
        },
        only: ['items']
    })
};

watch(selectedItems.value, (selected) => {
    axios.post(route('graph.syncSelected'), {
        selected: Array.from(selected).map((item: Item) => item.id)
    });
})

const resetChoice = () => {
    choiceOpen.value = false;
    choiceIndex.value = 0;
}

const questionAnswered = (filters: number[]) => {
    filter.value?.addFiltersByAttributeIds(filters);
    if (choiceIndex.value < props.questions.questions.length - 1) {
        choiceIndex.value++;
        return;
    }
    resetChoice();
}

const choiceOpen = ref(false);
const choiceIndex = ref(0);
const question = computed(() => props.questions.questions[choiceIndex.value]);
const filter: Ref<typeof AttributeFilter | null> = ref(null);

onMounted(() => console.log(filter.value))
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Items</h2>
                <div class="flex flex-row gap-2 justify-end">
                    <div class="flex flex-row gap-2" v-if="$page.props.auth.user">
                        <NavLink :href="route('items.create')">
                            New item
                        </NavLink>
                    </div>
                    <primary-button
                        @click="router.get(route('graph.index'))">
                        See overview
                    </primary-button>
                    <primary-button
                        @click="resetChoice(); filter?.reset(); choiceOpen=true">
                        Choice helper
                    </primary-button>
                    <pill :color="''" v-for="item in selectedItems" class="cursor-pointer bg-red-100"
                          @click="selectedItems.delete(item)">
                        {{ item.title }} <span class="ms-2 text-red-600">x</span>
                    </pill>
                </div>
            </div>
        </template>

        <section class="w-full h-full mx-auto">
            <div class="grid grid-cols-3 md:grid-cols-6 lg:grid-cols-12  gap-4">
                <div class="col-span-3 md:pt-0">
                    <card class="mt-4">
                        <div class="w-full text-center">
                            <attribute-filter
                                ref="filter"
                                @update="reloadWithFilters"
                                :attribute-types="attributeTypes"
                                :initial-filters="initialFilters"/>
                        </div>
                    </card>
                </div>
                <div class="lg:col-span-9 col-span-3">
                    <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-4 mt-4">
                        <item-card :item="item" v-for="item in items">
                            <template #qr>
                                <danger-button v-if="selectedItems.has(item)" @click="selectedItems.delete(item)"
                                               class="w-min text-sm px-0 py-0 mb-2 mr-2 self-end"> -
                                </danger-button>
                                <secondary-button v-else @click="selectedItems.add(item)"
                                                  class="w-min text-sm px-0 py-0  mb-2 mr-2 self-end"> +
                                </secondary-button>
                            </template>
                        </item-card>
                    </div>
                </div>
            </div>
        </section>
        <modal :show="choiceOpen" @close="resetChoice">
            <div class="w-full bg-blue-100 p-8">
                <div class="text-black text-2xl font-bold">Choice helper</div>
                <div class="text-gray-900 text-lg mt-1">
                    Answer the questions to see which items fit your needs!
                </div>
                <card class="mt-4">
                    <div class="text-gray-800 dark:text-gray-200 leading-tight">{{ question.description }}</div>
                    <div class="w-full mt-2 mx-auto" v-for="answer in question.answers">
                        <primary-button @click="questionAnswered(answer.filters)">
                            {{ answer.text }}
                        </primary-button>
                    </div>
                </card>
            </div>
        </modal>

    </AuthenticatedLayout>
</template>
