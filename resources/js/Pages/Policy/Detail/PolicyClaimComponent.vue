<script setup>
import { ref } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import Paginate from "@/Components/Paginate.vue";
import CreateEdit from "../Claim/CreateEdit.vue";
import Note from "../Claim/Note.vue";
import Upload from "../Claim/Upload.vue";

const { props } = usePage();
const permission = props.can;

defineProps({
    policy: Object,
    policy_claims: Array,
});

const claim_create_edit_ref = ref(null);
const claimEdit = (id) => {
    claim_create_edit_ref.value.claimEdit(id)
};

const claim_note_ref = ref(null);
const claimNote = (id, policy_id) => {
    claim_note_ref.value.claimNote(id, policy_id)
};

const claim_upload_ref = ref(null);
const claimUpload = (id, policy_id) => {
    claim_upload_ref.value.claimUpload(id, policy_id)
};

</script>
<template>

    <CreateEdit v-bind="$props" ref="claim_create_edit_ref" :create_mode="true" v-if="permission.policy_claim">
    </CreateEdit>

    <Note v-bind="$props" ref="claim_note_ref" v-if="permission.policy_claim"></Note>

    <Upload v-bind="$props" ref="claim_upload_ref" v-if="permission.policy_claim">
    </Upload>

    <div class="table-responsive mt-2" v-if="permission.policy_claim">
        <table class="table table-bordered text-uppercase" v-if="policy_claims.data.length > 0">
            <tbody>
                <tr>
                    <th colspan="10" class="bg-warning text-white">
                        Policy Claims
                    </th>
                </tr>
                <tr>
                    <th>Sr #</th>
                    <th>Claim No</th>
                    <th>Claim Date</th>
                    <th>Surveyor Name</th>
                    <th>Surveyor Contact</th>
                    <th>Intimation Date</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <template v-for="claim, index in policy_claims.data" :key="claim.id">
                    <tr>
                        <td>{{ (policy_claims.current_page - 1) * policy_claims.per_page + index +
                            1 }}</td>
                        <td>{{ claim.data.claim_no }}</td>
                        <td>{{ claim.claim_at }}</td>
                        <td>{{ claim.data.survivor_name }}</td>
                        <td>{{ claim.data.contact_no }}</td>
                        <td>{{ claim.intimation_at }}</td>
                        <td>{{ claim.data.detail }}</td>
                        <td><span class="badge bg-primary">{{ claim.data.status }}</span></td>
                        <td>
                            <SecondaryButton class="mr-1" @click="claimEdit(claim.data.id)" title="Edit"
                                data-bs-toggle="modal" data-bs-target="#EditLargeModal">
                                <i class='bx bx-edit'></i>
                            </SecondaryButton>

                            <SecondaryButton class="mr-1" @click="claimNote(claim.data.id, claim.data.policy_id)"
                                title="Note" data-bs-toggle="modal" data-bs-target="#notesLargeModal">
                                <i class='bx bxs-note'></i>
                            </SecondaryButton>

                            <SecondaryButton class="mr-1" @click="claimUpload(claim.data.id, claim.data.policy_id)"
                                title="Uploads" data-bs-toggle="modal" data-bs-target="#notesUploadLargeModal">
                                <i class='bx bx-cloud-upload'></i>
                            </SecondaryButton>
                        </td>
                    </tr>
                </template>

                <tr>
                    <td colspan="5">
                        <Paginate :links="policy_claims.links" :scroll="true" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-else>
        <h5 class="text-center">You do not have permission to view this tab.</h5>
    </div>
</template>