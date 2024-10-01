<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import "@vuepic/vue-datepicker/dist/main.css";
import CreateEdit from "./CreateEdit.vue";
import Import from "./Import/Import.vue";
import { ref } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Paginate from "@/Components/Paginate.vue";
import Swal from 'sweetalert2';
import SuccessButton from "@/Components/SuccessButton.vue";
import Multiselect from "@vueform/multiselect";
import InputLabel from "@/Components/InputLabel.vue";

defineProps({
    policies: Array,
    policy: Object,
});

const create_edit_ref = ref(null);
const edit = (id) => {
    create_edit_ref.value.edit(id)
};

const confirmDelete = (policyId) => {
    const form = useForm({
        id: policyId
    });

    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this policy!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            form.delete(route("policy.delete"), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire(
                        'Deleted!',
                        'Your policy has been deleted.',
                        'success'
                    )
                },
                onError: () => { },
                onFinish: () => form.reset(),
            });
        }
    });
};

var search_types = [
    { value: 1, label: "Policy ID" },
    { value: 2, label: "Policy NO" },
];

const search_form = useForm({
    search_type: 1,
    search_value: ""
});

const search = () => {
    search_form.post(route("policy.index"), {
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
                        <form @submit.prevent="search">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <!-- <InputLabel for="search_type" value="Search Type" /> -->
                                    <Multiselect :searchable="false" v-model="search_form.search_type"
                                        :options="search_types">
                                    </Multiselect>
                                </div>
                                <div class="col-md-3">
                                    <!-- <InputLabel for="search_value" value="Search Value" /> -->
                                    <input type="text" v-model="search_form.search_value" style="padding: 8px;"
                                        class="form-control" placeholder="Search">
                                </div>
                                <div class="col-md-3">
                                    <SuccessButton style="margin-top: 3.5px;" class="px-4 py-1" :class="{ 'opacity-25': search_form.processing }"
                                        :disabled="search_form.processing">
                                        Search
                                    </SuccessButton>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0 text-uppercase">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sr #</th>
                                        <th>Policy ID</th>
                                        <th>Policy NO</th>
                                        <th>Client Name</th>
                                        <th>Insurer Name</th>
                                        <th>Insurance Date</th>
                                        <th>Policy Start</th>
                                        <th>Policy End</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(policy, index) in policies.data">
                                        <tr>
                                            <td>{{ (policies.current_page - 1) * policies.per_page + index + 1 }}</td>
                                            <td>
                                                00{{ policy.id }}
                                            </td>
                                            <td>{{ policy.policy_no }}</td>
                                            <td>{{ policy.client_name }}</td>
                                            <td>{{ policy.insurer_name }}</td>
                                            <td>{{ policy.insurance_date }}</td>
                                            <td>{{ policy.policy_start }}</td>
                                            <td>{{ policy.policy_end }}</td>
                                            <td>
                                                <SecondaryButton @click="edit(policy.id)">Edit <i
                                                        class="bx bx-edit"></i></SecondaryButton>

                                                <Link :href="route('policy.detail', policy.id)" class="mx-1">
                                                <SecondaryButton>Detail <i class="bx bxs-collection"></i>
                                                </SecondaryButton>
                                                </Link>

                                                <SecondaryButton @click="confirmDelete(policy.id)">
                                                    Delete <i class='bx bxs-folder-minus'></i>
                                                </SecondaryButton>
                                            </td>
                                        </tr>
                                    </template>
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
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>

<style src="@vueform/multiselect/themes/default.css"></style>