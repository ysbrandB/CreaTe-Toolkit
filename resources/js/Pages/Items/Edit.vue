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
import axios from "axios";
import {Ref, ref} from "vue";
import * as repl from "node:repl";

const props = defineProps<{
    item?: Item,
    attributeTypes: AttributeType[],
    items: Item[],
    myAttributes: any
}>();
const itemPhotos: Ref<{
    url: string,
    id: number
}[]> = ref(props.item?.photos?.map((photo) => {
    return {
        'url': photo.url,
        'id': photo.id
    }
}) ?? []);
const addSelectedItem = (item: string, idx: number) => {
    if (item === "-1") {
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

const uploading = ref(false);

const uploadAdditionalPhotos = (e: any) => {
    if (!props.item) {
        return;
    }
    uploading.value = true;
    const formData = new FormData();
    formData.append('photo', e.target.files[0]);
    axios.post(route('items.photos.store', props.item.id), formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then((response) => {
        itemPhotos.value.push(response.data);
    }).catch((error) => {
        console.log(error);
    }).finally(() => {
        uploading.value = false;
    });
}

const copyToClipboard = (textToCopy: string) => {
    navigator.clipboard.writeText(textToCopy);
}

const removePhoto = (photoId: number) => {
    axios.delete(route('items.photos.destroy', {item: props.item?.id, photo: photoId})).then((response) => {
        if (response.status === 200) {
            itemPhotos.value = itemPhotos.value.filter((photo) => photo.id !== photoId);
        }
    })
}
</script>

<template>
    <Head title="Create/Update item"/>
    <AuthenticatedLayout>
        <form @submit.prevent="submit" class="m-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
                <card class="col-span-2">
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
                        <span class="font-bold ms-2">Description</span>
                        <v-md-editor v-model="form.description" height="300px"></v-md-editor>
                    </div>

                    <div class="mt-4">
                        <span class="font-bold ms-2">Card Description</span>
                        <v-md-editor v-model="form.card_description" height="300px"></v-md-editor>
                    </div>

                    <div class="mt-4">
                        <span class="font-bold ms-2">Wiring Instructions</span>
                        <v-md-editor v-model="form.wiring_instructions" height="300px"></v-md-editor>
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
                        <span class="font-bold ms-2">Pros</span>
                        <v-md-editor v-model="form.pros" height="300px"></v-md-editor>
                    </div>

                    <div class="mt-4">
                        <span class="font-bold ms-2">Cons</span>
                        <v-md-editor v-model="form.cons" height="300px"></v-md-editor>
                    </div>

                    <div class="mt-4">
                        <span class="font-bold ms-2">Hardware Considerations</span>
                        <v-md-editor v-model="form.hardware_considerations" height="300px"></v-md-editor>
                    </div>

                    <div class="mt-4">
                        <span class="font-bold ms-2">Software Considerations</span>
                        <v-md-editor v-model="form.software_considerations" height="300px"></v-md-editor>
                    </div>

                    <div class="mt-4">
                        <span class="font-bold ms-2">Example Code</span>
                        <v-md-editor v-model="form.example_code" height="300px"></v-md-editor>
                    </div>
                </card>
                <card class="col-span-1">
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
                <div class="col-span-1">
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
                                            :selected="
                                        // @ts-ignore
                                        selectableItem.id===selectedItemId||selectableItem.id.toString()===selectedItemId">
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
                                    {{ props.item ? 'Update' : 'Create' }} Item
                                </PrimaryButton>
                            </div>
                        </div>
                    </card>
                    <card>
                        <span class="font-bold">Photos to use in markdown:</span>
                        <div class="border" v-if="props.item">
                            <div class="flex">
                                <input type="file" accept="image/*" class="form-control-file"
                                       @change="uploadAdditionalPhotos"
                                       :disabled="uploading"
                                >
                                <svg v-if="uploading" class="text-gray-300 animate-spin" viewBox="0 0 64 64" fill="none"
                                     xmlns="http://www.w3.org/2000/svg"
                                     width="30" height="30">
                                    <path
                                        d="M32 3C35.8083 3 39.5794 3.75011 43.0978 5.20749C46.6163 6.66488 49.8132 8.80101 52.5061 11.4939C55.199 14.1868 57.3351 17.3837 58.7925 20.9022C60.2499 24.4206 61 28.1917 61 32C61 35.8083 60.2499 39.5794 58.7925 43.0978C57.3351 46.6163 55.199 49.8132 52.5061 52.5061C49.8132 55.199 46.6163 57.3351 43.0978 58.7925C39.5794 60.2499 35.8083 61 32 61C28.1917 61 24.4206 60.2499 20.9022 58.7925C17.3837 57.3351 14.1868 55.199 11.4939 52.5061C8.801 49.8132 6.66487 46.6163 5.20749 43.0978C3.7501 39.5794 3 35.8083 3 32C3 28.1917 3.75011 24.4206 5.2075 20.9022C6.66489 17.3837 8.80101 14.1868 11.4939 11.4939C14.1868 8.80099 17.3838 6.66487 20.9022 5.20749C24.4206 3.7501 28.1917 3 32 3L32 3Z"
                                        stroke="currentColor" stroke-width="5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path
                                        d="M32 3C36.5778 3 41.0906 4.08374 45.1692 6.16256C49.2477 8.24138 52.7762 11.2562 55.466 14.9605C58.1558 18.6647 59.9304 22.9531 60.6448 27.4748C61.3591 31.9965 60.9928 36.6232 59.5759 40.9762"
                                        stroke="currentColor" stroke-width="5" stroke-linecap="round"
                                        stroke-linejoin="round" class="text-red-500">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div v-else>
                            <span class="text-red-800">Please save the item first to upload additional photos</span>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2">
                            <div class="border-gray-300 p-1 border-2 rounded-lg flex justify-between flex-col"
                                 v-for="photo in itemPhotos">
                                <img :src="photo.url" class="rounded-lg" :alt="photo.url">
                                <div class="w-full flex justify-between">
                                    <button type="button" @click="copyToClipboard(photo.url)"
                                            class="bg-white rounded-lg p-1 font-bold">Copy link
                                    </button>
                                    <button type="button" @click="removePhoto(photo.id)"
                                            class="text-red-800 font-bold p-1">X
                                    </button>
                                </div>
                            </div>
                        </div>
                    </card>
                </div>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
