<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import Swal from 'sweetalert2';
import IconButton from "@/Components/IconButton.vue";

const props = defineProps({
    policies: Array,
    filter: Object,
    detail: Boolean,
});

const permission = usePage().props.can;

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


const sendPostRequest = (baseDocNo) => {
   
    const form = useForm({
        base_doc_no: baseDocNo,
    });

    form.post(route('policy.detail-2'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire('Updated!', 'The claim update successfully.', 'success')
            close();
        },
        onError: (errors) => {
            if (errors.status == false) {
                Swal.fire('Error!', errors.message, 'error')
            }
        },
        onFinish: () => { },
    });

};
</script>

<template>

    <div style="overflow-x: auto">
        <table class="table table-bordered table-sm text-uppercase">
            <thead class="table-light">
                <tr>
                    <th>Sr.</th>
                    <th style="min-width: 250px">Policy No</th>
                    <th style="min-width: 200px">Client Name</th>
                    <th style="min-width: 200px">Agency Name</th>
                    <th style="min-width: 120px">COB Name</th>
                    <th style="min-width: 120px;">Expiry Date</th>
                    <th style="min-width: 80px;">Claims</th>
                    <th style="min-width: 80px;">Type</th>
                    <th style="min-width: 150px">Action</th>
                </tr>
            </thead>
            <tbody>
                <template v-if="policies.data.length > 0">
                    <tr v-for="(policy, index) in policies.data">
                        <td>
                            {{ (policies.current_page - 1) * policies.per_page + index + 1 }}
                        </td>
                        <td>
                            <a :href="route('policy.detail', policy.p_id)" target="_blank">
                                {{ policy.policy_no }} <i class="bx bx-link-external"></i>
                            </a> <br>

                            <button v-if="policy.policy_type == 'endorsement'"
                                @click="sendPostRequest(policy.base_doc_no)">
                                <small>{{ policy.base_doc_no }}</small>
                            </button>
                        </td>
                        <td>{{ policy.client_name }}</td>
                        <td>{{ policy.agency_name }}</td>
                        <td><span class="badge bg-secondary">{{ policy.cob_name }}</span></td>
                        <td>{{ policy.expiry_date }}</td>
                        <td>
                            <a :href="`${route('claim.index')}?policy_id=${policy.p_id}`" target="_blank">
                                <span class="badge bg-dark">
                                    {{ policy.claim_count }} <i class="bx bx-link-external"></i>
                                </span>
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-success mr-1">{{ policy.policy_type }}</span>
                            <span class="badge bg-warning mr-1">{{ policy.policy_lead_type }}</span>
                        </td>
                        <td>
                            <IconButton class="m-1" @click="edit(policy.p_id)" v-if="permission.policy_update">
                                <i class="bx bx-edit bx-text-md"></i>
                            </IconButton>

                            <Link :href="route('policy.detail', policy.p_id)" class="m-1"
                                v-if="permission.policy_detail && detail">
                            <IconButton>
                                <i class="bx bxs-show bx-text-md"></i>
                            </IconButton>
                            </Link>

                            <IconButton class="m-1" @click="confirmDelete(policy.p_id)" v-if="permission.policy_delete">
                                <i class='bx bxs-trash bx-text-md text-danger'></i>
                            </IconButton>
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <tr>
                        <td colspan="10" class="text-center">No Record Found</td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>