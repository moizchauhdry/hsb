<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import "@vuepic/vue-datepicker/dist/main.css";
import CreateEdit from "./CreateEdit.vue";
import Import from "./Import/Import.vue";
import { ref } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps({
    policies: Array,
    policy: Object,
});

const create_edit_ref = ref(null);
const edit = (id) => {
    create_edit_ref.value.edit(id)
};
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
                                <li class="breadcrumb-item active" aria-current="page">Policies List</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="ms-auto">
                        <div class="d-lg-flex align-items-center mb-4 gap-3">
                            <div class="ms-auto d-flex" style="width: 210px;">                
                                <CreateEdit v-bind="$props" ref="create_edit_ref"></CreateEdit>
                                <Import v-bind="$props"></Import>  
                               
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-lg-flex align-items-center mb-4 gap-3">
                            <div class="position-relative">
                                <input type="text" class="form-control ps-5 radius-30" placeholder="Search Policy">
                                <span class="position-absolute top-50 product-show translate-middle-y"><i
                                        class="bx bx-search"></i></span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-light text-uppercase">
                                    <tr>
                                        <th>SR #</th>
                                        <th>Policy ID</th>
                                        <th>Policy NO</th>
                                        <th>Client Name</th>
                                        <th>Insurer Name</th>
                                        <th>Insurance Date</th>
                                        <th>Policy Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(policy, index) in policies.data">
                                        <tr>
                                            <td>{{ ++index }}</td>
                                            <td>{{ policy.id }}</td>
                                            <td>{{ policy.policy_no }}</td>
                                            <td>{{ policy.client_name }}</td>
                                            <td>{{ policy.insurer_name }}</td>
                                            <td>{{ policy.insurance_date }}</td>
                                            <td>{{ policy.policy_start }} - {{ policy.policy_end }}</td>
                                            <td>
                                                <SecondaryButton @click="edit(policy.id)">Edit <i
                                                        class="bx bx-edit"></i></SecondaryButton>

                                                <Link :href="route('policy.detail', policy.id)" class="mx-1">
                                                <SecondaryButton>Detail <i class="bx bxs-collection"></i>
                                                </SecondaryButton>
                                                </Link>

                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>
