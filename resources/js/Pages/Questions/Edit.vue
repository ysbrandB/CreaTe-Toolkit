<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import {AttributeType, Question} from "@/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/Card.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {Dropdown as VDropdown} from 'floating-vue'
import 'floating-vue/dist/style.css'
import Pill from "@/CustomComponents/Pill.vue";
import NavLink from "@/Components/NavLink.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps<{
    question?: Question;
    attributeTypes: AttributeType[];
}>();

const form = useForm({
    title: props.question?.title ?? '',
    description: props.question?.description ?? '',
    answers: props.question?.answers ?? [],
});

const submit = () => {
    if (props.question) {
        // @ts-ignore
        router.put(route('questions.update', props.question.id), form.data() as RequestPayload);
    } else {
        // @ts-ignore
        router.post(route('questions.store'), form.data());
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Update Question"/>

        <form @submit.prevent="submit">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 grid-cols-1 mt-4">
                <card class="col-span-1">
                    <div class="w-full flex justify-between">
                        <span class="text-2xl font-bold">Question:</span>
                        <NavLink v-if="props.question" class="px-6" :href="route('questions.destroy', question?.id)"
                                 method="delete">
                            Delete question {{ question?.id }}
                        </NavLink>
                    </div>
                    <div class="my-2">
                        <InputLabel for="title" value="Title"/>

                        <TextInput
                            id="title"
                            type="text"
                            class="mt-1 block w-full"
                            initial-value="form.title"
                            v-model="form.title"
                            required
                            autofocus
                        />

                        <InputError class="mt-2" :message="form.errors.title"/>
                    </div>


                    <div class="w-full mt-4">
                        <div class="relative w-full min-w-[200px] mt-6">
                    <textarea
                        required
                        v-model="form.description"
                        class="peer h-full min-h-[100px] w-full resize-none rounded-[7px] border border-blue-gray-200 border-t-transparent bg-transparent px-3 py-2.5 font-sans text-sm font-normal text-blue-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-gray-200 placeholder-shown:border-t-blue-gray-200 focus:border-2 focus:border-gray-900 focus:border-t-transparent focus:outline-0 disabled:resize-none disabled:border-0 disabled:bg-blue-gray-50"
                        rows="5"></textarea>
                            <InputError class="mt-2" :message="form.errors.description"/>
                            <label
                                class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-blue-gray-400 transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-blue-gray-200 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-blue-gray-200 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[3.75] peer-placeholder-shown:text-blue-gray-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-gray-900 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:border-gray-900 peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:border-gray-900 peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-blue-gray-500">
                                Description
                            </label>
                        </div>
                    </div>
                </card>
                <card class="col-span-1 md:col-span-2">
                    <span class="text-2xl font-bold">Answers:</span>
                    <div class="my-2 flex flex-col gap-4">
                        <div v-for="(answer, index) in form.answers" :key="index">
                            <InputLabel :for="`answer-${index}`" :value="`Answer ${index + 1}`"/>
                            <div class="border-gray-600 rounded-lg p-4 border">
                                <div class="flex flex-row">
                                    <TextInput
                                        :id="`answer-${index}`"
                                        type="text"
                                        class="block w-full"
                                        v-model="answer.text"
                                        required
                                    />
                                    <v-dropdown placement="bottom-start">
                                        <button type="button"
                                                class="ml-2 border text-sm border-gray-300 rounded px-4 py-2">
                                            Add attribute
                                        </button>

                                        <template #popper>
                                            <VDropdown
                                                v-for="attributeType in attributeTypes"
                                                :key="attributeType.id"
                                                placement="right-start"
                                                instant-move
                                            >
                                                <button :class="`bg-${attributeType.color}-100`" type="button"
                                                        class="w-full text-start rounded hover:bg-green-100 px-4 py-2">
                                                    {{ attributeType.title }} >
                                                </button>

                                                <template #popper>
                                                    <button type="button" v-for="attribute in attributeType.attributes"
                                                            @click="answer.attributes.push(attribute);"
                                                            class="w-full text-start block rounded hover:bg-green-100 px-4 py-2">
                                                        {{ attribute.title }} >
                                                    </button>
                                                </template>
                                            </VDropdown>
                                        </template>
                                    </v-dropdown>
                                </div>
                                <div class="flex flex-wrap mt-2">
                                    <pill v-for="attribute in answer.attributes" :key="attribute.id"
                                          class="cursor-pointer"
                                          @click="answer.attributes = answer.attributes.filter(a => a.id !== attribute.id);"
                                          :color="attribute.attribute_type?.color">
                                        {{ attribute.title }} <span class="ms-2 text-red-600">x</span>
                                    </pill>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between">
                            <secondary-button type="button" @click="form.answers.push({
                                                text: '',
                                                attributes: [],
                                            })">Add a new answer
                            </secondary-button>
                            <primary-button type="submit" @click="submit">
                                {{ props.question ? 'Update' : 'Create' }} question
                            </primary-button>
                        </div>
                    </div>
                </card>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
