<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import Uploads from "./Uploads.vue";
import Claim from "./Claim.vue";

const { props } = usePage();

defineProps({
    policy: Object,
    policyNotes: Object
});



</script>

<style> table, th, td { padding: 3px !important; font-size: 14px !important; } #lc-table td { border: 1px solid rgb(194, 189, 189) !important; text-align: left !important; } #lc-table th { border: 1px solid rgb(194, 189, 189) !important; text-align: left !important; } table { width: 100%; border-collapse: collapse; } fieldset legend { color: green; font-weight: 600; } fieldset.border { border: 2px solid #17af23 !important; border-radius: 5px !important; }
    fieldset.member-border { border: 2px solid #17af23 !important; border-radius: 5px !important;} .mb-4 { margin-bottom: 20px } .custom-image-preview { width: 100px; height: 100px } </style>


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
                            <div class="ms-auto d-flex" style="width: 205px;">
                                <Uploads v-bind="$props"></Uploads>
                                <Claim v-bind="$props"></Claim>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <fieldset class="border p-4 mb-4" id="partner">
                                <legend class="w-auto title">Policy Info</legend>
                                <div class="row">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>ID</th>
                                            <td>{{ policy.id }}</td>
                                            <th>Client Name</th>
                                            <td>{{ policy.client_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Insurance</th>
                                            <td>{{ policy.insurance_id }}</td>

                                            <th>Co Insurance</th>
                                            <td>{{ policy.co_insurance }}</td>
                                        </tr>
                                        <tr>
                                            <th>Takefull type</th>
                                            <td v-if="policy.takeful_type == 0">Direct 100%</td>
                                            <td v-if="policy.takeful_type == 1">Our lead</td>
                                            <th>Policy No.</th>
                                            <td>{{ policy.policy_no }}</td>
                                        </tr>
                                        <tr>
                                            <th>Agency</th>
                                            <td>{{ policy.agency_id }}</td>
                                        
                                            <th>Agency code</th>
                                            <td>{{ policy.agency_code }}</td>
                                        </tr>
                                        <tr>
                                            <th>Class of business</th>
                                            <td>{{ policy.class_of_business_id }}</td>
                                        
                                            <th>Orignal Endorsment</th>
                                            <td>{{ policy.orignal_endorsment }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date of insurance</th>
                                            <td>{{ policy.date_of_insurance }}</td>
                                    
                                            <th>Policy start period</th>
                                            <td>{{ policy.policy_start_period }}</td>
                                        </tr>
                                        <tr>
                                            <th>Policy end period</th>
                                            <td>{{ policy.policy_end_period }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4" id="partner">
                                <legend class="w-auto">Policy Amount</legend>
                                <div class="row">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Sum insured </th>
                                            <td> {{ policy.sum_insured }} </td>
                                       
                                            <th>Gross Premium </th>
                                            <td> {{ policy.gross_premium }} </td>
                                        </tr>
                                        <tr>
                                            <th>Net Premium </th>
                                            <td> {{ policy.net_premium }} </td>
                                        
                                            <th> Cover Note No </th>
                                            <td> {{ policy.cover_note_no }} </td>
                                        </tr>
                                        <tr>
                                            <th>Installment Plan </th>
                                            <td> {{ policy.installment_plan }} </td>
                                        
                                            <th>Leader </th>
                                            <td> {{ policy.leader }} </td>
                                        </tr>
                                        <tr>
                                            <th>leader_policy_no </th>
                                            <td> {{ policy.leader_policy_no }} </td>
                                       
                                            <th>branch </th>
                                            <td> {{ policy.branch }} </td>
                                        </tr>
                                        <tr>
                                            <th>Brokerage Amount </th>
                                            <td> {{ policy.brokerage_amount }} </td>
                                        
                                            <th>User </th>
                                            <td> {{ policy.user_id }} </td>
                                        </tr>
                                        <tr>
                                            <th>Tax</th>
                                            <td> {{ policy.tax }} </td>
                                      
                                            <th>Percentage</th>
                                            <td> {{ policy.percentage }} </td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>

                            <fieldset class="border p-4 mb-4" id="partner" v-if="JSON.parse(JSON.stringify(props.policyNotes))?.length > 0 ? JSON.parse(JSON.stringify(props.policyNotes)) : undefined">
                                <legend class="w-auto">Policy Notes</legend>
                                <div class="row">
                                    <table class="table table-bordered">
                                        <template v-for="(policyNote, index) in JSON.parse(JSON.stringify(props.policyNotes))">
                                            <tr>
                                                <td> {{ policyNote.additional_notes }} </td>
                                            </tr>
                                        </template>
                                    </table>
                                </div>
                            </fieldset>
                                
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>
</template>