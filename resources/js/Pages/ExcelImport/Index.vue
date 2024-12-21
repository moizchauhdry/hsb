<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage, useForm } from "@inertiajs/vue3";
import { ref, onBeforeUnmount } from "vue"; // Import ref and onBeforeUnmount
import Paginate from "@/Components/Paginate.vue";
import Import from "../Policy/Import/Import.vue";
import { Inertia } from "@inertiajs/inertia"; // Import Inertia

defineProps({
    error_logs: Array,
    import_completed_count: Object,
});

const import_completed_count = usePage().props.import_completed_count;
const error_logs_count = usePage().props.error_logs.data.length;

// if (import_completed_count <= 1 && error_logs_count > 0) {
//     // Set an interval to refresh the route every 5 seconds
//     const interval = setInterval(() => {
//         // Use Inertia to visit the current page again
//         Inertia.visit(window.location.href, {
//             method: 'get',
//             preserveState: true // Optional, preserve the current state if needed
//         });
//     }, 2000); // 5000 ms = 5 seconds

//     // Clear the interval on component unmount
//     onBeforeUnmount(() => {
//         clearInterval(interval);
//     });
// }

</script>

<template>

    <Head title="Sales Report" />
    <AuthenticatedLayout>
        <div class="page-wrapper">
            <div class="page-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Excel Import</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;">
                                        <i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active text-capitalize" aria-current="page">List
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="ms-auto">
                        <!-- ref -->
                    </div>

                    <div class="ms-auto">
                        <Import v-bind="$props"></Import>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center mt-1">

                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- {{ import_completed_count }} {{ error_logs_count }} -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-uppercase" style="font-size:12px">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-2">Sr #</th>
                                        <th class="px-2">ID</th>
                                        <th class="px-2">No of Policies</th>
                                        <th class="px-2">Found Discrepancies</th>
                                        <th class="px-2">Time/Date</th>
                                        <th class="px-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(log, index) in error_logs.data">
                                        <tr>
                                            <td class="px-2">{{ (error_logs.current_page - 1) * error_logs.per_page +
                                                index + 1 }}</td>
                                            <td class="px-2">{{ log.id }}</td>
                                            <td class="px-2">{{ log.total_records }}</td>
                                            <td class="px-2">{{ log.failed_records }}</td>
                                            <td class="px-2">{{ log.created_at }}</td>
                                            <td class="px-2"><span class="badge bg-success">{{ (log.total_records - log.failed_records) }} / {{ log.total_records}} uploaded successfully</span> <span class="bx bx-info-circle cursor-pointer" @click="selectLog(log)"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="float-right">
                            <Paginate :links="error_logs.links" />
                        </div>
                    </div>

                    <div v-if="selectedLog" class="mt-4">
                        <h5 class="pl-4">Validation Results for Batch ID: {{ selectedLog.id }}</h5>

                        <!-- make three section for errors count -->

                        <div class="row p-4">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Policies: {{ selectedLog.total_records }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Policies with Discrepancies: <span class="text-danger">{{ selectedLog.errors.length }}</span></h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Valid Policies: <span class="text-success">{{ selectedLog.total_records - selectedLog.errors.length }}</span></h5>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="table-responsive p-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Error Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(error, index) in selectedLog.errors" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ error.error_message }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
export default {
    props: ['error_logs', 'import_completed_count'],
    data() {
        return {
            selectedLog: null, // Tracks the selected log for error details
        };
    },
    computed: {
        totalPolicies() {
            return this.error_logs.data.reduce((sum, log) => sum + log.total_records, 0);
        },
        policiesWithDiscrepancies() {
            return this.error_logs.data.reduce((sum, log) => sum + log.failed_records, 0);
        },
        validPolicies() {
            return this.totalPolicies - this.policiesWithDiscrepancies;
        },
    },
    methods: {
        selectLog(log) {
            this.selectedLog = log; // Set the selected log for error details view
        },
    },
};
</script>
