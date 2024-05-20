<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
    policy: Object,
});



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
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <td>{{ policy.id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Client Name</th>
                                        <td>{{ policy.client_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Insurance</th>
                                        <td>{{ policy.insurance_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Co Insurance</th>
                                        <td>{{ policy.co_insurance }}</td>
                                    </tr>
                                    <tr>
                                        <th>Takefull type</th>
                                        <td v-if="policy.takeful_type == 0">Direct 100%</td>
                                        <td v-if="policy.takeful_type == 1">Our lead</td>
                                    </tr>
                                    <tr>
                                        <th>Policy No.</th>
                                        <td>{{ policy.policy_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Agency</th>
                                        <td>{{ policy.agency_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Agency code</th>
                                        <td>{{ policy.agency_code }}</td>
                                    </tr>
                                    <tr>
                                        <th>Class of business</th>
                                        <td>{{ policy.class_of_business_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Orignal Endorsment</th>
                                        <td>{{ policy.orignal_endorsment }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date of insurance</th>
                                        <td>{{ policy.date_of_insurance }}</td>
                                    </tr>
                                    <tr>
                                        <th>Policy start period</th>
                                        <td>{{ policy.policy_start_period }}</td>
                                    </tr>
                                    <tr>
                                        <th>Policy end period</th>
                                        <td>{{ policy.policy_end_period }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sum insured </th>
                                        <td> {{ policy.sum_insured }} </td>
                                    </tr>
                                    <tr>
                                        <th>Gross Premium </th>
                                        <td> {{ policy.gross_premium }} </td>
                                    </tr>
                                    <tr>
                                        <th>Net Premium </th>
                                        <td> {{ policy.net_premium }} </td>
                                    </tr>
                                    <tr>
                                        <th> Cover Note No </th>
                                        <td> {{ policy.cover_note_no }} </td>
                                    </tr>
                                    <tr>
                                        <th>Installment Plan </th>
                                        <td> {{ policy.installment_plan }} </td>
                                    </tr>
                                    <tr>
                                        <th>Leader </th>
                                        <td> {{ policy.leader }} </td>
                                    </tr>
                                    <tr>
                                        <th>leader_policy_no </th>
                                        <td> {{ policy.leader_policy_no }} </td>
                                    </tr>
                                    <tr>
                                        <th>branch </th>
                                        <td> {{ policy.branch }} </td>
                                    </tr>
                                    <tr>
                                        <th>Brokerage Amount </th>
                                        <td> {{ policy.brokerage_amount }} </td>
                                    </tr>
                                    <tr>
                                        <th>User </th>
                                        <td> {{ policy.user_id }} </td>
                                    </tr>
                                    <tr>
                                        <th>Tax</th>
                                        <td> {{ policy.tax }} </td>
                                    </tr>
                                    <tr>
                                        <th>Percentage</th>
                                        <td> {{ policy.percentage }} </td>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>