<script setup>
import { ref } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import Paginate from "@/Components/Paginate.vue";
import AdditionalNotes from "../Partial/AdditionalNotes.vue";
import Swal from 'sweetalert2';
import DangerButton from "@/Components/DangerButton.vue";

const { props } = usePage();
const permission = props.can;
const form = useForm({});

defineProps({
    policy: Object,
    policyNotes: Array,
});
const deletePolicyNote = (noteId) => {
    Swal.fire({
        title: "Are you sure?",
        text: "This note will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("policy.notes.destroy", noteId), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire("Deleted!", "Policy note deleted successfully.", "success");
                },
                onError: (errors) => {
                    console.error(errors);
                    Swal.fire("Error!", "Could not delete the note.", "error");
                },
                onFinish: () => form.reset(),
            });
        }
    });
};
</script>
<template>
    <AdditionalNotes v-bind="$props" v-if="permission.policy_note"></AdditionalNotes>

    <div class="table-responsive mt-2" v-if="permission.policy_note">
        <table class="table table-bordered text-uppercase" v-if="policyNotes.length > 0">
            <tbody>
                <tr>
                    <th colspan="4" class="bg-warning text-white">
                        Policy Notes
                    </th>
                </tr>
                <tr>
                    <th>Sr No.</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
                <template v-for="policyNote, index in policyNotes" :key="policyNote.id">
                    <tr>
                        <td>{{ ++index }}</td>
                        <td>{{ policyNote.additional_notes }}</td>
                        <td>
                            <DangerButton @click="deletePolicyNote(policyNote.id)">
                                <i class='bx bxs-trash-alt  mr-2'></i> DELETE
                            </DangerButton>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
    <div v-else>
        <h5 class="text-center">You do not have permission to view this tab.</h5>
    </div>
</template>
