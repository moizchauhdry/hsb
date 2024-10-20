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
                                        <th class="px-2">Channel</th>
                                        <th class="px-2">Log Type</th>
                                        <th class="px-2">Message</th>
                                        <th class="px-2">Level Name</th>
                                        <th class="px-2">Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(log, index) in error_logs.data">
                                        <tr>
                                            <td class="px-2">{{ (error_logs.current_page - 1) * error_logs.per_page +
                                                index + 1 }}</td>
                                            <td class="px-2">{{ log.id }}</td>
                                            <td class="px-2">{{ log.channel }}</td>
                                            <td class="px-2">{{ log.type }}</td>
                                            <td class="px-2">{{ log.message }}</td>
                                            <td class="px-2">{{ log.level_name }}</td>
                                            <td class="px-2">{{ log.created_at }}</td>
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
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>