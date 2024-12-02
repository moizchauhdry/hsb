<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import Uploads from "./Uploads.vue";
import AdditionalNotes from "./AdditionalNotes.vue";
import ClaimCreateEdit from "./ClaimCreateEdit.vue";
import ClaimNote from "./Claim/Notes.vue";
import ClaimUpload from "./Claim/Upload.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Paginate from "@/Components/Paginate.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const { props } = usePage();
const permission = props.can;

const isOpen = ref(new Array(props.policyNotes.length).fill(false)); // Array to store isOpen states

defineProps({
    policy: Object,
    policyInstallment: Array(),
    policyNotes: Object,
    policy_claims: Array,
    policyUploads: Array,
    assetUrl: Object,
    desiredSingleOpenBehavior: {
        type: Boolean,
        default: false,
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
const claimNote = (id, policy_id) => {
    claim_note_ref.value.claimNote(id, policy_id)
};

const claim_upload_ref = ref(null);
const claimUpload = (id, policy_id) => {
    claim_upload_ref.value.claimUpload(id, policy_id)
};

const claim_create_edit_ref = ref(null);
const claimEdit = (id) => {
    claim_create_edit_ref.value.claimEdit(id)
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

const format_number = (number) => {
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(number);
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
                        <Uploads v-bind="$props" v-if="permission.policy_upload"></Uploads>
                        <AdditionalNotes v-bind="$props" v-if="permission.policy_note"></AdditionalNotes>

                        <ClaimCreateEdit v-bind="$props" ref="claim_create_edit_ref" :create_mode="true"
                            v-if="permission.policy_claim"></ClaimCreateEdit>

                        <Link :href="route('policy.index',)" class="ml-5">
                        <SecondaryButton>Back</SecondaryButton>
                        </Link>

                        <ClaimNote v-bind="$props" ref="claim_note_ref" v-if="permission.policy_claim"></ClaimNote>
                        <ClaimUpload v-bind="$props" ref="claim_upload_ref" v-if="permission.policy_claim">
                        </ClaimUpload>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive" v-if="permission.policy_detail">
                            <table class="table table-bordered text-uppercase">
                                <tbody>
                                    <tr>
                                        <th colspan="4" class="bg-primary text-white">
                                            Policy Detail
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>POLICY ID</th>
                                        <td>{{ policy.id }}</td>
                                        <th>USER ID</th>
                                        <td>{{ policy.user_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Policy No</th>
                                        <td>{{ policy.policy_no }}</td>
                                        <th>Base Document No</th>
                                        <td>{{ policy.base_doc_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Insurer Name</th>
                                        <td>{{ policy?.insurer?.name }}</td>
                                        <th>Insurance type</th>
                                        <td>
                                            <span v-if="policy.insurance_type == 'takaful'">Takaful</span>
                                            <span v-if="policy.insurance_type == 'conventional'">Conventional</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Client Name</th>
                                        <td>{{ policy?.client?.name }}</td>
                                        <th>Lead type</th>
                                        <td>
                                            <span v-if="policy.lead_type == 'direct'">Direct 100%</span>
                                            <span v-if="policy.lead_type == 'our'">Our lead</span>
                                            <span v-if="policy.lead_type == 'other'">Other lead</span>
                                        </td>
                                    </tr>
                                    <tr>

                                        <th>Leader Name</th>
                                        <td>{{ policy.leader_name }}</td>
                                        <th>Leader Policy No</th>
                                        <td>{{ policy.leader_policy_no }}</td>

                                        <!-- <th v-if="policy.lead_type == 1 || policy.lead_type == 3">Co Insurance</th>
                                        <td v-if="policy.lead_type == 1 || policy.lead_type == 3">{{
                                            policy.co_insurance }}</td> -->
                                    </tr>

                                    <tr>
                                        <th>Agency Name / Agency Code</th>
                                        <td>{{ policy?.agency?.name }} / {{ policy.agency_code }}</td>

                                        <th>Child Agency Name</th>
                                        <td>{{ policy.child_agency_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Class of Business</th>
                                        <td>{{ policy?.cob?.class_name }}</td>

                                        <th>Department Name</th>
                                        <td>{{ policy?.department?.name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Inception Date</th>
                                        <td>{{ policy.policy_period_start }}</td>

                                        <th>Expiry Date</th>
                                        <td>{{ policy.policy_period_end }}</td>
                                    </tr>
                                    <tr>
                                        <th>Policy Type</th>
                                        <td>{{ policy.policy_type }}</td>
                                        <th>Installment Plan </th>
                                        <td> {{ policy.installment_plan }} </td>
                                    </tr>

                                    <tr>
                                        <th>Issuance Date</th>
                                        <td>{{ policy.date_of_issuance }}</td>
                                        <th> Cover Note No </th>
                                        <td> {{ policy.cover_note_no }} </td>
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
                        </div>

                        <div class="table-responsive" v-if="permission.policy_amount">
                            <table class="table table-bordered text-uppercase">
                                <tbody>
                                    <tr>
                                        <th colspan="4" class="bg-primary text-white">
                                            Policy Amount
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Sum insured </th>
                                        <td> PKR {{ format_number(policy.sum_insured) }} </td>

                                        <th>Rate Percentage</th>
                                        <td> {{ policy.rate_percentage }} % </td>
                                    </tr>
                                    <tr>
                                        <th>Gross Premium </th>
                                        <td> PKR {{ format_number(policy.gross_premium) }} </td>

                                        <th>Gross Premium 100%</th>
                                        <td> PKR {{ format_number(policy.gross_premium_100) }} </td>
                                    </tr>
                                    <tr>
                                        <th>Net Premium 100%</th>
                                        <td> PKR {{ format_number(policy.net_premium_100) }} </td>

                                        <th>Net Premium </th>
                                        <td> PKR {{ format_number(policy.net_premium) }} </td>
                                    </tr>
                                    <tr>
                                        <th>Gross Premium Received</th>
                                        <td> PKR {{ format_number(policy.gross_premium_received) }} </td>

                                        <th>Gross Premium Outstanding</th>
                                        <td> PKR {{ format_number(policy.gross_premium_outstanding) }} </td>
                                    </tr>
                                    <tr>
                                        <th>Outstanding 100%</th>
                                        <td> PKR {{ format_number(policy.outstanding_100) }} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive" v-if="permission.policy_brokerage_amount">
                            <table class="table table-bordered text-uppercase">
                                <tbody>
                                    <tr>
                                        <th colspan="4" class="bg-primary text-white">
                                            Brokerage Amount
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Brokerage/Commissioned Amount</th>
                                        <td>PKR {{ format_number(policy.brokerage_amount) }} </td>

                                        <th>Brokerage Percentage </th>
                                        <td>PKR {{ policy.brokerage_percentage }} % </td>
                                    </tr>
                                    <tr>
                                        <th>Brokerage Received Amount</th>
                                        <td>PKR {{ format_number(policy.brokerage_received_amount) }} </td>

                                        <th>Brokerage Paid Date</th>
                                        <td>{{ policy.brokerage_paid_date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Brokerage Status</th>
                                        <td>{{ policy.brokerage_status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive" v-if="permission.policy_note">
                            <table class="table table-bordered text-uppercase" v-if="policyNotes.length > 0">
                            <tbody>
                                <tr>
                                    <th colspan="4" class="bg-primary text-white">
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
                        </div>

                        <div class="table-responsive" v-if="permission.policy_claim">
                            <table class="table table-bordered text-uppercase" v-if="policy_claims.data.length > 0">
                                <tbody>
                                    <tr>
                                        <th colspan="10" class="bg-primary text-white">
                                            Policy Claims
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Sr #</th>
                                        <th>Claim ID</th>
                                        <th>Policy ID</th>
                                        <th>Claim Date</th>
                                        <th>Surveyor Name</th>
                                        <th>Surveyor Contact</th>
                                        <th>Intimation Date</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    <template v-for="claim, index in policy_claims.data" :key="claim.id">
                                        <tr>
                                            <td>{{ (policy_claims.current_page - 1) * policy_claims.per_page + index +
                                                1}}</td>
                                            <td>{{ claim.data.id }}</td>
                                            <td>{{ claim.data.policy_id }}</td>
                                            <td>{{ claim.claim_at }}</td>
                                            <td>{{ claim.data.survivor_name }}</td>
                                            <td>{{ claim.data.contact_no }}</td>
                                            <td>{{ claim.intimation_at }}</td>
                                            <td>{{ claim.data.detail }}</td>
                                            <td><span class="badge bg-primary">{{ claim.data.status }}</span></td>
                                            <td>
                                                <SecondaryButton class="mr-1" @click="claimEdit(claim.data.id)"
                                                    title="Edit" data-bs-toggle="modal"
                                                    data-bs-target="#EditLargeModal">
                                                    <i class='bx bx-edit'></i>
                                                </SecondaryButton>

                                                <SecondaryButton class="mr-1"
                                                    @click="claimNote(claim.data.id, claim.data.policy_id)" title="Note"
                                                    data-bs-toggle="modal" data-bs-target="#notesLargeModal">
                                                    <i class='bx bxs-note'></i>
                                                </SecondaryButton>

                                                <SecondaryButton class="mr-1"
                                                    @click="claimUpload(claim.data.id, claim.data.policy_id)"
                                                    title="Uploads" data-bs-toggle="modal"
                                                    data-bs-target="#notesUploadLargeModal">
                                                    <i class='bx bx-cloud-upload'></i>
                                                </SecondaryButton>
                                            </td>
                                        </tr>
                                    </template>

                                    <tr>
                                        <td colspan="5">
                                            <Paginate :links="policy_claims.links" :scroll="true" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive" v-if="permission.policy_upload">
                            <table class="table table-bordered text-uppercase" v-if="policyUploads.data.length > 0">
                                <tbody>
                                    <tr>
                                        <th colspan="5" class="bg-primary text-white">
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
                        </div>

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
