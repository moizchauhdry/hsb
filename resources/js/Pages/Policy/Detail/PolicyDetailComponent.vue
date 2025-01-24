<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";

const { props } = usePage();
const permission = props.can;

defineProps({
    policy: Object,
});
</script>
<template>
    <div class="table-responsive" v-if="permission.policy_detail">
        <table class="table table-bordered text-uppercase">
            <tbody>
                <tr>
                    <th colspan="4" class="bg-warning text-white">
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
    <div v-else>
        <p>You do not have permission to view this tab.</p>
    </div>
</template>