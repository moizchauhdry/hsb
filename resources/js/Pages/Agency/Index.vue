<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Paginate from "@/Components/Paginate.vue";

defineProps({
    agencies: Object
});

</script>

<template>

    <Head title="Class Of Business" />

    <AuthenticatedLayout>
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Agencies </div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;">
                                        <i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Agencies List</li>
                            </ol>
                        </nav>
                    </div> 
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr class="text-uppercase">
                                        <th>Sr.No.</th>
                                        <th>Agency ID</th>
                                        <th>Name</th>
                                        <th>Created Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(agency, index) in agencies.data">
                                        <tr>
                                            <td>{{ (agencies.current_page - 1) * agencies.per_page + index + 1 }}</td>
                                            <td>
                                                00{{ agency.id }}
                                            </td>
                                            <td>{{ agency.name }}</td>
                                            <td>{{ agency.created_at }}</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="float-right">
                            <Paginate :links="agencies.links" :scroll="true" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>

