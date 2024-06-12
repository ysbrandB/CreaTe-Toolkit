<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import {computed, ref} from "vue";
import axios from "axios";
import MarkDownTextArea from "@/CustomComponents/MarkDownTextArea.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/Card.vue";
import {Attribute, AttributeType, Item} from "@/types";
import AttributeFilter from "@/CustomComponents/AttributeFilter.vue";

const props = defineProps<{
    item?: Item,
    attributeTypes: AttributeType[],
    items: Item[],
    myAttributes: any
}>();

const initialSelectedAttributes = {} as Record<string, { color: string, attributes: Attribute[] | undefined }>;
props.myAttributes?.forEach((attributeType: AttributeType) => {
    initialSelectedAttributes[attributeType.id.toString()] = {
        color: attributeType.color,
        attributes: attributeType.attributes
    };
});

const selectedAttributes = ref<Record<string, {
    color: string,
    attributes: Attribute[] | undefined
}>>(initialSelectedAttributes);

const flattenedSelectedAttributes = computed(() => {
    let flattened: number[] = [];
    Object.values(selectedAttributes.value).forEach((attributeType: { attributes: Attribute[] | undefined }) => {
        if (attributeType.attributes === undefined) return;
        attributeType.attributes.forEach((attribute: Attribute) => {
            flattened.push(attribute.id);
        });
    });
    return flattened;
});
const addSelectedItem = (item: number, idx: number) => {
    // @ts-ignore
    form.edges[idx] = item;
}

const form = useForm({
    title: props.item?.title ?? '',
    description: props.item?.description ?? '',
    card_description: props.item?.card_description ?? '',
    wiring_instructions:  props.item?.wiring_instructions ?? '',
    pros: props.item?.pros ?? '',
    cons:   props.item?.cons ?? '',
    hardware_considerations: props.item?.hardware_considerations ?? '',
    software_considerations: props.item?.software_considerations ?? '',
    example_code: props.item?.example_code ?? '',
    edges: props.item?.json_items ?? [],
    attributes: flattenedSelectedAttributes.value,
    photo: '',
    wiring_photo: '',
});

function handlePhotoChange(e: any) {
    form.photo = e.target.files[0]
}

function handleWiringPhotoChange(e: any) {
    form.wiring_photo = e.target.files[0]
}

const submit = () => {
    const formData = new FormData();
    Object.keys(form.data()).forEach(key => {
        // @ts-ignore
        formData.append(key, form.data()[key]);
    });
    if (props.item) formData.append('_method', 'put');
    console.log(...formData.entries());
    let postRoute = props.item ? route('items.update', props.item.id) : route('items.store');
    axios.post(postRoute, formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then((data) => {
            if (data.status === 200) {
                if(props.item)
                    return router.get(route('items.show', props.item.public_id));
                return router.get(route('items.index'));
            }
            console.log(data);
        }
    ).catch((error) => {
        console.log(error);
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <form @submit.prevent="submit" class="m-4">
            <div class="flex lg:flex-row flex-col">
                <Card>
                    <Head title="Update item"/>
                    <div>
                        <InputLabel for="title" value="Title"/>
                        <TextInput
                            id="title"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.title"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.title"/>
                    </div>

                    <div class="mt-4">
                        <InputLabel for="photo" value="Photo"/>

                        <img class="w-[50%] mx-auto" :src="item?.photo_url"/>

                        <input type="file" accept="image/*" class="form-control-file"
                               name="photo"
                               @change="handlePhotoChange"
                        >
                    </div>

                    <div class="w-96 mt-4">
                        <MarkDownTextArea
                            id="description"
                            @updateMarkdown="form.description = $event"
                            :description="form.description"
                            :error="form.errors.description??''"/>
                    </div>

                    <div class="w-96 mt-4">
                        <MarkDownTextArea
                            id="card_description"
                            @updateMarkdown="form.card_description = $event"
                            :description="form.card_description"
                            :error="form.errors.card_description??''"/>
                    </div>

                    <div class="w-96 mt-4">
                        <MarkDownTextArea
                            id="wiring_instructions"
                            @updateMarkdown="form.wiring_instructions = $event"
                            :description="form.wiring_instructions"
                            :error="form.errors.wiring_instructions??''"/>
                    </div>

                    <div class="mt-4">
                        <InputLabel for="wiring_photo" value="Wiring Photo"/>

                        <img class="w-[50%] mx-auto" :src="item?.wiring_photo_url"/>

                        <input type="file" accept="image/*" class="form-control-file"
                               name="wiring_photo"
                               @change="handleWiringPhotoChange"
                        >
                    </div>

                    <div class="w-96 mt-4">
                        <MarkDownTextArea
                            id="pros"
                            @updateMarkdown="form.pros = $event"
                            :description="form.pros"
                            :error="form.errors.pros??''"/>
                    </div>

                    <div class="w-96 mt-4">
                        <MarkDownTextArea
                            id="cons"
                            @updateMarkdown="form.cons = $event"
                            :description="form.cons"
                            :error="form.errors.cons??''"/>
                    </div>

                    <div class="w-96 mt-4">
                        <MarkDownTextArea
                            id="hardware_considerations"
                            @updateMarkdown="form.hardware_considerations = $event"
                            :description="form.hardware_considerations"
                            :error="form.errors.hardware_considerations??''"/>
                    </div>

                    <div class="w-96 mt-4">
                        <MarkDownTextArea
                            id="software_considerations"
                            @updateMarkdown="form.software_considerations = $event"
                            :description="form.software_considerations"
                            :error="form.errors.software_considerations??''"/>
                    </div>

                    <div class="w-96 mt-4">
                        <MarkDownTextArea
                            id="example_code"
                            @updateMarkdown="form.example_code = $event"
                            :description="form.example_code"
                            :error="form.errors.example_code??''"/>
                    </div>
                </Card>
                <card>
                    <div class="w-96 mt-4 text-center">
                        <attribute-filter
                            title="attributes"
                            :attribute-types="props.attributeTypes"
                            :checked-attributes="initialSelectedAttributes"
                            @update:checked-attributes="
                            // @ts-ignore
                            selectedAttributes=$event; form.attributes = flattenedSelectedAttributes"/>
                    </div>
                </card>
                <card>
                    <div class="w-96 mt-4 text-center">
                        <div class="text-md">This item</div>
                        <div v-for="(selectedItemId, index) in form.edges" :key="index">
                            <div class="text-lg font-bold">↓</div>
                            <select
                                :id="index.toString()"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                @change="addSelectedItem(
                                    // @ts-ignore
                                    $event.target.value, index)"
                            >
                                <option v-for="selectableItem in props.items"
                                        :value="selectableItem.id"
                                        :key="selectableItem.id"
                                        :selected="selectedItemId === selectableItem.id">
                                    {{ selectableItem.title }}
                                </option>
                            </select>
                        </div>
                        <div class="text-lg font-bold">↓</div>
                        <select
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            @change="addSelectedItem(
                                // @ts-ignore
                                $event.target.value, form.edges?.length??0); $event.target.value = '';"
                        >
                            <option value="" selected disabled hidden>Choose another block</option>
                            <option v-for="selectableItem in props.items"
                                    :value="selectableItem.id"
                                    :key="selectableItem.id"
                            >
                                {{ selectableItem.title }}
                            </option>
                        </select>
                        <div class="text-lg font-bold">↓</div>
                        <div class="text-md">
                            Python
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }"
                                           :disabled="form.processing">
                                Update item
                            </PrimaryButton>
                        </div>
                    </div>
                </card>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
