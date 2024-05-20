<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import CreateEdit from "./CreateEdit.vue";

defineProps({
    insurances: Array,
    policies: Array,
    agencies: Array,
    classOfBusiness: Array,
    users: Array,
    clients: Array
});

const policy_modal = ref(false);
const edit_mode = ref(false);

const form = useForm({
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
    policy_modal.value = true;
    edit_mode.value = false;
};

const submit = () => {
    form.post(route("policy.create"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => error(),
        onFinish: () => { },
    });
};

const edit = (policy) => {
    policy_modal.value = true;
    edit_mode.value = true;

    form.policy_id = policy.id;
    form.client_id = policy.client_id;
    form.insurance_id = policy.insurance_id;
    form.co_insurance = policy.co_insurance;
    form.takeful_type = policy.takeful_type;
    form.policy_no = policy.policy_no;
    form.agency_id = policy.agency_id;
    form.agency_code = policy.agency_code;
    form.class_of_business_id = policy.class_of_business_id;
    form.orignal_endorsment = policy.orignal_endorsment;
    form.date_of_insurance = policy.date_of_insurance;
    form.policy_start_period = policy.policy_start_period;
    form.policy_end_period = policy.policy_end_period;
    form.sum_insured = policy.sum_insured;
    form.gross_premium = policy.gross_premium;
    form.net_premium = policy.net_premium;
    form.cover_note_no = policy.cover_note_no;
    form.installment_plan = policy.installment_plan;
    form.leader = policy.leader;
    form.leader_policy_no = policy.leader_policy_no;
    form.branch = policy.branch;
    form.brokerage_amount = policy.brokerage_amount;
    form.user_id = policy.user_id;
    form.tax = policy.tax;
    form.percentage = policy.percentage;
};

const update = () => {
    form.post(route("policy.update"), {
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
    policy_modal.value = false;
    form.reset();
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
                                <li class="breadcrumb-item active" aria-current="page">Policies List</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="ms-auto">
                        <CreateEdit v-bind="$props"></CreateEdit>
                    </div>

                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Policy No</th>
                                        <th>Client Name</th>
                                        <th>Takeful Type</th>
                                        <th>Created Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(policy, index) in policies.data">
                                        <tr>
                                            <td>{{ policy.id }}</td>
                                            <td>{{ policy.policy_no }}</td>
                                            <td>{{ policy.client_name }}</td>
                                            <td>
                                                <div v-if="policy.takeful_type == 1">Direct 100%</div>
                                                <div v-if="policy.takeful_type == 0">Our lead</div>
                                            </td>
                                            <td>{{ policy.created_at }}</td>
                                            <td>
                                                <button type="button" @click="edit(policy)" title="Edit"
                                                    clas="btn btn-primary"><i class="bx bx-edit"></i></button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>
