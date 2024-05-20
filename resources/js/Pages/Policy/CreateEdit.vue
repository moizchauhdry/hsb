<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import axios from 'axios';

defineProps({
    policy: Object,
});

const modal = ref(false);
const edit_mode = ref(false);
const policy = usePage().props.policy;

const users = ref([]);
const clients = ref([]);
const insurances = ref([]);
const agencies = ref([]);
const cobs = ref([]);


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

const form = useForm({
    current_step: 1,
    total_step: 3,
    policy_id: policy?.id,
    client_id: policy?.client_id,
    insurance_id: policy?.insurance_id,
    co_insurance: policy?.co_insurance,
    takeful_type: policy?.takeful_type,
    policy_no: policy?.policy_no,
    agency_id: policy?.agency_id,
    agency_code: policy?.agency_code,
    class_of_business_id: policy?.class_of_business_id,
    orignal_endorsment: policy?.orignal_endorsment,
    date_of_insurance: policy?.date_of_insurance,
    policy_start_period: policy?.policy_start_period,
    policy_end_period: policy?.policy_end_period,
    sum_insured: policy?.sum_insured,
    gross_premium: policy?.gross_premium,
    net_premium: policy?.net_premium,
    cover_note_no: policy?.cover_note_no,
    installment_plan: policy?.installment_plan,
    leader: policy?.leader,
    leader_policy_no: policy?.leader_policy_no,
    branch: policy?.branch,
    brokerage_amount: policy?.brokerage_amount,
    user_id: policy?.user_id,
    tax: policy?.tax,
    percentage: policy?.percentage,
});


const submit = () => {
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
};

const error = () => {
    // alert('error');
};

const close = () => {
    modal.value = false;
    form.reset();
};

const previousStep = () => {
    console.log('previous');
    if (form.current_step > 1) {
        form.current_step--;
    }
}

const nextStep = () => {
    console.log('next');
    if (form.current_step < form.total_step) {
        submit();
    }
}
</script>

<template>
    <div class="col">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleLargeModal"
            @click="create"><i class="bx bx-plus"></i>Add</button>

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
                                                        <h5 class="mb-0 steper-title">Personal Info
                                                        </h5>
                                                        <p class="mb-0 steper-sub-title">Enter Your
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
                                                        <h5 class="mb-0 steper-title">Account
                                                            Details</h5>
                                                        <p class="mb-0 steper-sub-title">Setup
                                                            Account Details</p>
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
                                                        <h5 class="mb-0 steper-title">Education</h5>
                                                        <p class="mb-0 steper-sub-title">Education
                                                            Details</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <template v-if="form.current_step == 1">
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Client Name</label>
                                                    <select id="input21" class="form-select" v-model="form.client_id">
                                                        <template v-for="client in clients" :key="client.id">
                                                            <option :value="client.id">{{ client.name }}
                                                            </option>
                                                        </template>
                                                    </select>

                                                    <InputError :message="form.errors.client_id" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input21" class="form-label">Insurance</label>

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
                                                    <label for="input13" class="form-label">Co insurance</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.co_insurance">

                                                    <InputError :message="form.errors.co_insurance" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input21" class="form-label">Takefull type</label>

                                                    <select id="input21" class="form-select"
                                                        v-model="form.takeful_type">

                                                        <option value="1">Direct 100%</option>
                                                        <option value="0">Our lead</option>
                                                    </select>
                                                    <InputError :message="form.errors.insurance_name" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Policy No</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.policy_no">

                                                    <InputError :message="form.errors.policy_no" />
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

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.agency_code">

                                                    <InputError :message="form.errors.agency_code" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input21" class="form-label">Class of
                                                        business</label>

                                                    <select id="input21" class="form-select"
                                                        v-model="form.class_of_business_id">
                                                        <template v-for="cob in cobs" :key="cob.id">
                                                            <option :value="cob.id">{{ cob.b_class_name }}</option>
                                                        </template>
                                                    </select>
                                                    <InputError :message="form.errors.class_of_business_id" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input21" class="form-label">Orignal/Endorsment</label>

                                                    <select id="input21" class="form-select"
                                                        v-model="form.orignal_endorsment">

                                                        <option value="new">New</option>
                                                        <option value="renewal">Renewal</option>
                                                    </select>
                                                    <InputError :message="form.errors.orignal_endorsment" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class="form-label">Date of insurance</label>
                                                    <VueDatePicker v-model="form.date_of_insurance" :teleport="true">
                                                    </VueDatePicker>

                                                    <InputError :message="form.errors.date_of_insurance" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Policy start
                                                        period</label>
                                                    <VueDatePicker v-model="form.policy_start_period" :teleport="true">
                                                    </VueDatePicker>

                                                    <InputError :message="form.errors.policy_start_period" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Policy end
                                                        period</label>
                                                    <VueDatePicker v-model="form.policy_end_period" :teleport="true">
                                                    </VueDatePicker>

                                                    <InputError :message="form.errors.policy_end_period" />
                                                </div>
                                            </div>
                                        </template>

                                        <template v-if="form.current_step == 2">
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Sum insured</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.sum_insured">

                                                    <InputError :message="form.errors.sum_insured" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Gross premium</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.gross_premium">

                                                    <InputError :message="form.errors.gross_premium" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Net premium</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.net_premium">

                                                    <InputError :message="form.errors.net_premium" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Cover note no</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.cover_note_no">

                                                    <InputError :message="form.errors.cover_note_no" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Installment plan</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.installment_plan">

                                                    <InputError :message="form.errors.installment_plan" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Leader</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.leader">

                                                    <InputError :message="form.errors.leader" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Leader policy no</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.leader_policy_no">

                                                    <InputError :message="form.errors.leader_policy_no" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Branch</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.branch">

                                                    <InputError :message="form.errors.branch" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Brokerage amount</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.brokerage_amount">

                                                    <InputError :message="form.errors.brokerage_amount" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input21" class="form-label">User</label>

                                                    <select id="input21" class="form-select" v-model="form.user_id">
                                                        <template v-for="user in users" :key="user.id">
                                                            <option :value="user.id">{{ user.name }}</option>
                                                        </template>
                                                    </select>
                                                    <InputError :message="form.errors.user_id" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Tax</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.tax">

                                                    <InputError :message="form.errors.tax" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Percentage</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.percentage">

                                                    <InputError :message="form.errors.percentage" />
                                                </div>
                                            </div>
                                        </template>

                                        <template v-if="form.current_step == 3">
                                            <h6>Please finalize data.</h6>
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