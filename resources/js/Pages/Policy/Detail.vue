<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

import SecondaryButton from "@/Components/SecondaryButton.vue";
import PolicyDetailComponent from "./Detail/PolicyDetailComponent.vue";
import PolicyAmountComponent from "./Detail/PolicyAmountComponent.vue";
import PolicyBrokerageComponent from "./Detail/PolicyBrokerageComponent.vue";
import PolicyClaimComponent from "./Detail/PolicyClaimComponent.vue";
import PolicyNoteComponent from "./Detail/PolicyNoteComponent.vue";
import PolicyUploadComponent from "./Detail/PolicyUploadComponent.vue";
import EndorsementComponent from "./Detail/EndorsementComponent.vue";
import RenewalComponent from "./Detail/RenewalComponent.vue";
import LeaderComponent from "./Detail/LeaderComponent.vue";

defineProps({
    policy: Object,
    policyInstallment: Array(),
    policyNotes: Object,
    policy_claims: Array,
    policyUploads: Array,
    endorsements: Array,
    renewals: Array,
    leads: Array,
    assetUrl: Object,
    // desiredSingleOpenBehavior: {
    //     type: Boolean,
    //     default: false,
    // },
});

// const edit_mode = ref(false);

// const toggleAccordion = (index) => {
//     isOpen.value[index] = !isOpen.value[index];
//     if (props.desiredSingleOpenBehavior) {
//         isOpen.value.fill(false, 0, index);
//         isOpen.value.fill(false, index + 1);
//     }
// };

// const forms = [];

// if (props.policyInstallment && props.policyInstallment.length > 0) {
//     for (let i = 0; i < Number(props.policy.installment_plan); i++) {
//         const formFields = i < props.policyInstallment.length ? {
//             due_date: props.policyInstallment[i].due_date || "",
//             gross_premium: props.policyInstallment[i].gross_premium || "",
//             net_premium: props.policyInstallment[i].net_premium || "",
//             payment_status: props.policyInstallment[i].payment_status || "",
//             edit_mode: true,
//         } : {
//             due_date: "",
//             gross_premium: "",
//             net_premium: "",
//             payment_status: "",
//             edit_mode: false,
//         };
//         const form = useForm(formFields);
//         forms.push(form);
//     }
// } else {
//     for (let i = 0; i < Number(props.policy.installment_plan); i++) {
//         const initialState = {
//             due_date: "",
//             gross_premium: "",
//             net_premium: "",
//             payment_status: "",
//             edit_mode: false,
//         };
//         const form = useForm(initialState);
//         forms.push(form);
//     }
// }


// const submit = () => {
//     if (forms && forms.length > 0) {
//         forms.forEach(proxyData => {

//             if (!proxyData) return;
//             const data = { ...proxyData };

//             if (data.due_date !== '' && data.gross_premium !== '' && data.net_premium !== '' && data.payment_status !== '') {

//                 const formFields = {
//                     policy_id: JSON.parse(JSON.stringify((props.policy.id))) ?? "",
//                     due_date: data.due_date || "",
//                     gross_premium: data.gross_premium || "",
//                     net_premium: data.net_premium || "",
//                     payment_status: data.payment_status || "",
//                 };

//                 const form = useForm(formFields);
//                 form.post(route("policy.installmentPlan"), {
//                     preserveScroll: true,
//                     onError: () => error(),
//                     onFinish: () => { },
//                 });
//             }
//         });
//     }
// };

const activeTab = ref('policy_detail_component');
const tabs = ref([
    {
        id: 'policy_detail_component',
        title: 'Policy Detail',
        icon: 'bx bx-label font-18 me-1',
        component: PolicyDetailComponent,
    },
    {
        id: 'policy_amount_component',
        title: 'Policy Amount',
        icon: 'bx bx-receipt font-18 me-1',
        component: PolicyAmountComponent,
    },
    {
        id: 'policy_brokerage_component',
        title: 'Brokerage Amount',
        icon: 'bx bx-book-alt font-18 me-1',
        component: PolicyBrokerageComponent,
    },
    {
        id: 'policy_claims_component',
        title: 'Policy Claims',
        icon: 'bx bx-list-ul font-18 me-1',
        component: PolicyClaimComponent,
    },
    {
        id: 'policy_note_component',
        title: 'Policy Notes',
        icon: 'bx bx-note font-18 me-1',
        component: PolicyNoteComponent,
    },
    {
        id: 'policy_upload_component',
        title: 'Policy Uploads',
        icon: 'bx bx-upload font-18 me-1',
        component: PolicyUploadComponent,
    },
    {
        id: 'endorsement_component',
        title: 'Endorsements',
        icon: 'bx bx-note font-18 me-1',
        component: EndorsementComponent,
    },
    {
        id: 'renewal_component',
        title: 'Renewals',
        icon: 'bx bx-note font-18 me-1',
        component: RenewalComponent,
    },
    {
        id: 'policy_leads',
        title: 'Policy Leads',
        icon: 'bx bx-note font-18 me-1',
        component: LeaderComponent,
    },
]);

const setActiveTab = (tabId) => {
    activeTab.value = tabId;
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
                        <Link :href="route('policy.index')" class="ml-5">
                            <SecondaryButton><i class="bx bx-arrow-back mr-1"></i>Back</SecondaryButton>
                        </Link>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <ul class="nav nav-tabs nav-warning" role="tablist">
                            <li class="nav-item" role="presentation" v-for="tab in tabs" :key="tab.id">
                                <a class="nav-link" :class="{ active: activeTab === tab.id }"
                                    @click.prevent="setActiveTab(tab.id)" href="#" role="tab">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon">
                                            <i :class="tab.icon"></i>
                                        </div>
                                        <div class="tab-title">{{ tab.title }}</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content py-3">
                            <div class="tab-pane fade" :class="{ 'show active': activeTab === tab.id }" role="tabpanel"
                                v-for="tab in tabs" :key="tab.id">
                                <component :is="tab.component" v-bind="$props" />
                            </div>
                        </div>

                        <!-- INSTALLMENT PLAN-->
                        <!-- <div class="table-responsive" style="display: none">
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
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>
</template>

<style>
/* Transition effect */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>