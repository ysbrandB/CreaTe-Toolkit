<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";
import {router} from "@inertiajs/vue3";
import {AttributeType, Item, Question} from "@/types";
import Card from "@/Components/Card.vue";
import AttributeFilter from "@/CustomComponents/AttributeFilter.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {computed, Ref, ref, watch} from "vue";
import axios from "axios";
import ItemCard from "@/Pages/Items/ItemCard.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectedItemDropdown from "@/CustomComponents/SelectedItemDropdown.vue";

const props = defineProps<{
    items: Item[],
    initialSelectedItems?: Item[],
    attributeTypes: AttributeType[],
    initialFilters: Record<number, number[]>,
    questions: Question[],
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
    if (choiceIndex.value < props.questions.length - 1) {
        choiceIndex.value++;
        return;
    }
    resetChoice();
}

const choiceOpen = ref(false);
const choiceIndex = ref(0);
const question = computed(() => props.questions[choiceIndex.value]);
const filter: Ref<typeof AttributeFilter | null> = ref(null);

</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Items</h2>
                <div class="flex flex-row gap-2 justify-end">
                    <div v-if="$page.props.auth.user">
                        <NavLink :href="route('items.create')">
                            New item
                        </NavLink>
                    </div>
                    <secondary-button class="bg-green-100"
                                      @click="resetChoice(); filter?.reset(); choiceOpen=true">
                        Choice helper
                    </secondary-button>
                    <selected-item-dropdown :selected-items="selectedItems"/>
                </div>
            </div>
        </template>

        <section class="w-full h-full mx-auto">
            <div class="grid grid-cols-3 md:grid-cols-6 lg:grid-cols-12  gap-4 max-w-[90%] mx-auto">
                <div class="col-span-3 md:pt-0 mt-2">
                    <card class="">
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
                    <div class="grid lg:grid-cols-4 grid-cols-2 gap-4 mt-4">
                        <item-card :item="item" v-for="item in items">
                            <template #qr>
                                <button v-if="selectedItems.has(item)" @click="selectedItems.delete(item)"
                                        class="bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                        style="border-radius:.5em;font-size: 1.1em; width:3.5em; height:3em; align-self: flex-end;">
                                    -
                                </button>
                                <button v-else @click="selectedItems.add(item)"
                                        class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                                        style="border-radius:.5em;font-size: 1.1em; width:3.5em; height:3em; align-self: flex-end;">
                                    +
                                </button>
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
                        <primary-button @click="questionAnswered(answer.attributes.map(attr=>attr.id))">
                            {{ answer.text }}
                        </primary-button>
                    </div>
                </card>
            </div>
        </modal>
    </AuthenticatedLayout>
</template>
