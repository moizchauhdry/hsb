<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref, watch } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import axios from 'axios';
import SuccessButton from "@/Components/SuccessButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    policy: Object,
});

const permission = usePage().props.can;

const modal = ref(false);
const edit_mode = ref(false);

const users = ref([]);
const clients = ref([]);
const insurances = ref([]);
const agencies = ref([]);
const cobs = ref([]);
const departments = ref([]);

const form = useForm({
    current_step: 1,
    total_step: 3,
    policy_id: "",
    client_id: "",
    insurer_id: "",
    co_insurance: "",
    insurance_type: "",
    lead_type: "",
    department_id: "",
    policy_no: "",
    agency_id: "",
    agency_code: "",
    cob_id: "",
    policy_type: "",
    date_of_issuance: "",
    policy_period_start: "",
    policy_period_end: "",
    sum_insured: "",
    gross_premium: "",
    net_premium: "",
    cover_note_no: "",
    installment_plan: "",
    leader: "",
    leader_policy_no: "",
    branch: "",
    brokerage_amount: "",
    tax: "",
    percentage: "",
    brokerage_amount: "",
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
            departments.value = data.departments;
        });
};

const loadBusinessClasses = () => {
    // Fetch business classes based on the selected department ID
    // Here's an example of how you can do it with Axios:
    axios.get(`/policy/getDepartmentByBusinessClass/${form.department_id}`)
        .then(({ data }) => {
            // Assuming the response contains an array of business classes
            cobs.value = data.cobs;
        })
        .catch(error => {
            console.error('Error fetching business classes:', error);
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
    form.insurer_id = "";
    form.co_insurance = "";
    form.insurance_type = "";
    form.policy_no = "";
    form.agency_id = "";
    form.agency_code = "";
    form.cob_id = "";
    form.policy_type = "";
    form.date_of_issuance = "";
    form.policy_period_start = "";
    form.policy_period_end = "";
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
    form.rate_percentage = "";
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
            departments.value = data.departments;

            form.policy_id = data.policy.id;
            form.client_id = data.policy.client_id;
            form.insurer_id = data.policy.insurer_id;
            form.co_insurance = data.policy.co_insurance;
            form.insurance_type = data.policy.insurance_type;
            form.department_id = data.policy.department_id;
            form.lead_type = data.policy.lead_type;
            form.policy_no = data.policy.policy_no;
            form.agency_id = data.policy.agency_id;
            form.agency_code = data.policy.agency_code;
            form.cob_id = data.policy.cob_id;
            form.policy_type = data.policy.policy_type;
            form.date_of_issuance = data.policy.date_of_issuance;
            form.policy_period_start = data.policy.policy_period_start;
            form.policy_period_end = data.policy.policy_period_end;
            form.sum_insured = data.policy.sum_insured;
            form.gross_premium = data.policy.gross_premium;
            form.net_premium = data.policy.net_premium;
            form.cover_note_no = data.policy.cover_note_no;
            form.installment_plan = data.policy.installment_plan;
            form.leader = data.policy.leader;
            form.leader_policy_no = data.policy.leader_policy_no;
            form.branch = data.policy.branch;
            form.brokerage_amount = data.policy.brokerage_amount;
            form.tax = data.policy.tax;
            form.rate_percentage = data.policy.percentage;
            form.brokerage_amount = data.policy.brokerage_amount;
        });
};

defineExpose({ edit: (id) => edit(id) });

const calculatePercentage = () => {
    if (form.department_id === 1) {
        if (form.net_premium !== 0) {
            return (form.sum_insured / form.net_premium);
        } else {
            return null; // or handle division by zero error
        }
    } else if (form.department_id !== null) {
        if (form.gross_premium !== 0) {
            return (form.sum_insured / form.gross_premium);
        } else {
            return null; // or handle division by zero error
        }
    } else {
        return null;
    }
};

watch(() => calculatePercentage(), (newVal) => {
    form.rate_percentage = newVal;
});

let cobPercentage = null;

