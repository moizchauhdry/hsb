<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import ReportFilter from "@/Pages/Report/ReportFilter.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";

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
    window.location.href = route('report.export',slug);
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
                                <li class="breadcrumb-item active text-capitalize" aria-current="page">{{slug}} Report</li>
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
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered polciy-table table-sm text-uppercase"
                                style="font-size:12px">
                                <thead class="table-light">
                                    <tr>
                                        <th colspan="15" class="text-uppercase text-center text-lg">
                                            {{ filter['month_name'] }} {{ filter['year'] }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="px-2">Sr. No.</th>
                                        <th class="px-2">Policy Type</th>
                                        <th class="px-2">Client Name</th>
                                        <th class="px-2">Policy Number</th>
                                        <th class="px-2">Insurer Name</th>
                                        <th class="px-2">Agency</th>
                                        <th class="px-2">Class of Business</th>
                                        <th class="px-2">Policy Start</th>
                                        <th class="px-2">Policy End</th>
                                        <th class="px-2">Insurance Date</th>
                                        <th class="px-2">Sum Insured</th>
                                        <th class="px-2">Gross Premium</th>
                                        <th class="px-2">Net Premium</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <template v-for="(policy, index) in policies.data">
                                        <tr>
                                            <td class="px-2">{{ ++index }}</td>
                                            <td class="px-2">{{ policy?.data?.orignal_endorsment }}</td>
                                            <td class="px-2">{{ policy?.data?.client?.name }}</td>
                                            <td class="px-2">{{ policy?.data?.policy_no }}</td>
                                            <td class="px-2">{{ policy?.data?.insurance?.name }}</td>
                                            <td class="px-2">{{ policy?.data?.agency?.name }}</td>
                                            <td class="px-2">{{ policy?.data?.business_class?.class_name }}</td>
                                            <td :class="{ 'bg-info': filter['date_type'] === 'policy_start_period' }" class="px-2">{{ policy?.data?.policy_start_period }}</td>
                                            <td :class="{ 'bg-info': filter['date_type'] === 'policy_end_period' }" class="px-2">{{ policy?.data?.policy_end_period }}</td>
                                            <td :class="{ 'bg-info': filter['date_type'] === 'date_of_insurance' }" class="px-2">{{ policy?.data?.date_of_insurance }}</td>
                                            <td class="px-2">{{ policy?.data?.sum_insured }}</td>
                                            <td class="px-2">{{ policy?.data?.gross_premium }}</td>
                                            <td class="px-2">{{ policy?.data?.net_premium }}</td>
                                        </tr>
                                    </template>

                                    <tr v-if="policies.data.length == 0">
                                        <td class="text-center" colspan="10">No record</td>
                                    </tr>

                                    <tr>
                                        <th colspan="10" class="text-right">Grand Total</th>
                                        <th>{{ format_number(grand_total.sum_insured) }}</th>
                                        <th>{{ format_number(grand_total.gross_premium) }}</th>
                                        <th>{{ format_number(grand_total.net_premium) }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <InvoiceDetailModal ref="invoice_detail_ref"></InvoiceDetailModal>
    </AuthenticatedLayout>
</template>