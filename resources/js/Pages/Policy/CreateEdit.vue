<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import axios from 'axios';
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    policy: Object,
});

const modal = ref(false);
const edit_mode = ref(false);

const users = ref([]);
const clients = ref([]);
const insurances = ref([]);
const agencies = ref([]);
const cobs = ref([]);

const form = useForm({
    current_step: 1,
    total_step: 3,
    policy_id: "",
    client_id: "",
    insurance_id: "",
    co_insurance: "",
    takeful_type: "",
    policy_no: "",
    agency_id: "",
    agency_code: "",
    class_of_business_id: "",
    orignal_endorsment: "",
    date_of_insurance: "",
    policy_start_period: "",
    policy_end_period: "",
    sum_insured: "",
    gross_premium: "",
    net_premium: "",
    cover_note_no: "",
    installment_plan: "",
    leader: "",
    leader_policy_no: "",
    branch: "",
    brokerage_amount: "",
    user_id: "",
    tax: "",
    percentage: "",
});

const create = () => {
    modal.value = true;
    edit_mode.value = false;

    axios.get("/policy/create")
        .then(({ data }) => {
            users.value = data.users;
            clients.value = data.clients;
            insurances.value = data.insurances;
            agencies.value = data.agencies;
            cobs.value = data.cobs;
        });
};

const submit = () => {

    // console.log(edit_mode);

    if (!edit_mode.value) {
        form.post(route("policy.store"), {
            preserveScroll: true,
            onSuccess: () => {
                form.current_step++;
                if (form.current_step > 3) {
                    close();
                }
            },
            onError: () => error(),
            onFinish: () => { },
        });
    } else {
        form.post(route("policy.update"), {
            preserveScroll: true,
            onSuccess: () => {
                form.current_step++;
                if (form.current_step > 3) {
                    close();
                }
            },
            onError: () => error(),
            onFinish: () => { },
        });
    }
};

const error = () => {
    // alert('error');
};

const previousStep = () => {
    // console.log('previous');
    if (form.current_step > 1) {
        form.current_step--;
    }
}

const nextStep = () => {
    // console.log('next');
    if (form.current_step < form.total_step) {
        submit();
    }
};

const close = () => {
    modal.value = false;
    form.current_step = 1;
    form.errors = {};

    form.policy_id = "";
    form.client_id = "";
    form.insurance_id = "";
    form.co_insurance = "";
    form.takeful_type = "";
    form.policy_no = "";
    form.agency_id = "";
    form.agency_code = "";
    form.class_of_business_id = "";
    form.orignal_endorsment = "";
    form.date_of_insurance = "";
    form.policy_start_period = "";
    form.policy_end_period = "";
    form.sum_insured = "";
    form.gross_premium = "";
    form.net_premium = "";
    form.cover_note_no = "";
    form.installment_plan = "";
    form.leader = "";
    form.leader_policy_no = "";
    form.branch = "";
    form.brokerage_amount = "";
    form.user_id = "";
    form.tax = "";
    form.percentage = "";
};

const edit = (id) => {
    modal.value = true;
    edit_mode.value = true;

    axios.get(`/policy/edit/${id}`)
        .then(({ data }) => {

            users.value = data.users;
            clients.value = data.clients;
            insurances.value = data.insurances;
            agencies.value = data.agencies;
            cobs.value = data.cobs;

            form.policy_id = data.policy.id;
            form.client_id = data.policy.client_id;
            form.insurance_id = data.policy.insurance_id;
            form.co_insurance = data.policy.co_insurance;
            form.takeful_type = data.policy.takeful_type;
            form.policy_no = data.policy.policy_no;
            form.agency_id = data.policy.agency_id;
            form.agency_code = data.policy.agency_code;
            form.class_of_business_id = data.policy.class_of_business_id;
            form.orignal_endorsment = data.policy.orignal_endorsment;
            form.date_of_insurance = data.policy.date_of_insurance;
            form.policy_start_period = data.policy.policy_start_period;
            form.policy_end_period = data.policy.policy_end_period;
            form.sum_insured = data.policy.sum_insured;
            form.gross_premium = data.policy.gross_premium;
            form.net_premium = data.policy.net_premium;
            form.cover_note_no = data.policy.cover_note_no;
            form.installment_plan = data.policy.installment_plan;
            form.leader = data.policy.leader;
            form.leader_policy_no = data.policy.leader_policy_no;
            form.branch = data.policy.branch;
            form.brokerage_amount = data.policy.brokerage_amount;
            form.user_id = data.policy.user_id;
            form.tax = data.policy.tax;
            form.percentage = data.policy.percentage;
        });
};



