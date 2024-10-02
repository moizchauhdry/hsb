<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import axios from 'axios';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const { props } = usePage();
const claim_modal = ref(false);
const edit_mode = ref(false);
const form = useForm({
    policy_id: JSON.parse(JSON.stringify((props.policy.id))) ?? "",
    detail: "",
    status: "",
});


const create = () => {
    claim_modal.value = true;
    edit_mode.value = false;
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
    <PrimaryButton @click="create" data-bs-toggle="modal" data-bs-target="#claimsLargeModal">
        <i class='bx bxs-note mr-1'></i> Policy Claim
    </PrimaryButton>

    <div class="modal fade show" id="claimsLargeModal" tabindex="-1" aria-hidden="true" style="display: block;"
        v-if="claim_modal">
        <div class="modal fade show" id="claimsLargeModal" tabindex="-1" aria-hidden="true" style="display: block;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form @submit.prevent="edit_mode ? update() : submit()">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Claims</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                @click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" v-model="form.policy_id">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="input21" class="form-label">Status</label>

                                    <select id="input21" class="form-select" v-model="form.status">

                                        <option value="open">Open</option>
                                        <option value="progress">Progress</option>
                                        <option value="settled">Settled</option>
                                        <option value="close">Close</option>
                                    </select>
                                    <InputError :message="form.errors.status" />
                                </div>
                                <div class="col-md-12">
                                    <label for="input13" class="form-label">Detail</label>
                                    <textarea class="form-control" id="detail" v-model="form.detail"></textarea>
                                    <InputError :message="form.errors.detail" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                                @click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm">
                                {{ edit_mode ? 'Save & Update' : 'Save & Submit' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
