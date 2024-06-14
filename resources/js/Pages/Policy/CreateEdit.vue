<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref, watch } from "vue";
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
const departments = ref([]);

const form = useForm({
    current_step: 1,
    total_step: 3,
    policy_id: "",
    client_id: "",
    insurance_id: "",
    co_insurance: "",
    takeful_type: "",
    lead_type: "",
    department_id: "",
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
    tax: "",
    percentage: "",
    hsb_profit: "",
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
            departments.value = data.departments;

            form.policy_id = data.policy.id;
            form.client_id = data.policy.client_id;
            form.insurance_id = data.policy.insurance_id;
            form.co_insurance = data.policy.co_insurance;
            form.takeful_type = data.policy.takeful_type;
            form.department_id = data.policy.department_id;
            form.lead_type = data.policy.lead_type;
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
            form.tax = data.policy.tax;
            form.percentage = data.policy.percentage;
            form.hsb_profit = data.policy.hsb_profit;
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
    form.percentage = newVal;
});

let cobPercentage = null;

const selectBusinessClass = () => {
    axios.get(`/policy/getBusinessClassByPercent/${form.class_of_business_id}`)
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
        form.hsb_profit = form.gross_premium * cobPercentage / 100;
    }
};


watch(() => form.gross_premium, calculatePremium);
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

                                                    <select id="input21" class="form-select"
                                                        v-model="form.takeful_type">
                                                        <option value="1">Takaful</option>
                                                        <option value="2">Conventional</option>
                                                    </select>
                                                    <InputError :message="form.errors.takeful_type" />
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
                                                        v-model="form.class_of_business_id"
                                                        @change="selectBusinessClass">
                                                        <option v-for="cob in cobs" :value="cob.id">{{ cob.class_name }}
                                                        </option>
                                                    </select>
                                                    <InputError :message="form.errors.class_of_business_id" />
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="input21"
                                                        class="form-label">New/Renewal/Endorsment</label>

                                                    <select id="input21" class="form-select"
                                                        v-model="form.orignal_endorsment">

                                                        <option value="new">New</option>
                                                        <option value="renewal">Renewal</option>
                                                        <option value="endorsment">Endorsment</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                    <InputError :message="form.errors.orignal_endorsment" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class="form-label">Date of Insurer</label>
                                                    <VueDatePicker v-model="form.date_of_insurance"
                                                        :enable-time-picker="false" :show-time="false">
                                                    </VueDatePicker>
                                                    <InputError :message="form.errors.date_of_insurance" />
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Policy start
                                                        period</label>
                                                    <VueDatePicker v-model="form.policy_start_period"
                                                        :enable-time-picker="false" :show-time="false">
                                                    </VueDatePicker>

                                                    <InputError :message="form.errors.policy_start_period" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Policy end
                                                        period</label>
                                                    <VueDatePicker v-model="form.policy_end_period"
                                                        :enable-time-picker="false" :show-time="false">
                                                    </VueDatePicker>

                                                    <InputError :message="form.errors.policy_end_period" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="input13" class="form-label">Installment plan</label>
                                                    <select id="input21" class="form-select"
                                                        v-model="form.installment_plan">

                                                        <option value="2">2</option>
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
                                                                        id="percentage" v-model="form.percentage"
                                                                        readonly>
                                                                    <InputError :message="form.errors.percentage" />
                                                                </td>
                                                            </tr>

                                                            <tr style="text-align: center;">
                                                                <th>HSB Profit</th>
                                                                <td>
                                                                    <input type="number" class="form-control"
                                                                        id="hsb_profit" v-model="form.hsb_profit">
                                                                    <InputError :message="form.errors.hsb_profit" />
                                                                </td>
                                                            </tr>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <template v-if="form.current_step == 3">
                                            <h6>Please finalize data.</h6>
                                            <div class="table-responsive" style="overflow-x: hidden;">
                                                <div class="container-fluid">
                                                    <div class="border mb-4" id="partner">
                                                        <div style="background-color: #037DE2">
                                                            <h5 style="text-align: center" class="w-auto title">Policy
                                                                Info</h5>
                                                        </div>

                                                        <div class="row">
                                                            <table class="table table-bordered"
                                                                style="margin-left: 18px;">
                                                                <tr>
                                                                    <th>Client</th>
                                                                    <td>{{ form.client_id }}</td>

                                                                    <th>Insurance</th>
                                                                    <td>{{ form.insurance_id }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Insurance type</th>
                                                                    <td> {{ form.takeful_type }}</td>

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
                                                                    <th>Class business</th>
                                                                    <td>{{ form.class_of_business_id }}</td>

                                                                    <th>New/Renewal/Endorsment</th>
                                                                    <td>{{ form.orignal_endorsment }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th> Cover Note No </th>
                                                                    <td> {{ form.cover_note_no }} </td>

                                                                    <th>Installment Plan </th>
                                                                    <td> {{ form.installment_plan }} </td>
                                                                </tr>
                                                                <tr>

                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="border mb-4" id="partner">
                                                        <div style="background-color: #037DE2">
                                                            <h5 style="text-align: center" class="w-auto title">Policy
                                                                Amount</h5>
                                                        </div>
                                                        <div class="row">
                                                            <table class="table table-bordered"
                                                                style="margin-left: 18px;">
                                                                <tr>
                                                                    <th>Sum insured </th>
                                                                    <td> PKR {{ form.sum_insured.toLocaleString() }} </td>

                                                                    <th>Gross Premium </th>
                                                                    <td> PKR {{ form.gross_premium.toLocaleString() }} </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Net Premium </th>
                                                                    <td> PKR {{ form.net_premium.toLocaleString() }} </td>

                                                                    <th>Percentage</th>
                                                                    <td> {{ form.percentage }} % </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>HSB profit</th>
                                                                    <td> PKR {{ form.hsb_profit.toLocaleString() }}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
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

table,
th,
td {
    padding: 3px !important;
    font-size: 14px !important;
}

#lc-table td {
    border: 1px solid rgb(194, 189, 189) !important;
    text-align: left !important;
}

#lc-table th {
    border: 1px solid rgb(194, 189, 189) !important;
    text-align: left !important;
}

table {
    width: 100%;
    border-collapse: collapse;
}

fieldset legend {
    color: black;
    font-weight: 500;
    font-size: 18px;
}

fieldset.border {
    border: 2px solid #037DE2 !important;
    border-radius: 5px !important;
}

fieldset.member-border {
    border: 2px solid blue !important;
    border-radius: 5px !important;
}

.mb-4 {
    margin-bottom: 20px
}

.custom-image-preview {
    width: 100px;
    height: 100px
}

.table-bordered>:not(caption)>* {
    border-width: 0;
}

h5.w-auto.title {
    text-align: center;
    color: #fff;
    height: 34px;
    padding: 5px;
    font-size: 18px;
}
</style>