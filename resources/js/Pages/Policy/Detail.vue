<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import Uploads from "./Uploads.vue";
import AdditionalNotes from "./AdditionalNotes.vue";
import Claim from "./Claim.vue";
import ClaimNote from "./Claim/Notes.vue";
import ClaimUpload from "./Claim/Upload.vue";
import ClaimEdit from "./Claim/Edit.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Paginate from "@/Components/Paginate.vue";

const { props } = usePage();

const isOpen = ref(new Array(props.policyNotes.length).fill(false)); // Array to store isOpen states

defineProps({
    policy: Object,
    policyInstallment: Array(),
    policyNotes: Object,
    policyClaims: Array,
    policyUploads: Array,
    assetUrl: Object,
    // Add a new prop for single-open behavior
    desiredSingleOpenBehavior: {
        type: Boolean,
        default: false, // Set default to false (optional)
    },
});

const edit_mode = ref(false);

const toggleAccordion = (index) => {
    // Toggle the isOpen state for the clicked item only
    isOpen.value[index] = !isOpen.value[index];

    // Optionally close other accordion items (if single-open behavior is desired)
    if (props.desiredSingleOpenBehavior) {
        isOpen.value.fill(false, 0, index); // Close previous items
        isOpen.value.fill(false, index + 1); // Close subsequent items
    }
};

const forms = [];

if (props.policyInstallment && props.policyInstallment.length > 0) {
    for (let i = 0; i < Number(props.policy.installment_plan); i++) {
        const formFields = i < props.policyInstallment.length ? {
            due_date: props.policyInstallment[i].due_date || "",
            gross_premium: props.policyInstallment[i].gross_premium || "",
            net_premium: props.policyInstallment[i].net_premium || "",
            payment_status: props.policyInstallment[i].payment_status || "",
            edit_mode: true,
        } : {
            due_date: "",
            gross_premium: "",
            net_premium: "",
            payment_status: "",
            edit_mode: false,
        };
        const form = useForm(formFields);
        forms.push(form);
    }
} else {
    for (let i = 0; i < Number(props.policy.installment_plan); i++) {
        const initialState = {
            due_date: "",
            gross_premium: "",
            net_premium: "",
            payment_status: "",
            edit_mode: false,
        };
        const form = useForm(initialState);
        forms.push(form);
    }
}



const claim_note_ref = ref(null);
const claim_upload_ref = ref(null);
const claim_edit_ref = ref(null);

const claimNote = (id) => {
    claim_note_ref.value.claimNote(id)
};

const claimUpload = (id) => {
    claim_upload_ref.value.claimUpload(id)
};

const claimEdit = (id) => {
    claim_edit_ref.value.claimEdit(id)
};


const submit = () => {
    if (forms && forms.length > 0) {
        forms.forEach(proxyData => {
    
            if (!proxyData) return;
            const data = { ...proxyData };

            if (data.due_date !== '' && data.gross_premium !== '' && data.net_premium !== '' && data.payment_status !== '') {
                
                const formFields = {
                    policy_id: JSON.parse(JSON.stringify((props.policy.id))) ?? "",
                    due_date: data.due_date || "",
                    gross_premium: data.gross_premium || "",
                    net_premium: data.net_premium || "",
                    payment_status: data.payment_status || "",
                };

                const form = useForm(formFields);
                form.post(route("policy.installmentPlan"), {
                    preserveScroll: true,
                    onError: () => error(),
                    onFinish: () => { },
                });
            }
        });
    }
};


const error = () => {
    // alert('error');
};


</script>

