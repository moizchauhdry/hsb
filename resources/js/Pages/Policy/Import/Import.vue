<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref, watch } from "vue";
import axios from 'axios';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
const { props } = usePage();
const modal = ref(false);
const edit_mode = ref(false);

const handleFileChange = (event) => {
    // Access the selected files from the event
    const selectedFiles = event.target.files[0];
    // Update the data property with the selected files
    form.file = selectedFiles;
};

const form = useForm({
    file: null,
    type: "",
});

const create = () => {
    modal.value = true;
    edit_mode.value = false;
};

const submit = () => {
    form.post(route("policy.import"), {
        preserveScroll: true,
        onSuccess: () => close(),
        onError: (errors) => {
            // Handle validation errors returned from the server
            if (errors.hasOwnProperty('errors') && errors.errors.hasOwnProperty('file')) {
                form.errors.file = errors.errors.file[0];
            }
        },
        onFinish: () => { },
    });
};

const error = () => {
    // alert('error');
};

const close = () => {
    modal.value = false;
    form.reset();
};

</script>

<template>
    <SuccessButton @click="create"><i class="bx bx-import mr-1"></i> Excel Import</SuccessButton>

    <Modal :show="modal" @close="closeModal">
        <form @submit.prevent="edit_mode ? update() : submit()" enctype="multipart/form-data">

            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Excel Import</h2>

                <p class="mt-1 text-sm text-gray-600">
                    <hr>
                </p>

                <div class="mt-6">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="input21" class="form-label">Type</label>

                            <select id="input21" class="form-select" v-model="form.type">
                                <option value="1">Policy Import</option>
                                <option value="2">Commission Statement</option>
                            </select>
                            <InputError :message="form.errors.type" />
                        </div>
                        <div class="col-md-6">
                            <label for="input13" class="form-label">Import File</label>
                            <input type="file" class="form-control" id="input13"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                @change="handleFileChange">
                            <InputError :message="form.errors.file" />
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="close()"> Cancel </SecondaryButton>

                    <SuccessButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ form.processing ? 'Please wait ... Importing data' : 'Import' }}
                    </SuccessButton>
                </div>
            </div>
        </form>
    </Modal>

</template>