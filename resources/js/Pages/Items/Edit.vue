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
import MarkDownTextArea from "@/CustomComponents/MarkDownTextArea.vue";

const props = defineProps<{
    item?: {
        id: number;
        title: string;
        description: string;
        wiring_instructions: string;
        pros: string;
        cons: string;
        hardware_considerations: string;
        software_considerations: string;
        example_code: string;
    };
}>();
const thumbnail = ref({
    name: '',
    data: null,
});

const wiringPhoto = ref({
    name: '',
    data: null,
});

const descriptionMarkdown = ref(props.item?.description ?? "");
const wiringMarkdown = ref(props.item?.wiring_instructions ?? "");
const prosMarkdown = ref(props.item?.pros ?? "");
const consMarkdown = ref(props.item?.cons ?? "");
const hardwareMarkdown = ref(props.item?.hardware_considerations ?? "");
const softwareMarkdown = ref(props.item?.software_considerations ?? "");
const exampleCodeMarkdown = ref(props.item?.example_code ?? "");

//computed property to convert markdown to html
const markdownToHtml = computed(() => {
    return marked(descriptionMarkdown.value);
});

const form = useForm({
    name: props.item?.title,
    description: descriptionMarkdown?.value,
    wiring_instructions: wiringMarkdown?.value,
    pros: prosMarkdown?.value,
    cons: consMarkdown?.value,
    hardware_considerations: hardwareMarkdown?.value,
    software_considerations: softwareMarkdown?.value,
    example_code: exampleCodeMarkdown?.value,
});

const submit = () => {

    const formData = new FormData();
    Object.keys(form.data()).forEach(key => {
        formData.append(key, form.data()[key]);
    });
    formData.append('photo', thumbnail.value.data);
    formData.append('wiring_photo', wiringPhoto.value.data);
    //weird put method hack to actually let laravel know this is a put request
    if (props.item) formData.append('_method', 'put');
    console.log(...formData.entries());
    let postRoute = props.item ? route('items.update', props.item.id) : route('items.store');
    axios.post(postRoute, formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then((res) => {
            console.log(res);
        });
};

const updatePhoto = (files) => {
    if (!files.length) return;

    // Store the file data
    thumbnail.value.data = files[0];
    thumbnail.value.name = files[0].name;
}

const updateWiringPhoto = (files) => {
    if (!files.length) return;

    // Store the file data
    wiringPhoto.value.data = files[0];
    wiringPhoto.value.name = files[0].name;
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

                <InputError class="mt-2" :message="form.errors.title"/>
            </div>

            <div class="mt-4">
                <InputLabel for="photo" value="Photo"/>

                <input type="file" accept="image/*" class="form-control-file"
                       name="photo"
                       required
                       @change="updatePhoto($event as HTMLInputElement).target.files"
                >

                <InputError class="mt-2" :message="form.errors.photo??''"/>
            </div>

            <div class="w-96 mt-4">
                <MarkDownTextArea
                    id="description"
                    @updateMarkdown="descriptionMarkdown = $event; form.description = $event"
                    :markdown="descriptionMarkdown"
                    :description="form.description"
                    :error="form.errors.description??''"/>
            </div>

            <div class="mt-4">
                <InputLabel for="wiring_photo" value="Wiring Photo"/>

                <input type="file" accept="image/*" class="form-control-file"
                       name="wiring_photo"
                       required
                       @change="updateWiringPhoto($event as HTMLInputElement).target.files"
                >

                <InputError class="mt-2" :message="form.errors.wiringPhoto??''"/>
            </div>

            <div class="w-96 mt-4">
                <MarkDownTextArea
                    id="wiring_instructions"
                    @updateMarkdown="wiringMarkdown = $event; form.wiring_instructions = $event"
                    :markdown="wiringMarkdown"
                    :description="form.wiring_instructions"
                    :error="form.errors.wiring_instructions??''"/>
            </div>

            <div class="w-96 mt-4">
                <MarkDownTextArea
                    id="pros"
                    @updateMarkdown="prosMarkdown = $event; form.pros = $event"
                    :markdown="prosMarkdown"
                    :description="form.pros"
                    :error="form.errors.pros??''"/>
            </div>

            <div class="w-96 mt-4">
                <MarkDownTextArea
                    id="cons"
                    @updateMarkdown="consMarkdown = $event; form.cons = $event"
                    :markdown="consMarkdown"
                    :description="form.cons"
                    :error="form.errors.cons??''"/>
            </div>

            <div class="w-96 mt-4">
                <MarkDownTextArea
                    id="hardware_considerations"
                    @updateMarkdown="hardwareMarkdown = $event; form.hardware_considerations = $event"
                    :markdown="hardwareMarkdown"
                    :description="form.hardware_considerations"
                    :error="form.errors.hardware_considerations??''"/>
            </div>

            <div class="w-96 mt-4">
                <MarkDownTextArea
                    id="software_considerations"
                    @updateMarkdown="softwareMarkdown = $event; form.software_considerations = $event"
                    :markdown="softwareMarkdown"
                    :description="form.software_considerations"
                    :error="form.errors.software_considerations??''"/>

            </div>

            <div class="w-96 mt-4">
                <MarkDownTextArea
                    id="example_code"
                    @updateMarkdown="exampleCodeMarkdown = $event; form.example_code = $event"
                    :markdown="exampleCodeMarkdown"
                    :description="form.example_code"
                    :error="form.errors.example_code??''"/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Update item
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
