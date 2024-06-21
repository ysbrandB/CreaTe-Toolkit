<script setup lang="ts">

import OpeningComponent from "@/CustomComponents/OpeningComponent.vue";
import Pill from "@/CustomComponents/Pill.vue";
import {Attribute, AttributeType} from "@/types";
import {computed, nextTick, ref} from "vue";

const props = withDefaults(defineProps<{
    attributeTypes: AttributeType[],
    initialFilters?: Record<number, string[]>
    title?: string
}>(), {
    title: 'filters',
});
const emit = defineEmits<{
    (e: 'update', update: Record<number, number[]>): void
}>()

const check = (attributeType: AttributeType, attribute: Attribute, checked: boolean) => nextTick(() => {
    if (checked) {
        checkedAttributesMap.value.set(attributeType, [...(checkedAttributesMap.value.get(attributeType) ?? []), attribute]);
    } else {
        checkedAttributesMap.value.set(attributeType, (checkedAttributesMap.value.get(attributeType) ?? []).filter((checkedAttribute) => checkedAttribute.id !== attribute.id));
    }
    //filter out the attribute type if there are no attributes
    if (checkedAttributesMap.value.get(attributeType)?.length === 0) {
        checkedAttributesMap.value.delete(attributeType);
    }
    emit('update', checkedAttributes.value);
});

const addFiltersByAttributeIds = (ids: number[]) => {
    ids.forEach((id) => {
        addFilterByAttributeId(id);
    });
}

const addFilterByAttributeId = (id: number) => {
    const attribute = props.attributeTypes.flatMap((attributeType) => attributeType.attributes).find((attribute) => attribute?.id === id);
    if (attribute) {
        //find the corresponding attribute type
        const attributeType = props.attributeTypes.find((attributeType) => attributeType.id === attribute.attribute_type_id);
        if (attributeType && !checkedAttributesMap.value.has(attributeType)) {
            check(attributeType, attribute, true);
        }
    }
}

const reset = () => {
    checkedAttributesMap.value.clear();
    emit('update', {});
}

const findAndAddAttribute = (attributeTitle: string) => {
    for (const attributeType of props.attributeTypes) {
        const attribute = attributeType.attributes?.find((attribute) => attribute.title === attributeTitle);
        if (attribute) {
            check(attributeType, attribute, true);
            return;
        }
    }
}

const checkedAttributesMap = ref(new Map<AttributeType, Attribute[]>());
if (props.initialFilters) {
    for (const [attributeTypeId, attributeIds] of Object.entries(props.initialFilters)) {
        const attributeType = props.attributeTypes.find((attributeType) => attributeType.id === parseInt(attributeTypeId));
        if (attributeType) {
            attributeIds.forEach((attributeId) => {
                const attribute = attributeType.attributes?.find((attribute) => attribute.id === parseInt(attributeId));
                if (attribute) {
                    check(attributeType, attribute, true);
                }
            });
        }
    }
} else {
    findAndAddAttribute('Actuator')
    findAndAddAttribute('Sensor')
}

const checkedAttributes = computed(() => {
    const allAttributes: Record<number, number[]> = {};
    for (const [attributeType, attributes] of checkedAttributesMap.value.entries()) {
        allAttributes[attributeType.id] = attributes.map((attribute) => attribute.id);
    }
    return allAttributes;
});

function capitalizeFirstLetter(string: string) {
    return string[0].toUpperCase() + string.slice(1);
}

defineExpose({
    reset,
    addFiltersByAttributeIds,
    checkedAttributes
})
</script>
<template>
    <div class="flex justify-items-center items-end  mt-4 w-full">
      <div class="bg-red-100 basis-1/3 grow"></div>
        <div class="text-2xl text-center basis-1/3 font-semibold grow">{{ capitalizeFirstLetter(title) }}</div>
       <div class="grow basis-1/3 mb-1"> <pill v-if="checkedAttributesMap.size" class="cursor-pointer" @click="reset()" :color="'red'"> Clear all filters</pill> </div>
    </div>
    <div
        class="mx-2 mt-1 flex flex-wrap text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        <template v-for="[attributeType, attributes] in checkedAttributesMap.entries()">
            <pill
                v-for="attribute in attributes" :key="attribute.id"
                class="cursor-pointer" @click="check(attributeType, attribute, false)"
                :color="attributeType.color">
                {{ attribute.title }} <span class="ms-2 text-red-600">x</span>
            </pill>
        </template>
        <div v-if="checkedAttributesMap.size===0" class="w-full p-2 text-center text-gray-500">
            No {{ title }} applied
        </div>
    </div>
    <OpeningComponent v-for="attributeType in attributeTypes" :title="attributeType.title">
        <template #title>
            <div class="font-bold text-sm md:text-lg p-1 md:p3 rounded rounded-lg"
                 :class="`bg-${attributeType.color}-100`">{{ attributeType.title }}
            </div>
        </template>
        <template #content>
            <ul class="w-full mt-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <li v-for="attribute in attributeType.attributes" :key="attribute.id"
                    class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                    <div class="flex items-center ps-3">
                        <input type="checkbox"
                               :checked="checkedAttributesMap.get(attributeType)?.some((checkedAttribute) => checkedAttribute.id === attribute.id)"
                               @change="check(attributeType, attribute, ($event as HTMLInputElement|any).target.checked)"
                               :id="attribute.id.toString()" :value="attribute.id"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                        <label :for="attribute.id.toString()"
                               class="w-full py-2 md:py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{
                                attribute.title
                            }}</label>
                    </div>
                </li>
            </ul>
        </template>
    </OpeningComponent>
</template>
