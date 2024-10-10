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
import PrimaryButton from "@/Components/PrimaryButton.vue";

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
                    <div class="ms-auto">
                        <Uploads v-bind="$props"></Uploads>
                        <AdditionalNotes v-bind="$props"></AdditionalNotes>
                        <Claim v-bind="$props"></Claim>

                        <Link :href="route('policy.index',)" class="ml-5">
                        <SecondaryButton>Back</SecondaryButton>
                        </Link>


                        <ClaimNote v-bind="$props" ref="claim_note_ref"></ClaimNote>
                        <ClaimUpload v-bind="$props" ref="claim_upload_ref"></ClaimUpload>
                        <ClaimEdit v-bind="$props" ref="claim_edit_ref"></ClaimEdit>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th colspan="4" class="bg-primary text-white text-lg">
                                        Policy Detail
                                    </th>
                                </tr>
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
                                    <td colspan="3">{{ policy.policy_no }}</td>
                                </tr>
                                <tr>
                                    <th>Main Agency / Code</th>
                                    <td>{{ policy.agency_id }} / {{ policy.agency_code }}</td>

                                    <th>Child Agency</th>
                                    <td>{{ policy.child_agency_name }}</td>
                                </tr>
                                <tr>

                                    <th>Date of insurance</th>
                                    <td>{{ policy.date_of_insurance }}</td>

                                    <th>Business class / ID</th>
                                    <td>{{ policy.class_of_business_name }} / {{ policy.class_of_business_id }}</td>
                                </tr>
                                <tr>
                                    <th>Policy start period</th>
                                    <td>{{ policy.policy_start_period }}</td>

                                    <th>Policy end period</th>
                                    <td>{{ policy.policy_end_period }}</td>
                                </tr>
                                <tr>
                                    <th>New/Renewal/Endorsment</th>
                                    <td>{{ policy.orignal_endorsment }}</td>
                                    <th>Installment Plan </th>
                                    <td> {{ policy.installment_plan }} </td>

                                </tr>

                                <tr>
                                    <th> Cover Note No </th>
                                    <td> {{ policy.cover_note_no }} </td>

                                    <th>User</th>
                                    <td> {{ policy.user_id }} </td>
                                </tr>

                                <tr>
                                    <th> Excel Import </th>
                                    <td> {{ policy.excel_import }} </td>

                                    <template v-if="policy.excel_import">
                                        <th>Excel Import Date</th>
                                        <td> {{ policy.excel_import_at }} </td>
                                    </template>
                                </tr>

                            </tbody>
                        </table>

                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th colspan="4" class="bg-primary text-white text-lg">
                                        Policy Amount
                                    </th>
                                </tr>
                                <tr>
                                    <th>Sum insured </th>
                                    <td> PKR {{ policy.sum_insured }} </td>

                                    <th>Gross Premium </th>
                                    <td> PKR {{ policy.gross_premium }} </td>
                                </tr>
                                <tr>
                                    <th>Net Premium </th>
                                    <td> PKR {{ policy.net_premium }} </td>

                                    <th>Rate Percentage</th>
                                    <td> {{ policy.percentage }} % </td>
                                </tr>
                                <tr>
                                    <th>Gross Premium Received</th>
                                    <td> PKR {{ policy.gross_premium_received }} </td>

                                    <th>Gross Premium Outstanding</th>
                                    <td> PKR {{ policy.gross_premium_outstanding }} </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th colspan="4" class="bg-primary text-white text-lg">
                                        Brokerage Amount
                                    </th>
                                </tr>
                                <tr>
                                    <th>Brokerage/Commissioned Amount</th>
                                    <td>PKR {{ policy.brokerage_amount }} </td>

                                    <th>Brokerage Percentage </th>
                                    <td>PKR {{ policy.brokerage_percentage }} % </td>
                                </tr>
                                <tr>
                                    <th>Brokerage Received Amount</th>
                                    <td>PKR {{ policy.brokerage_received_amount }} </td>

                                    <th>Brokerage Paid Date</th>
                                    <td>{{ policy.brokerage_paid_date }}</td>
                                </tr>
                                <tr>
                                    <th>Brokerage Status</th>
                                    <td>{{ policy.brokerage_status }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th colspan="4" class="bg-primary text-white text-lg">
                                        Policy Notes
                                    </th>
                                </tr>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Note</th>
                                </tr>
                                <template v-for="policyNote, index in policyNotes" :key="policyNote.id">
                                    <tr>
                                        <td>{{ ++index }}</td>
                                        <td>{{ policyNote.additional_notes }}</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th colspan="5" class="bg-primary text-white text-lg">
                                        Policy Claims
                                    </th>
                                </tr>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>ID</th>
                                    <th>Detail</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                <template v-for="policyClaim, index in policyClaims.data" :key="policyClaim.id">
                                    <tr>
                                        <td>{{ ++index }}</td>
                                        <td>{{ policyClaim.id }}</td>
                                        <td>{{ policyClaim.detail }}</td>
                                        <td><span class="badge bg-primary">{{ policyClaim.status
                                                }}</span></td>
                                        <td>
                                            <PrimaryButton @click="claimEdit(policyClaim.id)" title="Edit"
                                                data-bs-toggle="modal" data-bs-target="#EditLargeModal"><i
                                                    class='bx bx-edit mr-1'></i> Edit
                                            </PrimaryButton>
                                            <PrimaryButton @click="claimNote(policyClaim.id)" title="Note"
                                                data-bs-toggle="modal" data-bs-target="#notesLargeModal"><i
                                                    class='bx bxs-note mr-1'></i> Add Note
                                            </PrimaryButton>
                                            <PrimaryButton @click="claimUpload(policyClaim.id)" title="Uploads"
                                                data-bs-toggle="modal" data-bs-target="#notesUploadLargeModal"><i
                                                    class='bx bx-cloud-upload mr-1'></i> Upload File
                                            </PrimaryButton>
                                        </td>
                                    </tr>
                                </template>

                                <tr>
                                    <td>
                                        <Paginate :links="policyClaims.links" :scroll="true" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th colspan="5" class="bg-primary text-white text-lg">
                                        Policy Uploads
                                    </th>
                                </tr>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Type</th>
                                    <th>File</th>
                                    <th>Action</th>
                                </tr>
                                <template v-for="policyUpload, index in policyUploads.data" :key="policyUpload.id">
                                    <tr>
                                        <td>{{ ++index }}</td>
                                        <td>{{ policyUpload.type }}</td>
                                        <td>
                                            <img :src="props.assetUrl + '/' + policyUpload.upload" alt=""
                                                style="width: 70px;">
                                        </td>
                                        <td>
                                            <a :href="props.assetUrl + '/' + policyUpload.upload" download>
                                                <PrimaryButton>
                                                    <i class='bx bxs-down-arrow-square mr-1'></i> Download
                                                </PrimaryButton>
                                            </a>
                                        </td>
                                    </tr>
                                </template>

                                <tr>
                                    <td>
                                        <Paginate :links="policyUploads.links" :scroll="true" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- INSTALLMENT PLAN-->
                        <div class="table-responsive" style="display: none">
                            <div class="container-fluid">
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
                                    <div class="col-md-12" v-if="policy.installment_plan == 3">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>
</template>
