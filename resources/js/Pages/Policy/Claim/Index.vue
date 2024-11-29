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

import ClaimCreateEdit from "../ClaimCreateEdit.vue";
import ClaimNote from "./Notes.vue";
import ClaimUpload from "./Upload.vue";

import moment from 'moment';

defineProps({
    claims: Array,
    filter: Object,
});

const claim_note_ref = ref(null);
const claimNote = (id, policy_id) => {
    claim_note_ref.value.claimNote(id, policy_id)
};

const claim_upload_ref = ref(null);
const claimUpload = (id, policy_id) => {
    claim_upload_ref.value.claimUpload(id, policy_id)
};

const claim_create_edit_ref = ref(null);
const claimEdit = (id) => {
    claim_create_edit_ref.value.claimEdit(id)
};

const search_form = useForm({
    search: "",
    page_count: 10,
});

const search = () => {
    const queryParams = new URLSearchParams(search_form).toString();
    search_form.post(`${route("claim.index")}?${queryParams}`, {
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

const getDateFormat = (date) => {
    let parsedDate = moment(date);
    let formattedDate = parsedDate.format('DD-MM-YYYY');
    return formattedDate;
}

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
                        <ClaimCreateEdit v-bind="$props" ref="claim_create_edit_ref"></ClaimCreateEdit>
                        <ClaimNote ref="claim_note_ref"></ClaimNote>
                        <ClaimUpload ref="claim_upload_ref"></ClaimUpload>
                    </div>

                    <div class="ms-auto" style="display: flex; justify-content: space-between; align-items: center;">
                        <Filter v-bind="$props"></Filter>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <div class="col-12 col-md-2 d-flex align-items-center mb-2 mb-md-0">
                                <span class="mr-2">Show</span>
                                <select v-model="search_form.page_count" class="form-control" style="width: 70px;"
                                    @change="search()">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="500">500</option>
                                </select>
                                <span class="ml-2">entries</span>
                            </div>

                            <div class="col-12 col-md-10">
                                <form
                                    class="d-flex flex-column flex-md-row justify-content-md-end align-items-md-center"
                                    @submit.prevent="search">
                                    <div class="d-flex flex-column flex-md-row align-items-md-center">
                                        <input type="text" v-model="search_form.search" class="form-control mr-2 mb-2"
                                            placeholder="Search" style="width: 100%;">
                                        <div class="d-flex">
                                            <SuccessButton class="mb-2 px-4 py-1 mr-1">Search</SuccessButton>
                                            <DangerButton class="mb-2 px-2 py-1" @click="reset()"><i class="bx bx-reset text-lg"></i></DangerButton>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-uppercase">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-2">Sr #</th>
                                        <th class="px-2">Claim ID</th>
                                        <th class="px-2">Policy</th>
                                        <th class="px-2">Claim Date</th>
                                        <th class="px-2">Intimation Date</th>
                                        <th class="px-2">Survivor Name</th>
                                        <th class="px-2">Contact No</th>
                                        <th class="px-2">Description</th>
                                        <th class="px-2">Status</th>
                                        <th class="px-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(claim, index) in claims.data">
                                        <tr>
                                            <td class="px-2">{{ (claims.current_page - 1) * claims.per_page + index + 1
                                                }}</td>
                                            <td class="px-2">{{ claim.id }}</td>
                                            <td class="px-2">
                                                <a :href="route('policy.detail', claim.policy_id)" target="_blank"> {{
                                                    claim.policy_no }} <i class="bx bx-link-external"></i>
                                                </a>
                                                <br> {{ claim.client_name }}
                                            </td>
                                            <td class="px-2">{{ getDateFormat(claim.claim_at) }}</td>
                                            <td class="px-2">{{ getDateFormat(claim.intimation_at) }}</td>
                                            <td class="px-2">{{ claim.survivor_name }}</td>
                                            <td class="px-2">{{ claim.contact_no }}</td>
                                            <td class="px-2">{{ claim.detail }}</td>
                                            <td><span class="badge bg-primary">{{ claim.status }}</span></td>
                                            <td class="px-2">
                                                <SecondaryButton class="mr-1" @click="claimEdit(claim.id)" title="Edit"
                                                    data-bs-toggle="modal" data-bs-target="#EditLargeModal">
                                                    <i class='bx bx-edit'></i>
                                                </SecondaryButton>

                                                <SecondaryButton class="mr-1"
                                                    @click="claimNote(claim.id, claim.policy_id)" title="Note"
                                                    data-bs-toggle="modal" data-bs-target="#notesLargeModal">
                                                    <i class='bx bxs-note'></i>
                                                </SecondaryButton>

                                                <SecondaryButton class="mr-1"
                                                    @click="claimUpload(claim.id, claim.policy_id)" title="Uploads"
                                                    data-bs-toggle="modal" data-bs-target="#notesUploadLargeModal">
                                                    <i class='bx bx-cloud-upload'></i>
                                                </SecondaryButton>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="float-left">
                            <span>Showing {{ claims.from }} to {{ claims.to }} of {{claims.total}} entries</span>
                        </div>
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