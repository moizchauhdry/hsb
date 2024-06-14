<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import axios from 'axios';
import SecondaryButton from "@/Components/SecondaryButton.vue";

const { props } = usePage();
const uploads_modal = ref(false);
const edit_mode = ref(false);

const handleFileChange = (event) => {
    // Access the selected files from the event
    const selectedFiles = event.target.files;
    // Update the data property with the selected files
    form.uploads = selectedFiles;
};

const form = useForm({
    policy_id: props.policy.id ?? "",
    uploads: null,
    type: "",
});

const create = () => {
    uploads_modal.value = true;
    edit_mode.value = false;
};

const submit = () => {
    form.post(route("policy.uploads"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => error(),
        onFinish: () => { },
    });
};

const error = () => {
    // alert('error');
};

const closeModal = () => {
    uploads_modal.value = false;
    form.reset();
};

</script>

<template>
    <AuthenticatedLayout>
        <div class="col">
            <SecondaryButton @click="create" data-bs-toggle="modal"
                data-bs-target="#uploadsLargeModal"><i class='bx bx-cloud-upload'>Uploads</i>
            </SecondaryButton>
                <div class="modal fade show" id="uploadsLargeModal" tabindex="-1" aria-hidden="true" style="display: block;"
                v-if="uploads_modal">
                <div class="modal fade show" id="uploadsLargeModal" tabindex="-1" aria-hidden="true"
                    style="display: block;">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <form @submit.prevent="edit_mode ? update() : submit()" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title">Uploads</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close" @click="closeModal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <input type="hidden" v-model="form.policy_id">
                                        <div class="col-md-12">
                                            <label for="input13" class="form-label">Uploads</label>
                                            <input type="file" class="form-control" id="input13" @change="handleFileChange">
                                            <InputError :message="form.errors.uploads" />
                                        </div>
                                        <div class="col-md-12">
                                            <label for="input21" class="form-label">Type</label>

                                            <select id="input21" class="form-select"
                                                v-model="form.type">

                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                            </select>
                                            <InputError :message="form.errors.type" />
                                        </div>
                                       
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal" @click="closeModal">Close</button>

                                    <button type="submit" class="btn btn-primary btn-sm">
                                        {{ edit_mode ? 'Save & Update' : 'Save & Submit' }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
