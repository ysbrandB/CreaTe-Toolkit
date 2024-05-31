<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, useForm} from '@inertiajs/vue3';
import {marked} from 'marked';
import {computed, ref} from "vue";
import axios from "axios";

const props = defineProps<{
    item?: {
        id: number;
        name: string;
        description: string;
    };
}>();
const photo = ref({
    name: '',
    data: null,
});

const markdown = ref(props.item?.description??"");
//computed property to convert markdown to html
const markdownToHtml = computed(() => {
    return marked(markdown.value);
});

const form = useForm({
    name: props.item?.name,
    description: markdown?.value,
});

const submit = () => {

    const formData = new FormData();
    console.log(form.name, form.description, photo.value.data);
    formData.append('name', form.name);
    formData.append('description', form.description);
    formData.append('photo', photo.value.data);
    if(props.item) formData.append('_method', 'put');
    console.log(...formData.entries());
    let postRoute = props.item ? route('items.update', props.item.id) : route('items.store');
    axios.post(postRoute, formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }})
        .then((res) => {
            console.log(res);
        });
};

const updatePhoto = (files) => {
    if (!files.length) return;

    // Store the file data
    photo.value.data = files[0];
    photo.value.name= files[0].name;
}
</script>

<template>
    <GuestLayout>
        <Head title="Update item"/>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Title"/>

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    initial-value="form.name"
                    v-model="form.name"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.name"/>
            </div>

            <div class="mt-4">
                <InputLabel for="image" value="Image"/>

                <input type="file" accept="image/*" class="form-control-file"
                       @change="updatePhoto($event.target.files)"
                >

                <InputError class="mt-2" :message="form.errors.image"/>
            </div>

            <div class="w-96 mt-4">
                <div class="relative w-full min-w-[200px]">
<!--                    <InputLabel for="description" value="Description"/>-->

                    <textarea
                        :value="form.description"
                        @input="markdown = ($event.target as HTMLInputElement).value; form.description = ($event.target as HTMLInputElement).value;"
                        id="description"
                        name="description"
                        type="description"
                        class="peer h-full min-h-[100px] w-full resize-none rounded-[7px] border border-blue-gray-200 border-t-transparent bg-transparent px-3 py-2.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-gray-900 focus:border-t-transparent focus:outline-0 disabled:resize-none disabled:border-0 disabled:bg-blue-gray-50"
                        placeholder=" "></textarea>
                    <InputError class="mt-2" :message="form.errors.description"/>
                    <label
                        class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-blue-gray-400 transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-blue-gray-200 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-blue-gray-200 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[3.75] peer-placeholder-shown:text-blue-gray-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-gray-900 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:border-gray-900 peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:border-gray-900 peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-blue-gray-500">
                        Markdown description
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Update item
                </PrimaryButton>
            </div>
        </form>
        <div v-html="markdownToHtml"></div>
    </GuestLayout>
</template>
