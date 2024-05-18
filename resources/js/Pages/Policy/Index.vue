<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";

defineProps({
    insurances: Object,
    policies: Object,
    agencies: Object,
    classOfBusiness: Object,
    users: Object,
});

const policy_modal = ref(false);
const edit_mode = ref(false);

const form = useForm({
    policy_id: "",
    client_name: "",
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
    form.client_name = policy.client_name;
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
                        <!-- CREATE & UPDATE MODAL -->
                        <div class="col">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#exampleLargeModal" @click="create"><i
                                    class="bx bx-plus"></i>Add</button>
                            <div class="modal fade show" id="exampleLargeModal" tabindex="-1" aria-hidden="true"
                                style="display: block;" v-if="policy_modal">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <form @submit.prevent="edit_mode ? update() : submit()">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Policies</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close" @click="closeModal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Client Name</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.client_name">
                                                            <span class="position-absolute top-50 translate-middle-y"><i
                                                                    class='bx bx-user'></i></span>
                                                        </div>
                                                        <InputError :message="form.errors.client_name" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input21" class="form-label">Insurance</label>
                                                        
                                                        <select id="input21" class="form-select" v-model="form.insurance_id" multiple="">
                                                            <option value="">Choose...</option>
                                                            <template v-for="insurance in insurances" :key="insurance.id">
                                                                <option :value="insurance.id">{{ insurance.name }}</option>
                                                            </template>
                                                        </select>
                                                        <InputError :message="form.errors.insurance_id" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Co insurance</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.co_insurance">
                                                            <span class="position-absolute top-50 translate-middle-y">%</span>
                                                        </div>
                                                        <InputError :message="form.errors.co_insurance" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input21" class="form-label">Takefull type</label>
                                                        
                                                        <select id="input21" class="form-select" v-model="form.takeful_type">
                                                            <option selected disabled>Choose...</option>
                                                            <option value="1">Direct 100%</option>
                                                            <option value="0">Our lead</option>
                                                        </select>
                                                        <InputError :message="form.errors.insurance_name" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Policy No</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.policy_no">
                                                            <span class="position-absolute top-50 translate-middle-y"><i
                                                                    class='bx bx-user'></i></span>
                                                        </div>
                                                        <InputError :message="form.errors.policy_no" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input21" class="form-label">Agency</label>
                                                        
                                                        <select id="input21" class="form-select" v-model="form.agency_id">
                                                            <option value="">Choose...</option>
                                                            <template v-for="agency in agencies" :key="agency.id">
                                                                <option :value="agency.id">{{ agency.name }}</option>
                                                            </template>
                                                        </select>
                                                        <InputError :message="form.errors.agency_id" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Agency Code</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.agency_code">
                                                            <span class="position-absolute top-50 translate-middle-y"><i
                                                                    class='bx bx-user'></i></span>
                                                        </div>
                                                        <InputError :message="form.errors.agency_code" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input21" class="form-label">Class of business</label>
                                                        
                                                        <select id="input21" class="form-select" v-model="form.class_of_business_id">
                                                            <option value="">Choose...</option>
                                                            <template v-for="classOfBus in classOfBusiness" :key="classOfBus.id">
                                                                <option :value="classOfBus.id">{{ classOfBus.b_class_name }}</option>
                                                            </template>
                                                        </select>
                                                        <InputError :message="form.errors.class_of_business_id" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input21" class="form-label">Orignal/Endorsment</label>
                                                        
                                                        <select id="input21" class="form-select" v-model="form.orignal_endorsment">
                                                            <option selected disabled>Choose...</option>
                                                            <option value="new">New</option>
                                                            <option value="renewal">Renewal</option>
                                                        </select>
                                                        <InputError :message="form.errors.orignal_endorsment" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Date of insurance</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="date" class="form-control" id="input13"
                                                                placeholder="" v-model="form.date_of_insurance">
                                                            <span class="position-absolute top-50 translate-middle-y"></span>
                                                        </div>
                                                        <InputError :message="form.errors.date_of_insurance" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Policy start period</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="date" class="form-control" id="input13"
                                                                placeholder="" v-model="form.policy_start_period">
                                                            <span class="position-absolute top-50 translate-middle-y"></span>
                                                        </div>
                                                        <InputError :message="form.errors.policy_start_period" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Policy end period</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="date" class="form-control" id="input13"
                                                                placeholder="" v-model="form.policy_end_period">
                                                            <span class="position-absolute top-50 translate-middle-y"></span>
                                                        </div>
                                                        <InputError :message="form.errors.policy_end_period" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Sum insured</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.sum_insured">
                                                            <span class="position-absolute top-50 translate-middle-y">%</span>
                                                        </div>
                                                        <InputError :message="form.errors.sum_insured" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Gross premium</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.gross_premium">
                                                            <span class="position-absolute top-50 translate-middle-y">%</span>
                                                        </div>
                                                        <InputError :message="form.errors.gross_premium" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Net premium</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.net_premium">
                                                            <span class="position-absolute top-50 translate-middle-y">%</span>
                                                        </div>
                                                        <InputError :message="form.errors.net_premium" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Cover note no</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.cover_note_no">
                                                            <span class="position-absolute top-50 translate-middle-y">%</span>
                                                        </div>
                                                        <InputError :message="form.errors.cover_note_no" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Installment plan</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.installment_plan">
                                                            <span class="position-absolute top-50 translate-middle-y">%</span>
                                                        </div>
                                                        <InputError :message="form.errors.installment_plan" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Leader</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.leader">
                                                            <span class="position-absolute top-50 translate-middle-y">%</span>
                                                        </div>
                                                        <InputError :message="form.errors.leader" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Leader policy no</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.leader_policy_no">
                                                            <span class="position-absolute top-50 translate-middle-y">%</span>
                                                        </div>
                                                        <InputError :message="form.errors.leader_policy_no" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Branch</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.branch">
                                                            <span class="position-absolute top-50 translate-middle-y">%</span>
                                                        </div>
                                                        <InputError :message="form.errors.branch" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Brokerage amount</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.brokerage_amount">
                                                            <span class="position-absolute top-50 translate-middle-y">%</span>
                                                        </div>
                                                        <InputError :message="form.errors.brokerage_amount" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input21" class="form-label">User</label>
                                                        
                                                        <select id="input21" class="form-select" v-model="form.user_id">
                                                            <option value="">Choose...</option>
                                                            <template v-for="user in users" :key="user.id">
                                                                <option :value="user.id">{{ user.name }}</option>
                                                            </template>
                                                        </select>
                                                        <InputError :message="form.errors.user_id" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Tax</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.tax">
                                                            <span class="position-absolute top-50 translate-middle-y">%</span>
                                                        </div>
                                                        <InputError :message="form.errors.tax" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Percentage</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.percentage">
                                                            <span class="position-absolute top-50 translate-middle-y">%</span>
                                                        </div>
                                                        <InputError :message="form.errors.percentage" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    data-bs-dismiss="modal" @click="closeModal">Close</button>

                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    {{ edit_mode ? 'Save & Update' : 'Save & Submit' }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Client Name</th>
                                        <th>Created Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(policy, index) in policies.data">
                                        <tr>
                                            <td>{{ policy.id }}</td>
                                            <td>{{ policy.client_name }}</td>
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

