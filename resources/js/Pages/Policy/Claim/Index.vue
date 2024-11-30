<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Paginate from "@/Components/Paginate.vue";
import Filter from "./Filter.vue";

import ClaimCreateEdit from "../ClaimCreateEdit.vue";
import ClaimNote from "./Notes.vue";
import ClaimUpload from "./Upload.vue";

import moment from 'moment';
import Search from "@/Components/Search.vue";

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
                    <div class="card-header">
                        <Search :route_name="route('claim.index')" />
                    </div>

                    <div class="card-body">
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
                    <div class="card-footer">
                        <div class="float-left">
                            <span><h6>Showing {{ claims.from }} to {{ claims.to }} of {{claims.total}} entries</h6></span>
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