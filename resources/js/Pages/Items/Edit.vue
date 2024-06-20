<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import MarkDownTextArea from "@/CustomComponents/MarkDownTextArea.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/Card.vue";
import {AttributeType, Item} from "@/types";
import AttributeFilter from "@/CustomComponents/AttributeFilter.vue";

const props = defineProps<{
    item?: Item,
    attributeTypes: AttributeType[],
    items: Item[],
    myAttributes: any
}>();

const addSelectedItem = (item: string, idx: number) => {
    if(item === "-1") {
        // @ts-ignore
        form.edges.splice(idx, 1);
        return;
    }
    // @ts-ignore
    form.edges[idx] = item;
}

const flattenAttributeIds = (attributeIds: Record<number, number[]>) => {
    const allAttributes: number[] = []
    for (const [_, attributes] of Object.entries(attributeIds)) {
        attributes.forEach((attributeId) => {
            allAttributes.push(attributeId)
        });
    }
    return allAttributes;
}

const form = useForm({
    _method: 'post',
    title: props.item?.title ?? '',
    description: props.item?.description ?? '',
    card_description: props.item?.card_description ?? '',
    wiring_instructions: props.item?.wiring_instructions ?? '',
    pros: props.item?.pros ?? '',
    cons: props.item?.cons ?? '',
    hardware_considerations: props.item?.hardware_considerations ?? '',
    software_considerations: props.item?.software_considerations ?? '',
    example_code: props.item?.example_code ?? '',
    edges: props.item?.json_items ?? [],
    attributes: [],
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
    if (props.item) {
        form._method = 'put'
        router.post(route('items.update', props.item.id), form.data(), {
            forceFormData: true,
        });
    } else {
        router.post(route('items.store'), form.data(), {
            forceFormData: true,
        });
    }
}
</script>

<template>
    <Head title="Update item"/>
    <AuthenticatedLayout>
        <form @submit.prevent="submit" class="m-4">
            <div class="flex lg:flex-row flex-col">
                <Card>
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

                    <div class="mt-4">
                        <MarkDownTextArea
                            id="description"
                            @updateMarkdown="form.description = $event"
                            :description="form.description"
                            :error="form.errors.description??''"/>
                    </div>

                    <div class="mt-4">
                        <MarkDownTextArea
                            id="card_description"
                            @updateMarkdown="form.card_description = $event"
                            :description="form.card_description"
                            :error="form.errors.card_description??''"/>
                    </div>

                    <div class="mt-4">
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

                    <div class="mt-4">
                        <MarkDownTextArea
                            id="pros"
                            @updateMarkdown="form.pros = $event"
                            :description="form.pros"
                            :error="form.errors.pros??''"/>
                    </div>

                    <div class="mt-4">
                        <MarkDownTextArea
                            id="cons"
                            @updateMarkdown="form.cons = $event"
                            :description="form.cons"
                            :error="form.errors.cons??''"/>
                    </div>

                    <div class="mt-4">
                        <MarkDownTextArea
                            id="hardware_considerations"
                            @updateMarkdown="form.hardware_considerations = $event"
                            :description="form.hardware_considerations"
                            :error="form.errors.hardware_considerations??''"/>
                    </div>

                    <div class="mt-4">
                        <MarkDownTextArea
                            id="software_considerations"
                            @updateMarkdown="form.software_considerations = $event"
                            :description="form.software_considerations"
                            :error="form.errors.software_considerations??''"/>
                    </div>

                    <div class="mt-4">
                        <MarkDownTextArea
                            id="example_code"
                            @updateMarkdown="form.example_code = $event"
                            :description="form.example_code"
                            :error="form.errors.example_code??''"/>
                    </div>
                </Card>
                <card>
                    <div class="mt-4 text-center">
                        <attribute-filter
                            title="attributes"
                            :attribute-types="props.attributeTypes"
                            :initial-filters="props.myAttributes"
                            @update="
                            // @ts-ignore
                            form.attributes=flattenAttributeIds($event)"/>
                    </div>
                </card>
                <card>
                    <div class="mt-4 text-center">
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
                                <option value="-1">Remove block</option>
                                <option v-for="selectableItem in items"
                                        :value="selectableItem.id"
                                        :key="selectableItem.id"
                                        :selected="selectableItem.id.toString()===selectedItemId">
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
