<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import PolicyFilter from "@/Pages/Policy/Partial/PolicyFilter.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import Paginate from "@/Components/Paginate.vue";

const slug = usePage().props.slug;

defineProps({
    payments: Array,
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
                        <PolicyFilter :filter_route="'report'"></PolicyFilter>
                        <SuccessButton @click="exportExcel"><i class="bx bx-export text-lg mr-1"></i> Excel Export
                        </SuccessButton>
                    </div>
                </div>


                <div class="row row-cols-1 row-cols-md-3 row-cols-xxl-3">

                    <template v-if="slug == 'sales'">
                        <div class="col">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Total Sum Insured</p>
                                            <h4 class="my-1"><small>PKR</small> {{
                                                format_number(grand_total.sum_insured) }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Total Net Premium</p>
                                            <h4 class="my-1"><small>PKR</small> {{
                                                format_number(grand_total.net_premium) }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="col">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Total Gross Premium</p>
                                            <h4 class="my-1"><small>PKR</small> {{
                                                format_number(grand_total.gross_premium)
                                            }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template v-if="slug == 'gross'">
                        <div class="col">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Gross Premium Amount</p>
                                            <h4 class="my-1"><small>PKR</small> {{
                                                format_number(grand_total.gross_premium) }}
                                            </h4>
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
                                            <p class="mb-0 text-secondary">Gross Premium Collected</p>
                                            <h4 class="my-1"><small>PKR</small> {{
                                                format_number(grand_total.gross_premium_received) }}
                                            </h4>
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
                                            <p class="mb-0 text-secondary">Gross Premium Outstanding</p>
                                            <h4 class="my-1"><small>PKR</small> {{
                                                format_number(grand_total.gross_premium_outstanding)
                                                }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>


                    <template v-if="slug == 'commission'">
                        <div class="col">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Brokerage Commission Amount</p>
                                            <h4 class="my-1"><small>PKR</small> {{
                                                format_number(grand_total.brokerage_amount) }}
                                            </h4>
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
                                            <p class="mb-0 text-secondary">Brokerage Commission Received</p>
                                            <h4 class="my-1"><small>PKR</small> {{
                                                format_number(grand_total.brokerage_amount_received) }}
                                            </h4>
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
                                            <p class="mb-0 text-secondary">Brokerage Commission Outstanding</p>
                                            <h4 class="my-1"><small>PKR</small> {{
                                                format_number(grand_total.brokerage_amount_outstanding)
                                            }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center mt-1">
                            <b>{{ filter['month_name'] }} {{ filter['year'] }}</b>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-uppercase" style="font-size:12px">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-2">Sr #</th>
                                        <th class="px-2">Policy Number</th>
                                        <!-- <th class="px-2">Client Name</th> -->
                                        <!-- <th class="px-2">Insurer Name</th> -->
                                        <!-- <th class="px-2">Agency Name</th> -->
                                        <!-- <th class="px-2">Class of Business</th> -->
                                        <th class="px-2">Policy Type</th>

                                        <!-- <template v-if="slug == 'renewal' || slug == 'outstanding'">
                                            <th class="px-2">Inception Date</th>
                                            <th class="px-2">Expiry Date</th>
                                            <th class="px-2">Issuance Date</th>
                                        </template>

                                        <template v-if="slug == 'commission-outstanding-recovery'">
                                        </template> -->

                                        <th class="px-2">Sum Insured</th>
                                        <th class="px-2">Net Premium</th>
                                        <th class="px-2">Gross Premium</th>

                                        <template v-if="slug == 'gross'">
                                            <th class="px-2">Gross Premium Collected</th>
                                            <th class="px-2">Gross Premium Outstanding</th>
                                        </template>

                                        <template v-if="slug == 'commission'">
                                            <!-- <th class="px-2">Brokerage Percentage</th> -->
                                            <th class="px-2">Brokerage Commission Amount</th>
                                            <th class="px-2">Brokerage Received Amount</th>
                                            <th class="px-2">Brokerage Outstanding Amount</th>
                                            <!-- <th class="px-2">Brokerage Status</th> -->
                                            <!-- <th class="px-2">Brokerage Paid Date </th> -->
                                        </template>

                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(payment, index) in payments.data">
                                        <tr>
                                            <td class="px-2">{{ (payments.current_page - 1) * payments.per_page + index
                                                + 1 }}</td>
                                            <td class="px-2">{{ payment.policy_no }}</td>
                                            <!-- <td class="px-2">{{ payment.client_name }}</td> -->
                                            <!-- <td class="px-2">{{ payment.insurer_name }}</td> -->
                                            <!-- <td class="px-2">{{ payment.agency_name }}</td> -->
                                            <!-- <td class="px-2">{{ payment.cob_name }}</td> -->
                                            <td class="px-2">{{ payment.policy_type }}</td>

                                            <!-- <template v-if="slug == 'renewal' || slug == 'outstanding'">
                                                <td :class="{ 'bg-info': filter['date_type'] === 'policy_period_start' }"
                                                    class="px-2">{{ payment.policy_period_start }}</td>
                                                <td :class="{ 'bg-info': filter['date_type'] === 'policy_period_end' }"
                                                    class="px-2">{{ payment.policy_period_end }}</td>
                                                <td :class="{ 'bg-info': filter['date_type'] === 'date_of_issuance' }"
                                                    class="px-2">{{ payment.date_of_issuance }}</td>
                                            </template> -->

                                            <!-- <template
                                                v-if="slug == 'commission-recovery' || slug == 'commission-outstanding-recovery'">
                                            </template> -->

                                            <td class="px-2">{{ format_number(payment.sum_insured) }}</td>
                                            <td class="px-2">{{ format_number(payment.net_premium) }}</td>
                                            <td class="px-2">{{ format_number(payment.gross_premium) }}</td>

                                            <template v-if="slug == 'gross'">
                                                <td class="px-2">{{ format_number(payment.gross_premium_received)}}</td>
                                                <td class="px-2">{{format_number(payment.gross_premium_outstanding) }}</td>
                                            </template>

                                            <template v-if="slug == 'commission'">
                                                <td class="px-2">{{ format_number(payment.brokerage_amount) }}</td>
                                                <td class="px-2">{{format_number(payment.brokerage_amount_received) }}</td>
                                                <td class="px-2">{{ format_number(payment.brokerage_amount_outstanding) }}</td>
                                            </template>
                                        </tr>
                                    </template>

                                    <tr v-if="payments.data.length == 0">
                                        <td class="text-center" colspan="5">No record</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="float-right">
                            <Paginate :links="payments.links" />
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <InvoiceDetailModal ref="invoice_detail_ref"></InvoiceDetailModal>
    </AuthenticatedLayout>
</template>