defineExpose({ edit: (id) => edit(id) });
</script>

<template>
    <div class="col">

        <PrimaryButton @click="create">Add Policy</PrimaryButton>

        <div class="modal fade show" id="exampleLargeModal" tabindex="-1" aria-hidden="true" style="display: block;"
            v-if="modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form @submit.prevent="submit()">
                        <div class="modal-header">
                            <h5 class="modal-title">Policies</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                @click="close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="stepper1" class="bs-stepper">
                                <div class="card">

                                    <div class="card-header">
                                        <div class="d-lg-flex flex-lg-row align-items-lg-center justify-content-lg-between"
                                            role="tablist">
                                            <div class="step">
                                                <div class="step-trigger" role="tab">
                                                    <div class="bs-stepper-circle"
                                                        :class="{ 'bs-stepper-circle-active': form.current_step == 1 }">
                                                        1
                                                    </div>

                                                    <div class="">
                                                        <h5 class="mb-0 steper-title">Policy Info
                                                        </h5>
                                                        <p class="mb-0 steper-sub-title">Enter Policy
                                                            Details</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bs-stepper-line"></div>
                                            <div class="step">
                                                <div class="step-trigger" role="tab">
                                                    <div class="bs-stepper-circle"
                                                        :class="{ 'bs-stepper-circle-active': form.current_step == 2 }">
                                                        2
                                                    </div>
                                                    <div class="">
                                                        <h5 class="mb-0 steper-title">Policy Amount</h5>
                                                        <p class="mb-0 steper-sub-title">Setup
                                                            Policy Amount</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bs-stepper-line"></div>
                                            <div class="step">
                                                <div class="step-trigger" role="tab">
                                                    <div class="bs-stepper-circle"
                                                        :class="{ 'bs-stepper-circle-active': form.current_step == 3 }">
                                                        3
                                                    </div>
                                                    <div class="">
                                                        <h5 class="mb-0 steper-title">Confirmation</h5>
                                                        <p class="mb-0 steper-sub-title">Policy
                                                            Confirmation</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <template v-if="form.current_step == 1">
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Client</label>
                                                    <select id="input21" class="form-select" v-model="form.client_id">
                                                        <template v-for="client in clients" :key="client.id">
                                                            <option :value="client.id">{{ client.name }}
                                                            </option>
                                                        </template>
                                                    </select>

                                                    <InputError :message="form.errors.client_id" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input21" class="form-label">Insurer</label>

                                                    <select id="input21" class="form-select"
                                                        v-model="form.insurance_id">
                                                        <template v-for="insurance in insurances" :key="insurance.id">
                                                            <option :value="insurance.id">{{ insurance.name }}
                                                            </option>
                                                        </template>
                                                    </select>
                                                    <InputError :message="form.errors.insurance_id" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input21" class="form-label">Insurance type</label>

                                                    <select id="input21" class="form-select" v-model="form.takeful_type">
                                                        <option value="1">Direct 100%</option>
                                                        <option value="0">Our lead</option>
                                                    </select>
                                                    <InputError :message="form.errors.insurance_name" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Co insurance</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.co_insurance" :disabled="form.takeful_type === '0'">

                                                    <InputError :message="form.errors.co_insurance" />
                                                </div>

                                               
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Policy No</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.policy_no">

                                                    <InputError :message="form.errors.policy_no" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Cover note no</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.cover_note_no">

                                                    <InputError :message="form.errors.cover_note_no" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input21" class="form-label">Agency</label>

                                                    <select id="input21" class="form-select" v-model="form.agency_id">
                                                        <template v-for="agency in agencies" :key="agency.id">
                                                            <option :value="agency.id">{{ agency.name }}</option>
                                                        </template>
                                                    </select>
                                                    <InputError :message="form.errors.agency_id" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Agency Code</label>
                                                    <input type="number" class="form-control" id="input13"
                                                        placeholder="" v-model="form.agency_code">
                                                    <InputError :message="form.errors.agency_code" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input21" class="form-label">Business Class</label>

                                                    <select id="input21" class="form-select"
                                                        v-model="form.class_of_business_id">
                                                        <template v-for="cob in cobs" :key="cob.id">
                                                            <option :value="cob.id">{{ cob.class_name }}</option>
                                                        </template>
                                                    </select>
                                                    <InputError :message="form.errors.class_of_business_id" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input21" class="form-label">New/Renewal/Endorsment</label>

                                                    <select id="input21" class="form-select"
                                                        v-model="form.orignal_endorsment">

                                                        <option value="new">New</option>
                                                        <option value="renewal">Renewal</option>
                                                        <option value="endorsment">Endorsment</option>
                                                    </select>
                                                    <InputError :message="form.errors.orignal_endorsment" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class="form-label">Date of Insurer</label>
                                                    <VueDatePicker v-model="form.date_of_insurance" :teleport="true" :show-time="false">
                                                    </VueDatePicker>
                                                    <InputError :message="form.errors.date_of_insurance" />
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Policy start
                                                        period</label>
                                                    <VueDatePicker v-model="form.policy_start_period" :teleport="true" :show-time="false">
                                                    </VueDatePicker>

                                                    <InputError :message="form.errors.policy_start_period" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Policy end
                                                        period</label>
                                                    <VueDatePicker v-model="form.policy_end_period" :teleport="true" :show-time="false">
                                                    </VueDatePicker>

                                                    <InputError :message="form.errors.policy_end_period" />
                                                </div>
                                            </div>
                                        </template>

                                        <template v-if="form.current_step == 2">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label for="input13" class="form-label">Installment plan</label>
                                                    <select id="input21" class="form-select"
                                                        v-model="form.installment_plan">

                                                        <option value="2">2</option>
                                                        <option value="4">4</option>
                                                    </select>
                                                    <InputError :message="form.errors.installment_plan" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="input21" class="form-label">User</label>

                                                    <select id="input21" class="form-select" v-model="form.user_id">
                                                        <template v-for="user in users" :key="user.id">
                                                            <option :value="user.id">{{ user.name }}</option>
                                                        </template>
                                                    </select>
                                                    <InputError :message="form.errors.user_id" />
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Label</th>
                                                                    <th>Input</th>
                                                                    <th>Error</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td><label for="sum_insured" class="form-label">Sum insured</label></td>
                                                                    <td><input type="number" class="form-control" id="sum_insured" v-model="form.sum_insured"></td>
                                                                    <td><InputError :message="form.errors.sum_insured" /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><label for="gross_premium" class="form-label">Gross premium</label></td>
                                                                    <td><input type="number" class="form-control" id="gross_premium" v-model="form.gross_premium"></td>
                                                                    <td><InputError :message="form.errors.gross_premium" /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><label for="net_premium" class="form-label">Net premium</label></td>
                                                                    <td><input type="number" class="form-control" id="net_premium" v-model="form.net_premium"></td>
                                                                    <td><InputError :message="form.errors.net_premium" /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><label for="brokerage_amount" class="form-label">Brokerage amount</label></td>
                                                                    <td><input type="number" class="form-control" id="brokerage_amount" v-model="form.brokerage_amount"></td>
                                                                    <td><InputError :message="form.errors.brokerage_amount" /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><label for="tax" class="form-label">Tax</label></td>
                                                                    <td><input type="number" class="form-control" id="tax" v-model="form.tax"></td>
                                                                    <td><InputError :message="form.errors.tax" /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><label for="percentage" class="form-label">Percentage</label></td>
                                                                    <td><input type="number" class="form-control" id="percentage" v-model="form.percentage"></td>
                                                                    <td><InputError :message="form.errors.percentage" /></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div> 
                                            </div>
                                        </template>

                                        <template v-if="form.current_step == 3">
                                            <h6>Please finalize data.</h6>
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                <label for="input13" class="form-label">Client</label>
                                                <div class="form-control">
                                                    {{ form.client_id }}
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label for="input21" class="form-label">Insurer</label>
                                                <div class="form-control">
                                                    {{ form.insurance_id }}
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label for="input21" class="form-label">Insurance type</label>
                                                <div class="form-control">
                                                    {{ form.takeful_type }}
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label for="input13" class="form-label">Co insurance</label>
                                                <div class="form-control">
                                                    {{ form.co_insurance }}
                                                </div>
                                                </div>

                                                <div class="col-md-4">
                                                <label for="input13" class="form-label">Policy No</label>
                                                <div class="form-control">
                                                    {{ form.policy_no }}
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label for="input13" class="form-label">Cover note no</label>
                                                <div class="form-control">
                                                    {{ form.cover_note_no }}
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label for="input21" class="form-label">Agency</label>
                                                <div class="form-control">
                                                    {{ form.agency_id }}
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label for="input13" class="form-label">Agency Code</label>
                                                <div class="form-control">
                                                    {{ form.agency_code }}
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label for="input21" class="form-label">Business Class</label>
                                                <div class="form-control">
                                                    {{ form.class_of_business_id }}
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label for="input21" class="form-label">New/Renewal/Endorsment</label>
                                                <div class="form-control">
                                                    {{ form.orignal_endorsment }}
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label for="" class="form-label">Date of Insurer</label>
                                                <div class="form-control">
                                                    {{ form.date_of_insurance }}
                                                </div>
                                                </div>

                                                <div class="col-md-4">
                                                <label for="input13" class="form-label">Policy start period</label>
                                                <div class="form-control">
                                                    {{ form.policy_start_period }}
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label for="input13" class="form-label">Policy end period</label>
                                                <div class="form-control">
                                                    {{ form.policy_end_period }}
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Installment plan</label>
                                                    <div class="form-control">
                                                        {{ form.installment_plan }}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input21" class="form-label">User</label>
                                                    <div class="form-control">
                                                        {{ form.user_id }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Label</th>
                                                                    <th>Input</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td><label for="sum_insured" class="form-label">Sum insured</label></td>
                                                                    <td>{{ form.sum_insured }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><label for="brokerage_amount" class="form-label">Brokerage amount</label></td>
                                                                    <td>{{ form.brokerage_amount }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><label for="tax" class="form-label">Tax</label></td>
                                                                    <td>{{ form.tax }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><label for="percentage" class="form-label">Percentage</label></td>
                                                                    <td>{{ form.percentage }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><label for="gross_premium" class="form-label">Gross premium</label></td>
                                                                    <td>{{ form.gross_premium }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><label for="net_premium" class="form-label">Net premium</label></td>
                                                                    <td>{{ form.net_premium }}</td>
                                                                   
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div> 
                                            </div>
                                        </template>

                                    </div>

                                    <div class="card-footer">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center gap-3">

                                                <button class="btn btn-outline-secondary px-4" type="button"
                                                    @click="previousStep" v-if="[2, 3].includes(form.current_step)"><i
                                                        class='bx bx-left-arrow-alt me-2'></i>Previous</button>

                                                <button class="btn btn-primary px-4" type="button" @click="nextStep"
                                                    v-if="[1, 2].includes(form.current_step)">Next<i
                                                        class='bx bx-right-arrow-alt ms-2'></i></button>

                                                <button class="btn btn-success px-4"
                                                    v-if="form.current_step == 3">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                                @click="close">Close</button>

                            <button type="submit" class="btn btn-primary btn-sm">
                                {{ edit_mode ? 'Save & Update' : 'Save & Submit' }}</button>
                        </div> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.bs-stepper-circle-active {
    width: 2.7rem;
    height: 2.7rem;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    background-color: #008cff;
    border-radius: 50%;
}
</style>