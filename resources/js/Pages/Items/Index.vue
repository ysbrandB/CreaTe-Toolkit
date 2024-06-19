<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NavLink from "@/Components/NavLink.vue";
import {router, usePage} from "@inertiajs/vue3";
import {AttributeType, Item, Question} from "@/types";
import Card from "@/Components/Card.vue";
import AttributeFilter from "@/CustomComponents/AttributeFilter.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {computed, onMounted, Ref, ref, watch} from "vue";
import axios from "axios";
import ItemCard from "@/Pages/Items/ItemCard.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectedItemDropdown from "@/CustomComponents/SelectedItemDropdown.vue";

const props = defineProps<{
    items: Item[],
    attributeTypes: AttributeType[],
    initialFilters: Record<number, number[]>,
    questions: Question[],
    initialSelectedItems: Item[],
    explainer: boolean,
}>();
const page = usePage();
const selectedItems = ref(new Set<Item>(props.initialSelectedItems ?? []));
const reloadWithFilters = (filters: Record<number, number[]>) => {
    router.reload({
        data: {
            filters: filters
        },
        only: ['items']
    })
};

onMounted(() => {
    watch(selectedItems.value, (selected) => {
        axios.post(route('graph.syncSelected'), {
            selected: Array.from(selected).map((item: Item) => item.id)
        });
    })
});


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
const explainOpen = ref(props.explainer);
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
                    <secondary-button @click="explainOpen=true">
                        Explainer
                    </secondary-button>
                    <primary-button
                        @click="resetChoice(); filter?.reset(); choiceOpen=true">
                        Choice helper
                    </primary-button>
                    <selected-item-dropdown class="mx-2" :selected-items="selectedItems"/>
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
                                        class="bg-grey-100 dark:bg-gray-800 border border-grey-300 dark:border-gray-500 font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-emerald-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
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
            <div class="w-full bg-emerald-100 p-8">
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

        <modal :show="explainOpen" @close="explainOpen=false">
            <div class="w-full bg-blue-100 p-8">
                <div class="text-black text-2xl font-bold">Choice helper</div>
                <div class="text-gray-900 text-lg mt-1">
                    This tool helps you find the right sensors and actuators for your project!
                </div>
                <card class="mt-4">
                    <div class="font-bold text-lg text-gray-900 dark:text-gray-100">
                        What to do?
                    </div>
                    <div class="text-gray-800 dark:text-gray-200">
                        You can add items to your selection by clicking on the plus button.<br>
                        You can see your selected items in the top right!
                        Click on an item card to learn more about it.
                        <br><br>
                    </div>

                    <div class="font-bold text-lg text-gray-900 dark:text-gray-100">
                        Which items to choose?
                    </div>
                    <div class="text-gray-800 dark:text-gray-200">
                        If you are unsure which items to choose, you can use the choice helper to answer a few questions
                        and narrow down the selection.
                        You can also always find it with the button at the top of this page!<br> <br>
                    </div>

                    <div class="font-bold text-lg text-gray-900 dark:text-gray-100">
                        Then what?
                    </div>
                    <div class="text-gray-800 dark:text-gray-200">
                        When you are done, see how your selected items can be put together by clicking on the "ITEM
                        GRAPH" button.
                    </div>
                </card>
                <div class="w-full flex justify-between gap-4 mt-6">
                    <primary-button @click="choiceOpen=true; explainOpen=false">
                        Use the choice helper
                    </primary-button>
                    <secondary-button @click="explainOpen=false">
                        I want to explore on my own
                    </secondary-button>
                </div>
            </div>
        </modal>
    </AuthenticatedLayout>
</template>
