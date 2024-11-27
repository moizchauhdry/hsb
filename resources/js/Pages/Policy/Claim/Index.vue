<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Paginate from "@/Components/Paginate.vue";
import Swal from 'sweetalert2';
import SuccessButton from "@/Components/SuccessButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Filter from "./Filter.vue";

defineProps({
    claims: Array,
    filter: Object,
});

const create_edit_ref = ref(null);
const edit = (id) => {
    create_edit_ref.value.edit(id)
};

const confirmDelete = (claim_id) => {
    const form = useForm({
        id: claim_id
    });

    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this claim!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            form.delete(route("claim.delete"), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire(
                        'Deleted!',
                        'The claim has been deleted.',
                        'success'
                    )
                },
                onError: () => { },
                onFinish: () => form.reset(),
            });
        }
    });
};

const search_form = useForm({
    search: ""
});

const search = () => {
    search_form.post(route("claim.index"), {
        preserveScroll: true,
        onSuccess: (response) => {
            // 
        },
        onError: (errors) => {
            console.log(errors)
        },
        onFinish: () => { },
    });
};

const reset = () => {
    search_form.search = "";
    search();
};

</script>

<template>

    <Head title="Policies" />

    <AuthenticatedLayout>
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <div class="page-breadcrumb d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Manage Claims</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;">
                                        <i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active text-capitalize" aria-current="page">Claim List
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="ms-auto">
                        <!-- ref -->
                    </div>

                    <div class="ms-auto" style="display: flex; justify-content: space-between; align-items: center;">
                        <!-- <CreateEdit v-bind="$props" ref="create_edit_ref"></CreateEdit>-->
                        <Filter v-bind="$props"></Filter>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="search">
                            <div class="row mb-3 d-flex align-items-center">
                                <div class="col-md-3">
                                    <input type="text" v-model="search_form.search" class="form-control"
                                        placeholder="Search">
                                </div>
                                <div class="col-md-3">
                                    <SuccessButton class="px-4 py-1 mr-1">Search</SuccessButton>
                                    <DangerButton class="px-4 py-1 mr-1" @click="reset()">Reset</DangerButton>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-uppercase">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-2">Sr #</th>
                                        <th class="px-2">Claim ID</th>
                                        <th class="px-2">Policy ID</th>
                                        <th class="px-2">Policy No</th>
                                        <th class="px-2">Claim Date</th>
                                        <th class="px-2">Intimation Date</th>
                                        <th class="px-2">Survivor Name</th>
                                        <th class="px-2">Contact No</th>
                                        <th class="px-2">Description</th>
                                        <!-- <th class="px-2">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(claim, index) in claims.data">
                                        <tr>
                                            <td class="px-2">{{ (claims.current_page - 1) * claims.per_page + index+ 1 }}</td>
                                            <td class="px-2">{{ claim.data.id }}</td>
                                            <td class="px-2">{{ claim.data.policy_id }}</td>
                                            <td class="px-2">{{ claim.policy_no }}</td>
                                            <td class="px-2">{{ claim.claim_at }}</td>
                                            <td class="px-2">{{ claim.intimation_at }}</td>
                                            <td class="px-2">{{ claim.data.survivor_name }}</td>
                                            <td class="px-2">{{ claim.data.contact_no }}</td>
                                            <td class="px-2">{{ claim.data.detail }}</td>
                                            <!-- <td class="px-2"></td> -->
                                        </tr>
                                    </template>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="float-right">
                            <Paginate :links="claims.links" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>

<style src="@vueform/multiselect/themes/default.css"></style>