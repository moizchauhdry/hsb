<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import "@vuepic/vue-datepicker/dist/main.css";
import CreateEdit from "./CreateEdit.vue";
import Import from "./Import/Import.vue";
import { ref } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Paginate from "@/Components/Paginate.vue";
import Swal from 'sweetalert2';
import SuccessButton from "@/Components/SuccessButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Multiselect from "@vueform/multiselect";
import InputLabel from "@/Components/InputLabel.vue";
import ReportFilter from "../Report/ReportFilter.vue";

defineProps({
    policies: Array,
    policy: Object,
    filter: Object,
    data: Array,
});

const create_edit_ref = ref(null);
const edit = (id) => {
    create_edit_ref.value.edit(id)
};

const confirmDelete = (policyId) => {
    const form = useForm({
        id: policyId
    });

    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this policy!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            form.delete(route("policy.delete"), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire(
                        'Deleted!',
                        'Your policy has been deleted.',
                        'success'
                    )
                },
                onError: () => { },
                onFinish: () => form.reset(),
            });
        }
    });
};

const search_form = useForm({
    search: "",
    page_count: 10,
});

const search = () => {
    const queryParams = new URLSearchParams(search_form).toString();
    search_form.post(`${route("policy.index")}?${queryParams}`, {
        preserveScroll: true,
        onSuccess: (response) => {
            // 
        },
        onError: (errors) => {
            console.log(errors)
        },
        onFinish: () => { },
    });
};

const reset = () => {
    search_form.search = "";
    search();
};

</script>

<template>

    <Head title="Policies" />

    <AuthenticatedLayout>
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <div class="page-breadcrumb d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Manage Reports</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;">
                                        <i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active text-capitalize" aria-current="page">Manage Policies
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="ms-auto">
                        <!-- ref -->
                    </div>

                    <div class="ms-auto" style="display: flex; justify-content: space-between; align-items: center;">
                        <CreateEdit v-bind="$props" ref="create_edit_ref"></CreateEdit>
                        <Import v-bind="$props"></Import>
                        <ReportFilter v-bind="$props" :filter_route="'policy'"></ReportFilter>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <div class="col-12 col-md-2 d-flex align-items-center mb-2 mb-md-0">
                                <span class="mr-2">Show</span>
                                <select v-model="search_form.page_count" class="form-control" style="width: 70px;"
                                    @change="search()">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="500">500</option>
                                </select>
                                <span class="ml-2">entries</span>
                            </div>

                            <div class="col-12 col-md-10">
                                <form
                                    class="d-flex flex-column flex-md-row justify-content-md-end align-items-md-center"
                                    @submit.prevent="search">
                                    <div class="d-flex flex-column flex-md-row align-items-md-center">
                                        <input type="text" v-model="search_form.search" class="form-control mr-2 mb-2"
                                            placeholder="Search" style="width: 100%;">
                                        <div class="d-flex">
                                            <SuccessButton class="mb-2 px-4 py-1 mr-1">Search</SuccessButton>
                                            <DangerButton class="mb-2 px-2 py-1" @click="reset()"><i class="bx bx-reset text-lg"></i></DangerButton>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-uppercase">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-2">Sr #</th>
                                        <th class="px-2">Policy ID</th>
                                        <th class="px-2">Policy No</th>
                                        <!-- <th class="px-2">Insurer Name</th> -->
                                        <th class="px-2">Client Name</th>
                                        <!-- <th class="px-2">Leader</th> -->
                                        <!-- <th class="px-2">Leader Policy No</th> -->
                                        <!-- <th class="px-2">Lead Type</th> -->
                                        <th class="px-2">Agency Name</th>
                                        <!-- <th class="px-2">Agency Code</th> -->
                                        <!-- <th class="px-2">Child Agency</th> -->
                                        <!-- <th class="px-2">Department Name</th> -->
                                        <th class="px-2">COB Name</th>
                                        <th class="px-2">Issuance Date</th>
                                        <th class="px-2">Inception Date</th>
                                        <th class="px-2">Expiry Date</th>
                                        <!-- <th class="px-2">Sum Insured</th>
                                        <th class="px-2">Gross Premium (100%)</th>
                                        <th class="px-2">Gross Premium</th>
                                        <th class="px-2">Net Premium (100%)</th>
                                        <th class="px-2">Net Premium</th>
                                        <th class="px-2">Rate %</th>
                                        <th class="px-2">Brokerage %</th>
                                        <th class="px-2">Brokerage Amount</th> -->
                                        <th class="px-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(policy, index) in policies.data">
                                        <tr>
                                            <td class="px-2">{{ (policies.current_page - 1) * policies.per_page + index
                                                + 1 }}</td>
                                            <td class="px-2">{{ policy.id }}</td>
                                            <td class="px-2">
                                                <a :href="route('policy.detail', policy.id)" target="_blank"> {{
                                                    policy.data.policy_no }} <i class="bx bx-link-external"></i>
                                                </a>
                                            </td>
                                            <!-- <td class="px-2">{{ policy.insurer_name }}</td> -->
                                            <td class="px-2">{{ policy.client_name }}</td>
                                            <!-- <td class="px-2">{{ policy.data.leader_name }}</td> -->
                                            <!-- <td class="px-2">{{ policy.data.leader_policy_no }}</td> -->
                                            <!-- <td class="px-2">{{ policy.data.lead_type }}</td> -->
                                            <td class="px-2">{{ policy.agency_name }}</td>
                                            <!-- <td class="px-2">{{ policy.data.agency_code }}</td> -->
                                            <!-- <td class="px-2">{{ policy.data.child_agency_name }}</td> -->
                                            <!-- <td class="px-2">{{ policy.department_name }}</td> -->
                                            <td class="px-2">{{ policy.cob_name }}</td>
                                            <td class="px-2">{{ policy.data.date_of_issuance }}</td>
                                            <td class="px-2">{{ policy.data.policy_period_start }}</td>
                                            <td class="px-2">{{ policy.data.policy_period_end }}</td>
                                            <!-- <td class="px-2">{{ policy.data.sum_insured }}</td>
                                            <td class="px-2">{{ policy.data.gross_premium_100 }}</td>
                                            <td class="px-2">{{ policy.data.gross_premium }}</td>
                                            <td class="px-2">{{ policy.data.net_premium_100 }}</td>
                                            <td class="px-2">{{ policy.data.net_premium }}</td>
                                            <td class="px-2">{{ policy.data.rate_percentage }}</td>
                                            <td class="px-2">{{ policy.data.brokerage_percentage }}</td>
                                            <td class="px-2">{{ policy.data.brokerage_amount }}</td> -->
                                            <td class="px-2">
                                                <SecondaryButton @click="edit(policy.id)">
                                                    <i class="bx bx-edit"></i>
                                                </SecondaryButton>

                                                <Link :href="route('policy.detail', policy.id)" class="mx-1">
                                                <SecondaryButton>
                                                    <i class="bx bxs-collection"></i>
                                                </SecondaryButton>
                                                </Link>

                                                <DangerButton @click="confirmDelete(policy.id)">
                                                    <i class='bx bxs-trash'></i>
                                                </DangerButton>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="float-left">
                            <span>Showing {{ policies.from }} to {{ policies.to }} of {{policies.total}} entries</span>
                        </div>
                        <div class="float-right">
                            <Paginate :links="policies.links" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>

<style src="@vueform/multiselect/themes/default.css"></style>