const selectBusinessClass = () => {
    axios.get(`/policy/getBusinessClassByPercent/${form.cob_id}`)
        .then(({ data }) => {

            cobPercentage = data.cobPercentage;
            if (form.gross_premium) {
                calculatePremium();
            }
        })
        .catch(error => {
            console.error('Error fetching percentage:', error);
        });
};

const calculatePremium = () => {
    if (cobPercentage && form.gross_premium) {
        form.brokerage_amount = form.gross_premium * cobPercentage / 100;
    }
};


watch(() => form.gross_premium, calculatePremium);
</script>

<template>
    <div class="col">

        <PrimaryButton @click="create" class="mr-1" v-if="permission.policy_create"> <i class="bx bx-plus text-lg"></i>
            Add Policy</PrimaryButton>

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

                                                    <select id="input21" class="form-select" v-model="form.insurer_id">
                                                        <template v-for="insurance in insurances" :key="insurance.id">
                                                            <option :value="insurance.id">{{ insurance.name }}
                                                            </option>
                                                        </template>
                                                    </select>
                                                    <InputError :message="form.errors.insurer_id" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input21" class="form-label">Insurance type</label>

                                                    <select id="input21" class="form-select"
                                                        v-model="form.insurance_type">
                                                        <option value="takaful">Takaful</option>
                                                        <option value="conventional">Conventional</option>
                                                    </select>
                                                    <InputError :message="form.errors.insurance_type" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input21" class="form-label">Lead type</label>

                                                    <select id="input21" class="form-select" v-model="form.lead_type">
                                                        <option value="1">Direct 100%</option>
                                                        <option value="2">Our lead</option>
                                                        <option value="3">Other lead</option>
                                                    </select>
                                                    <InputError :message="form.errors.lead_type" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Co insurance</label>

                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="form.co_insurance" :disabled="form.lead_type === '2'">

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
                                                    <label for="departmentSelect" class="form-label">Department</label>
                                                    <select id="departmentSelect" class="form-select"
                                                        v-model="form.department_id" @change="loadBusinessClasses">
                                                        <option v-for="department in departments"
                                                            :value="department.id">{{ department.name }}</option>
                                                    </select>
                                                    <InputError :message="form.errors.department_id" />
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="businessClassSelect" class="form-label">Business
                                                        Class</label>
                                                    <select id="businessClassSelect" class="form-select"
                                                        v-model="form.cob_id" @change="selectBusinessClass">
                                                        <option v-for="cob in cobs" :value="cob.id">{{ cob.class_name }}
                                                        </option>
                                                    </select>
                                                    <InputError :message="form.errors.cob_id" />
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="input21" class="form-label">Policy Type</label>

                                                    <select id="input21" class="form-select" v-model="form.policy_type">
                                                        <option value="new">New</option>
                                                        <option value="renewal">Renewal</option>
                                                        <option value="endorsement">Endorsement</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                    <InputError :message="form.errors.policy_type" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class="form-label">Date of Issuance</label>
                                                    <VueDatePicker v-model="form.date_of_issuance"
                                                        :enable-time-picker="false" :show-time="false">
                                                    </VueDatePicker>
                                                    <InputError :message="form.errors.date_of_issuance" />
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Inception Date</label>
                                                    <VueDatePicker v-model="form.policy_period_start"
                                                        :enable-time-picker="false" :show-time="false">
                                                    </VueDatePicker>

                                                    <InputError :message="form.errors.policy_period_start" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Expiry Date</label>
                                                    <VueDatePicker v-model="form.policy_period_end"
                                                        :enable-time-picker="false" :show-time="false">
                                                    </VueDatePicker>

                                                    <InputError :message="form.errors.policy_period_end" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Installment plan</label>
                                                    <select id="input21" class="form-select"
                                                        v-model="form.installment_plan">

                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                    </select>
                                                    <InputError :message="form.errors.installment_plan" />
                                                </div>
                                            </div>
                                        </template>

                                        <template v-if="form.current_step == 2">
                                            <div class="row g-2">
                                                <div class="border mb-4" id="partner">
                                                    <div class="table-responsive">
                                                        <table class="table"
                                                            style="margin-top: 10px; margin-bottom: 10px">

                                                            <tr style="text-align: center;">
                                                                <th>Sum insured</th>
                                                                <td>
                                                                    <input type="number" class="form-control"
                                                                        id="sum_insured" v-model="form.sum_insured">
                                                                    <InputError :message="form.errors.sum_insured" />
                                                                </td>
                                                            </tr>
                                                            <tr style="text-align: center;">
                                                                <th>Gross premium</th>
                                                                <td>
                                                                    <input type="number" class="form-control"
                                                                        id="gross_premium" v-model="form.gross_premium"
                                                                        @input="calculatePremium">
                                                                    <InputError :message="form.errors.gross_premium" />
                                                                </td>
                                                            </tr>
                                                            <tr style="text-align: center;">
                                                                <th>Net premium</th>
                                                                <td>
                                                                    <input type="number" class="form-control"
                                                                        id="net_premium" v-model="form.net_premium">
                                                                    <InputError :message="form.errors.net_premium" />
                                                                </td>
                                                            </tr>
                                                            <tr style="text-align: center;">
                                                                <th>Percentage</th>
                                                                <td>
                                                                    <input type="number" class="form-control"
                                                                        id="percentage" v-model="form.rate_percentage"
                                                                        readonly>
                                                                    <InputError :message="form.errors.rate_percentage" />
                                                                </td>
                                                            </tr>

                                                            <tr style="text-align: center;">
                                                                <th>HSB Profit</th>
                                                                <td>
                                                                    <input type="number" class="form-control"
                                                                        id="brokerage_amount"
                                                                        v-model="form.brokerage_amount">
                                                                    <InputError
                                                                        :message="form.errors.brokerage_amount" />
                                                                </td>
                                                            </tr>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <template v-if="form.current_step == 3">
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-capitalize">
                                                    <tr>
                                                        <th colspan="4" class="bg-primary text-white">
                                                            Policy Detail
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Client</th>
                                                        <td>{{ form.client_id }}</td>

                                                        <th>Insurance</th>
                                                        <td>{{ form.insurer_id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Insurance type</th>
                                                        <td> {{ form.insurance_type }}</td>

                                                        <th>Lead type</th>
                                                        <td> {{ form.lead_type }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Co Insurance</th>
                                                        <td>{{ form.co_insurance }}</td>

                                                        <th>Policy No.</th>
                                                        <td>{{ form.policy_no }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Agency</th>
                                                        <td>{{ form.agency_id }}</td>

                                                        <th>Department</th>
                                                        <td>
                                                            {{ form.department_id }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Class of Business</th>
                                                        <td>{{ form.cob_id }}</td>

                                                        <th>Policy Type</th>
                                                        <td>{{ form.policy_type }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th> Cover Note No </th>
                                                        <td> {{ form.cover_note_no }} </td>

                                                        <th>Installment Plan </th>
                                                        <td> {{ form.installment_plan }} </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-bordered text-capitalize">
                                                    <tr>
                                                        <th colspan="4" class="bg-primary text-white">
                                                            Policy Amount
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Sum insured </th>
                                                        <td> PKR {{ form.sum_insured.toLocaleString() }}
                                                        </td>

                                                        <th>Gross Premium </th>
                                                        <td> PKR {{ form.gross_premium.toLocaleString() }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Net Premium </th>
                                                        <td> PKR {{ form.net_premium.toLocaleString() }}
                                                        </td>

                                                        <th>Percentage</th>
                                                        <td> {{ form.rate_percentage }} % </td>
                                                    </tr>
                                                    <tr>
                                                        <th>HSB profit</th>
                                                        <td> PKR {{ form.brokerage_amount.toLocaleString()
                                                            }}</td>
                                                    </tr>
                                                </table>
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
                                                    v-if="form.current_step == 3">Save & Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>