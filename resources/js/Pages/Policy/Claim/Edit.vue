<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import axios from 'axios';

const { props } = usePage();
const claim_modal = ref(false);
const edit_mode = ref(false);
const form = useForm({
    policy_id: JSON.parse(JSON.stringify((props.policy.id))) ?? "",
    detail: "",
    progress: "",
    settled: "",
    status: "",
});


const edit = () => {
  claim_modal.value = true;
  edit_mode.value = true;
};

const submit = () => {
    form.post(route("policy.claims"), {
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
    claim_modal.value = false;
    form.reset();
};

</script>
<template>
<AuthenticatedLayout>
    <div class="col">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#EditLargeModal" style="font-size: 10px; width:40px;"
        @click="edit"><i class='bx bx-edit'></i></button>
            <div class="modal fade show" id="EditLargeModal" tabindex="-1" aria-hidden="true" style="display: block;"
            v-if="claim_modal">
            <div class="modal fade show" id="EditLargeModal" tabindex="-1" aria-hidden="true"
                style="display: block;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form @submit.prevent="edit_mode ? update() : submit()">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Claims</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close" @click="closeModal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" v-model="form.policy_id">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="input13" class="form-label">Progress</label>
                                        <input type="text" class="form-control" id="input13"
                                            placeholder="" v-model="form.progress">
                                        
                                        <InputError :message="form.errors.progress" />
                                    </div>
                                    <div class="col-md-4">
                                        <label for="input13" class="form-label">Settled</label>
                                        <input type="text" class="form-control" id="input13"
                                            placeholder="" v-model="form.settled">
                                       
                                        <InputError :message="form.errors.settled" />
                                    </div>
                                    <div class="col-md-4">
                                        <label for="input21" class="form-label">Status</label>

                                        <select id="input21" class="form-select"
                                            v-model="form.status">

                                            <option value="open">Open</option>
                                            <option value="close">Close</option>
                                        </select>
                                        <InputError :message="form.errors.status" />
                                    </div>
                                    <div class="col-md-12">
                                        <label for="input13" class="form-label">Detail</label>
                                       <textarea class="form-control" id="detail" v-model="form.detail" ></textarea>
                                       <InputError :message="form.errors.detail" />
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