<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import axios from 'axios';


const uploads_modal = ref(false);
const edit_mode = ref(false);

const create = () => {
  uploads_modal.value = true;
  edit_mode.value = false;
};


const submit = () => {
    form.post(route("class-of-business.create"), {
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

const handleDragOver = (event) => {
    event.preventDefault();
};
const handleDrop = (event) => {
    event.preventDefault();
    const files = event.dataTransfer.files;
    this.uploadFiles(files);
};
const selectFiles = () => {
    const inputElement = document.getElementById('image-uploadify');
    inputElement.click();
};
const uploadFiles = (files) => {
      const formData = new FormData();
      for (let i = 0; i < files.length; i++) {
        formData.append('images[]', files[i]);
      }

      axios.post('/upload', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(response => {
        console.log(response.data);
      }).catch(error => {
        console.error(error);
      });
    };
</script>

<template>
    <AuthenticatedLayout>
        <div class="col">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadsLargeModal"
                @click="create"><i class='bx bx-cloud-upload'></i>Uploads</button>
                <div class="modal fade show" id="uploadsLargeModal" tabindex="-1" aria-hidden="true" style="display: block;"
                v-if="uploads_modal">
                <div class="modal fade show" id="uploadsLargeModal" tabindex="-1" aria-hidden="true"
                    style="display: block;">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <form @submit.prevent="edit_mode ? update() : submit()">
                                <div class="modal-header">
                                    <h5 class="modal-title">Uploads</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close" @click="closeModal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <input id="image-uploadify" type="file" accept=".xlsx,.xls,image/*,.doc,audio/*,.docx,video/*,.ppt,.pptx,.txt,.pdf" multiple style="display: none;">
                                        <div class="imageuploadify well" @dragover="handleDragOver" @drop="handleDrop">
                                        <div class="imageuploadify-overlay">
                                            <i class="fa fa-picture-o"></i>
                                        </div>
                                        <div class="imageuploadify-images-list text-center">
                                            <i class="bx bxs-cloud-upload"></i>
                                            <span class="imageuploadify-message">Drag & Drop Your File(s) Here To Upload</span>
                                            <!-- Call selectFiles function when button is clicked -->
                                            <button type="button" class="btn btn-default" @click="selectFiles">or select file(s) to upload</button>
                                        </div>
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
