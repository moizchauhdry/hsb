<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import ReportFilter from "@/Pages/Report/ReportFilter.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import Paginate from "@/Components/Paginate.vue";

// const role = usePage().props.auth.user.roles[0];
// const permission = usePage().props.can;
const slug = usePage().props.slug;

defineProps({
    policies: Array,
    data: Array,
    filter: Array,
    grand_total: Array,
});

const format_number = (number) => {
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(number);
};

const exportExcel = () => {
    window.location.href = route('report.export', slug);
};
</script>

<style src="@vueform/multiselect/themes/default.css"></style>

<template>

    <Head title="Sales Report" />
    <AuthenticatedLayout>
        <div class="page-wrapper">
            <div class="page-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Manage Reports</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;">
                                        <i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active text-capitalize" aria-current="page">{{ slug }} Report
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="ms-auto">
                        <!-- ref -->
                    </div>

                    <div class="ms-auto">
                        <ReportFilter v-bind="$props"></ReportFilter>
                        <SuccessButton @click="exportExcel">Excel Export</SuccessButton>
                    </div>
                </div>


                <div class="row row-cols-1 row-cols-md-2 row-cols-xxl-4">
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Total Sum Insured</p>
                                        <h4 class="my-1">{{ format_number(grand_total.sum_insured) }}</h4>
                                    </div>
                                    <div class="widgets-icons bg-light-info text-info ms-auto"><i
                                            class='bx bx-dollar'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Total Gross Premium</p>
                                        <h4 class="my-1">{{ format_number(grand_total.gross_premium) }}</h4>
                                    </div>
                                    <div class="widgets-icons bg-light-warning text-warning ms-auto"><i
                                            class='bx bx-dollar'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Total Net Premium</p>
                                        <h4 class="my-1">{{ format_number(grand_total.net_premium) }}</h4>
                                    </div>
                                    <div class="widgets-icons bg-light-success text-success ms-auto"><i
                                            class='bx bx-dollar'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center mt-1">
                            <b>{{ filter['month_name'] }} {{ filter['year'] }}</b>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-uppercase"
                                style="font-size:12px">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-2">Sr #</th>
                                        <th class="px-2">Policy Number</th>
                                        <th class="px-2">Client Name</th>
                                        <th class="px-2">Insurer Name</th>
                                        <th class="px-2">Agency Name</th>
                                        <th class="px-2">Class of Business</th>
                                        <th class="px-2">Policy Type</th>

                                        <template v-if="slug == 'renewal' || slug == 'outstanding'">
                                            <th class="px-2">Policy Period Start</th>
                                            <th class="px-2">Policy Period End</th>
                                            <th class="px-2">Date of Issuance</th>
                                        </template>

                                        <template
                                            v-if="slug == 'commission-recovery' || slug == 'commission-outstanding-recovery'">
                                            <th class="px-2">Brokerage amount</th>
                                            <th class="px-2">Brokerage Paid Date </th>
                                        </template>

                                        <template v-if="slug == 'commission-recovery'">
                                            <th class="px-2">Brokerage Percentage</th>
                                            <th class="px-2">Brokerage Received Amount</th>
                                            <th class="px-2">Brokerage Status</th>
                                        </template>

                                        <template v-if="slug == 'commission-outstanding-recovery'">
                                            <th class="px-2">Balance Amount</th>
                                        </template>

                                        <th class="px-2">Sum Insured</th>
                                        <th class="px-2">Gross Premium</th>

                                        <template v-if="slug == 'outstanding'">
                                            <th class="px-2">Gross Premium Received</th>
                                            <th class="px-2">Gross Premium OutStanding</th>
                                        </template>

                                        <th class="px-2">Net Premium</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(policy, index) in policies.data">
                                        <tr>
                                            <td class="px-2">{{ (policies.current_page - 1) * policies.per_page + index + 1 }}</td>
                                            <td class="px-2">{{ policy?.data?.policy_no }}</td>
                                            <td class="px-2">{{ policy?.data?.client?.name }}</td>
                                            <td class="px-2">{{ policy?.data?.insurer?.name }}</td>
                                            <td class="px-2">{{ policy?.data?.agency?.name }}</td>
                                            <td class="px-2">{{ policy?.data?.cob?.class_name }}</td>
                                            <td class="px-2">{{ policy?.data?.policy_type }}</td>

                                            <template v-if="slug == 'renewal' || slug == 'outstanding'">
                                                <td :class="{ 'bg-info': filter['date_type'] === 'policy_period_start' }" class="px-2">{{ policy?.data?.policy_period_start }}</td>
                                                <td :class="{ 'bg-info': filter['date_type'] === 'policy_period_end' }" class="px-2">{{ policy?.data?.policy_period_end }}</td>
                                                <td :class="{ 'bg-info': filter['date_type'] === 'date_of_issuance' }" class="px-2">{{ policy?.data?.date_of_issuance }}</td>
                                            </template>

                                            <template
                                                v-if="slug == 'commission-recovery' || slug == 'commission-outstanding-recovery'">
                                                <td class="px-2">{{ format_number(policy?.data?.brokerage_amount) }}</td>
                                                <td class="px-2">{{ policy?.data?.brokerage_paid_date }}</td>
                                            </template>

                                            <template v-if="slug == 'commission-recovery'">
                                                <td class="px-2">{{ policy?.data?.brokerage_percentage }}%</td>
                                                <td class="px-2">{{ format_number(policy?.data?.brokerage_received_amount) }}</td>
                                                <td class="px-2">{{ policy?.data?.brokerage_status }}</td>
                                            </template>

                                            <template v-if="slug == 'commission-outstanding-recovery'">
                                                <td class="px-2">{{ policy?.data?.balance_amount }}</td>
                                            </template>

                                            <td class="px-2">{{ format_number(policy?.data?.sum_insured) }}</td>
                                            <td class="px-2">{{ format_number(policy?.data?.gross_premium) }}</td>
                                            
                                            <template v-if="slug == 'outstanding'">
                                                <td class="px-2">{{ format_number(policy?.data?.gross_premium_received) }}</td>
                                                <td class="px-2">{{ format_number(policy?.data?.gross_premium_outstanding) }}</td>
                                            </template>
                                            
                                            <td class="px-2">{{ format_number(policy?.data?.net_premium) }}</td>
                                        </tr>
                                    </template>

                                    <tr v-if="policies.data.length == 0">
                                        <td class="text-center" colspan="5">No record</td>
                                    </tr>

                                    <!-- <tr>
                                        <th colspan="10" class="text-right">Grand Total</th>
                                        <th>{{ format_number(grand_total.sum_insured) }}</th>
                                        <th>{{ format_number(grand_total.gross_premium) }}</th>
                                        <th>{{ format_number(grand_total.net_premium) }}</th>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="float-right">
                            <Paginate :links="policies.links" />
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <InvoiceDetailModal ref="invoice_detail_ref"></InvoiceDetailModal>
    </AuthenticatedLayout>
</template>