<template>

    <Head title="Policies" />
    <AuthenticatedLayout>
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Policies </div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;">
                                        <i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Policies Detail</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-lg-flex align-items-center mb-4 gap-3">
                            <div class="ms-auto d-flex" style="width: 310px;">
                                <Uploads v-bind="$props"></Uploads>
                                <AdditionalNotes v-bind="$props"></AdditionalNotes>
                                <Claim v-bind="$props"></Claim>
                            </div>
                        </div>
                        <div class="table-responsive" style="overflow-x: hidden;">
                            <div class="container-fluid">
                                <!-- POLICY INFO-->
                                <div class="border mb-4" id="partner">
                                    <div style="background-color: #037DE2">
                                        <h5 style="text-align: center" class="w-auto title">Policy Info</h5>
                                    </div>

                                    <div class="row">
                                        <table class="table table-bordered" style="margin-left: 18px;">
                                            <tr>
                                                <th>ID</th>
                                                <td>{{ policy.id }}</td>
                                                <th>Client</th>
                                                <td>{{ policy.client_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Insurance</th>
                                                <td>{{ policy.insurance_id }}</td>

                                                <th>Insurance type</th>
                                                <td v-if="policy.takeful_type == 1">Takaful</td>
                                                <td v-if="policy.takeful_type == 2">Conventional</td>
                                            </tr>
                                            <tr>
                                                <th>Lead type</th>
                                                <td v-if="policy.lead_type == 1">Direct 100%</td>
                                                <td v-if="policy.lead_type == 2">Our lead</td>
                                                <td v-if="policy.lead_type == 3">Other lead</td>

                                                <th v-if="policy.lead_type == 1 || policy.lead_type == 3">Co Insurance
                                                </th>
                                                <td v-if="policy.lead_type == 1 || policy.lead_type == 3">{{
                                                    policy.co_insurance }}</td>
                                            </tr>
                                            <tr>
                                                <th>Policy No.</th>
                                                <td>{{ policy.policy_no }}</td>

                                                <th>Agency</th>
                                                <td>{{ policy.agency_id }}</td>
                                            </tr>
                                            <tr>
                                                <th>Agency code</th>
                                                <td>{{ policy.agency_code }}</td>

                                                <th>Business class</th>
                                                <td>{{ policy.class_of_business_id }}</td>
                                            </tr>
                                            <tr>
                                                <th>New/Renewal/Endorsment</th>
                                                <td>{{ policy.orignal_endorsment }}</td>

                                                <th>Date of insurance</th>
                                                <td>{{ policy.date_of_insurance }}</td>
                                            </tr>
                                            <tr>
                                                <th>Policy start period</th>
                                                <td>{{ policy.policy_start_period }}</td>

                                                <th>Policy end period</th>
                                                <td>{{ policy.policy_end_period }}</td>
                                            </tr>
                                            <tr>
                                                <th> Cover Note No </th>
                                                <td> {{ policy.cover_note_no }} </td>

                                                <th>Installment Plan </th>
                                                <td> {{ policy.installment_plan }} </td>
                                            </tr>
                                            <tr>
                                                <th>User</th>
                                                <td> {{ policy.user_id }} </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <!-- POLICY AMOUNT-->
                                <div class="border mb-4" id="partner">
                                    <div style="background-color: #037DE2">
                                        <h5 style="text-align: center" class="w-auto title">Policy Amount</h5>
                                    </div>
                                    <div class="row">
                                        <table class="table table-bordered" style="margin-left: 18px;">
                                            <tr>
                                                <th>Sum insured </th>
                                                <td> PKR {{ policy.sum_insured }} </td>

                                                <th>Gross Premium </th>
                                                <td> PKR {{ policy.gross_premium }} </td>
                                            </tr>
                                            <tr>
                                                <th>Net Premium </th>
                                                <td> PKR {{ policy.net_premium }} </td>

                                                <th>Percentage</th>
                                                <td> {{ policy.percentage }} % </td>
                                            </tr>
                                            <tr>
                                                <th>HSB profit</th>
                                                <td> PKR {{ policy.hsb_profit }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <!-- POLICY INSTALLMENT PLAN-->
                                <div class="border mb-4" id="partner">
                                    <div style="background-color: #037DE2">
                                        <h5 style="text-align: center" class="w-auto title">Installment Plan</h5>
                                    </div>
                                    <div class="col-md-12" v-if="policy.installment_plan == 2">
                                        <form @submit.prevent="edit_mode ? update() : submit()">
                                            <div class="row"
                                                style="margin-left: 0px;margin-right: 0px;margin-bottom: 20px;">
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Due date</label>
                                                    <VueDatePicker v-model="forms[0].due_date"
                                                        :enable-time-picker="false" :show-time="false">
                                                    </VueDatePicker>
                                                    <InputError :message="forms[0].errors.due_date" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Gross premium</label>
                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="forms[0].gross_premium">
                                                    <InputError :message="forms[0].errors.gross_premium" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Net premium</label>
                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="forms[0].net_premium">
                                                    <InputError :message="forms[0].errors.net_premium" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Payment status</label>
                                                    <select id="input21" class="form-select"
                                                        v-model="forms[0].payment_status">

                                                        <option value="pending">Pending</option>
                                                        <option value="paid">Paid</option>
                                                        <option value="un-paid">Un-paid</option>
                                                    </select>
                                                    <InputError :message="forms[0].errors.payment_status" />
                                                </div>
                                            </div>
                                            <div class="modal-footer"
                                                style="margin-left: 0px;margin-right: 17px;margin-bottom: 20px;">

                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    {{ edit_mode ? 'Save & Update' : 'Save' }}</button>
                                            </div>
                                        </form>
                                        <form @submit.prevent="edit_mode ? update() : submit()">

                                            <div class="row"
                                                style="margin-left: 0px;margin-right: 0px;margin-bottom: 20px;">
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Due date</label>
                                                    <VueDatePicker v-model="forms[1].due_date"
                                                        :enable-time-picker="false" :show-time="false">
                                                    </VueDatePicker>
                                                    <InputError :message="forms[1].errors.due_date" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Gross premium</label>
                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="forms[1].gross_premium">
                                                    <InputError :message="forms[1].errors.gross_premium" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Net premium</label>
                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="forms[1].net_premium">
                                                    <InputError :message="forms[1].errors.net_premium" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Payment status</label>
                                                    <select id="input21" class="form-select"
                                                        v-model="forms[1].payment_status">

                                                        <option value="pending">Pending</option>
                                                        <option value="paid">Paid</option>
                                                        <option value="un-paid">Un-paid</option>
                                                    </select>
                                                    <InputError :message="forms[1].errors.payment_status" />
                                                </div>
                                            </div>

                                            <div class="modal-footer"
                                                style="margin-left: 0px;margin-right: 17px;margin-bottom: 20px;">

                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    {{ edit_mode ? 'Save & Update' : 'Save' }}</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-12" v-if="policy.installment_plan == 4">
                                        <form @submit.prevent="forms[0].edit_mode ? update() : submit()">
                                            <div class="row"
                                                style="margin-left: 0px;margin-right: 0px;margin-bottom: 20px;">
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Due date</label>
                                                    <VueDatePicker v-model="forms[0].due_date"
                                                        :enable-time-picker="false" :show-time="false">
                                                    </VueDatePicker>
                                                    <InputError :message="forms[0].errors.due_date" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Gross premium</label>
                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="forms[0].gross_premium">
                                                    <InputError :message="forms[0].errors.gross_premium" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Net premium</label>
                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="forms[0].net_premium">
                                                    <InputError :message="forms[0].errors.net_premium" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Payment status</label>
                                                    <select id="input21" class="form-select"
                                                        v-model="forms[0].payment_status">

                                                        <option value="pending">Pending</option>
                                                        <option value="paid">Paid</option>
                                                        <option value="un-paid">Un-paid</option>
                                                    </select>
                                                    <InputError :message="forms[0].errors.payment_status" />
                                                </div>
                                            </div>
                                            <div class="modal-footer"
                                                style="margin-left: 0px;margin-right: 17px;margin-bottom: 20px;">

                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    {{ forms[0].edit_mode ? 'Save & Update' : 'Save' }}</button>
                                            </div>
                                        </form>
                                        <form @submit.prevent="forms[1].edit_mode ? update() : submit()">

                                            <div class="row"
                                                style="margin-left: 0px;margin-right: 0px;margin-bottom: 20px;">
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Due date</label>
                                                    <VueDatePicker v-model="forms[1].due_date"
                                                        :enable-time-picker="false" :show-time="false">
                                                    </VueDatePicker>
                                                    <InputError :message="forms[1].errors.due_date" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Gross premium</label>
                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="forms[1].gross_premium">
                                                    <InputError :message="forms[1].errors.gross_premium" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Net premium</label>
                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="forms[1].net_premium">
                                                    <InputError :message="forms[1].errors.net_premium" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Payment status</label>
                                                    <select id="input21" class="form-select"
                                                        v-model="forms[1].payment_status">

                                                        <option value="pending">Pending</option>
                                                        <option value="paid">Paid</option>
                                                        <option value="un-paid">Un-paid</option>
                                                    </select>
                                                    <InputError :message="forms[1].errors.payment_status" />
                                                </div>
                                            </div>
                                            <div class="modal-footer"
                                                style="margin-left: 0px;margin-right: 17px;margin-bottom: 20px;">

                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    {{ forms[1].edit_mode ? 'Save & Update' : 'Save' }}</button>
                                            </div>
                                        </form>
                                        <form @submit.prevent="forms[2].edit_mode ? update() : submit()">
                                            <div class="row"
                                                style="margin-left: 0px;margin-right: 0px;margin-bottom: 20px;">
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Due date</label>
                                                    <VueDatePicker v-model="forms[2].due_date"
                                                        :enable-time-picker="false" :show-time="false">
                                                    </VueDatePicker>
                                                    <InputError :message="forms[2].errors.due_date" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Gross premium</label>
                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="forms[2].gross_premium">
                                                    <InputError :message="forms[2].errors.gross_premium" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Net premium</label>
                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="forms[2].net_premium">
                                                    <InputError :message="forms[2].errors.net_premium" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Payment status</label>
                                                    <select id="input21" class="form-select"
                                                        v-model="forms[2].payment_status">

                                                        <option value="pending">Pending</option>
                                                        <option value="paid">Paid</option>
                                                        <option value="un-paid">Un-paid</option>
                                                    </select>
                                                    <InputError :message="forms[2].errors.payment_status" />
                                                </div>
                                            </div>
                                            <div class="modal-footer"
                                                style="margin-left: 0px;margin-right: 17px;margin-bottom: 20px;">

                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    {{ forms[2].edit_mode ? 'Save & Update' : 'Save' }}</button>
                                            </div>
                                        </form>
                                        <form @submit.prevent="forms[3].edit_mode ? update() : submit()">
                                            <div class="row"
                                                style="margin-left: 0px;margin-right: 0px;margin-bottom: 20px;">
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Due date</label>
                                                    <VueDatePicker v-model="forms[3].due_date"
                                                        :enable-time-picker="false" :show-time="false">
                                                    </VueDatePicker>
                                                    <InputError :message="forms[3].errors.due_date" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Gross premium</label>
                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="forms[3].gross_premium">
                                                    <InputError :message="forms[3].errors.gross_premium" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Net premium</label>
                                                    <input type="text" class="form-control" id="input13" placeholder=""
                                                        v-model="forms[3].net_premium">
                                                    <InputError :message="forms[3].errors.net_premium" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="input13" class="form-label">Payment status</label>
                                                    <select id="input21" class="form-select"
                                                        v-model="forms[3].payment_status">

                                                        <option value="pending">Pending</option>
                                                        <option value="paid">Paid</option>
                                                        <option value="un-paid">Un-paid</option>
                                                    </select>
                                                    <InputError :message="forms[3].errors.payment_status" />
                                                </div>
                                            </div>
                                            <div class="modal-footer"
                                                style="margin-left: 0px;margin-right: 17px;margin-bottom: 20px;">

                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    {{ forms[3].edit_mode ? 'Save & Update' : 'Save' }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- POLICY NOTES-->
                                <div class="border mb-4" id="partner">
                                    <div style="background-color: #037DE2">
                                        <h5 style="text-align: center" class="w-auto title">Notes</h5>
                                    </div>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>


                                                    <div class="accordion accordion-flush" id="accordionExample">
                                                        <template v-for="(policyNote, index) in policyNotes"
                                                            :key="index">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header"
                                                                    :id="'heading-' + policyNote.id">
                                                                    <button class="accordion-button" type="button"
                                                                        @click="toggleAccordion(index)"
                                                                        :aria-expanded="isOpen[index]"
                                                                        :aria-controls="'collapse-' + policyNote.id">
                                                                        {{ policyNote.additional_notes }}
                                                                    </button>
                                                                </h2>
                                                                <div :id="'collapse-' + policyNote.id"
                                                                    class="accordion-collapse"
                                                                    :class="{ 'show': isOpen[index] }"
                                                                    :aria-labelledby="'heading-' + policyNote.id"
                                                                    data-bs-parent="#accordionExample2">
                                                                    <div class="accordion-body">
                                                                        <p>{{ policyNote.additional_notes }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- POLICY CLAIM-->
                                <div class="border mb-4" id="partner">
                                    <div style="background-color: #037DE2">
                                        <h5 style="text-align: center" class="w-auto title">Policy Claims</h5>
                                    </div>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <div class="ms-auto">
                                                <ClaimNote v-bind="$props" ref="claim_note_ref"></ClaimNote>
                                                <ClaimUpload v-bind="$props" ref="claim_upload_ref"></ClaimUpload>
                                                <ClaimEdit v-bind="$props" ref="claim_edit_ref"></ClaimEdit>
                                            </div>
                                            <table class="table mb-0">
                                                <thead class="table-light" style="text-align: center;">
                                                    <tr>
                                                        <th>Sr No.</th>
                                                        <th>ID</th>
                                                        <th>Detail</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="text-align: center;">
                                                    <template 
                                                     v-for="policyClaim, index in policyClaims.data" :key="policyClaim.id">
                                                        <tr>
                                                            <td>{{ ++index }}</td>
                                                            <td>{{ policyClaim.id }}</td>
                                                            <td>{{ policyClaim.detail }}</td>
                                                            <td><span class="badge bg-primary">{{ policyClaim.status
                                                                    }}</span></td>
                                                            <td>
                                                                <SecondaryButton @click="claimEdit(policyClaim.id)"
                                                                    title="Edit" data-bs-toggle="modal"
                                                                    style="margin-inline: 5px;"
                                                                    data-bs-target="#EditLargeModal"><i
                                                                        class='bx bx-edit'>Edit</i>
                                                                </SecondaryButton>
                                                                <SecondaryButton @click="claimNote(policyClaim.id)"
                                                                    title="Note" data-bs-toggle="modal"
                                                                    style="margin-inline: 5px;"
                                                                    data-bs-target="#notesLargeModal"><i
                                                                        class='bx bxs-note'>Note</i>
                                                                </SecondaryButton>
                                                                <SecondaryButton @click="claimUpload(policyClaim.id)"
                                                                    title="Uploads" data-bs-toggle="modal"
                                                                    style="margin-inline: 5px;"
                                                                    data-bs-target="#notesUploadLargeModal"><i
                                                                        class='bx bx-cloud-upload'>Upload</i>
                                                                </SecondaryButton>


                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                            <div>
                                                <Paginate :links="policyClaims.links" :scroll="true" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- POLICY UPLOADS-->
                                <div class="border mb-4" id="partner">
                                    <div style="background-color: #037DE2">
                                        <h5 style="text-align: center" class="w-auto title">Uploads</h5>
                                    </div>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead class="table-light" style="text-align: center;">
                                                    <tr>
                                                        <th>Sr No.</th>
                                                        <th>Type</th>
                                                        <th>File</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="text-align: center;">
                                                    <template 
                                                    v-for="policyUpload, index in policyUploads.data" :key="policyUpload.id">
                                                        <tr>
                                                            <td>{{ ++index }}</td>
                                                            <td>{{ policyUpload.type }}</td>
                                                            <td><img :src="props.assetUrl + '/' + policyUpload.upload"
                                                                    alt="" style="width: 70px;"></td>
                                                            <td>
                                                                <a :href="props.assetUrl + '/' + policyUpload.upload"
                                                                    class="inline-flex items-center px-2 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                                                                    download><i
                                                                        class='bx bxs-down-arrow-square'>Download</i></a>

                                                                <!-- <a  class="inline-flex items-center px-2 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                                                                    download><i
                                                                        class='bx bxs-down-arrow-square'>Delete</i></a> -->
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                            <div>
                                                <Paginate :links="policyUploads.links" :scroll="true" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>
</template>

<style>
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
 a.inline-flex.items-center.px-2.py-1.bg-white.border.border-gray-300.rounded-md.font-semibold.text-xs.text-gray-700.tracking-widest.shadow-sm.hover\:bg-gray-50.focus\:outline-none.focus\:ring-2.focus\:ring-indigo-500.focus\:ring-offset-2.disabled\:opacity-25.transition.ease-in-out.duration-150 {
    margin-inline: 3px;
}
</style>
