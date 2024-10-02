<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref, watch } from "vue";
import axios from 'axios';
import PrimaryButton from "@/Components/PrimaryButton.vue";

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
    type: 1,
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
    <div class="col">

        <PrimaryButton @click="create">Import</PrimaryButton>

        <div class="modal fade show" id="exampleLargeModal" tabindex="-1" aria-hidden="true" style="display: block;"
            v-if="modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    
                    <form @submit.prevent="edit_mode ? update() : submit()" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Import</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                @click="close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="input21" class="form-label">Type</label>

                                    <select id="input21" class="form-select" v-model="form.type">
                                        <option value="1">Policy Import</option>
                                        <option value="2">Client Import</option>
                                    </select>
                                    <InputError :message="form.errors.type" />
                                </div>
                                <div class="col-md-12">
                                    <label for="input13" class="form-label">Import File</label>
                                    <input type="file" class="form-control" id="input13"
                                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                        @change="handleFileChange">
                                    <InputError :message="form.errors.file" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                                @click="close">Close</button>

                            <button type="submit" class="btn btn-primary btn-sm" :disabled="form.processing">
                                {{ form.processing ? 'Please wait .. importing data' : 'Import' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>