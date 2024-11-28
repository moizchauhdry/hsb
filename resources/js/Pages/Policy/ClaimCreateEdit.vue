<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import axios from 'axios';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import Swal from 'sweetalert2';

defineProps({
    create_mode: Boolean,
});

const { props } = usePage();

const modal = ref(false);
const edit_mode = ref(false);

const form = useForm({
    claim_id: "",
    policy_id: "",
    description: "",
    status: "progress",
    claim_at: "",
    intimation_at: "",
    survivor_name: "",
    contact_no: "",
});

const create = () => {
    form.policy_id = props.policy.id;
    modal.value = true;
    edit_mode.value = false;
};

const submit = () => {
    form.post(route("claim.store"), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire('Added!', 'The claim added successfully.', 'success')
            close();
        },
        onError: () => error(),
        onFinish: () => { },
    });
};

const update = () => {
    form.post(route("claim.update"), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire('Updated!', 'The claim update successfully.', 'success')
            close();
        },
        onError: (errors) => {
            if (errors.status == false) {
                Swal.fire('Error!', errors.message, 'error')
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
};

const claimEdit = (id) => {
    modal.value = true;
    edit_mode.value = true;

    axios.get(`/claims/fetch/claim/${id}`)
        .then(({ data }) => {
            form.claim_id = data.policy_claim.id;
            form.policy_id = data.policy_claim.policy_id;
            form.description = data.policy_claim.detail;
            form.status = data.policy_claim.status;

            form.claim_at = data.policy_claim.claim_at;
            form.intimation_at = data.policy_claim.intimation_at;
            form.survivor_name = data.policy_claim.survivor_name;
            form.contact_no = data.policy_claim.contact_no;
        });
};

defineExpose({ claimEdit: (id) => claimEdit(id) });

</script>
<template>
    <PrimaryButton @click="create" data-bs-toggle="modal" data-bs-target="#claimsLargeModal" v-if="create_mode">
        <i class='bx bx-plus mr-1'></i> Claim
    </PrimaryButton>

    <Modal :show="modal" @close="close">
        <form @submit.prevent="edit_mode ? update() : submit()">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">{{edit_mode ? 'Edit' : 'Add'}} Claim</h2>

                <p class="mt-1 text-sm text-gray-600">
                    <hr>
                </p>

                <div class="mt-6">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <InputLabel for="" value="Status" class="mb-1" />
                            <select id="input21" class="form-select" v-model="form.status">
                                <option value="progress">Progress</option>
                                <option value="settled">Settled</option>
                                <option value="close">Close</option>
                            </select>
                            <InputError :message="form.errors.status" />
                        </div>

                        <div class="col-md-6">
                            <InputLabel for="" value="Claim Date" class="mb-1" />
                            <VueDatePicker v-model="form.claim_at" :enable-time-picker="true" :show-time="true">
                            </VueDatePicker>
                            <InputError :message="form.errors.claim_at" />
                        </div>
                        <div class="col-md-6">
                            <InputLabel for="" value="Intimation Date" class="mb-1" />
                            <VueDatePicker v-model="form.intimation_at" :enable-time-picker="true" :show-time="true">
                            </VueDatePicker>
                            <InputError :message="form.errors.intimation_at" />
                        </div>
                        <div class="col-md-6">
                            <InputLabel for="" value="Survivor Name" class="mb-1" />
                            <input type="text" class="form-control" v-model="form.survivor_name">
                            <InputError :message="form.errors.survivor_name" />
                        </div>
                        <div class="col-md-6">
                            <InputLabel for="" value="Contact No" class="mb-1" />
                            <input type="text" class="form-control" v-model="form.contact_no">
                            <InputError :message="form.errors.contact_no" />
                        </div>
                        <div class="col-md-12">
                            <InputLabel for="" value="Description" class="mb-1" />
                            <textarea class="form-control" id="description" v-model="form.description"
                                rows="10"></textarea>
                            <InputError :message="form.errors.description" />
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="close"> Cancel </SecondaryButton>

                    <SuccessButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Save
                    </SuccessButton>
                </div>
            </div>
        </form>
    </Modal>
</template>
