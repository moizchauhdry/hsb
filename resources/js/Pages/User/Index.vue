<script setup>
import { router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import Paginate from "@/Components/Paginate.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Search from "@/Components/Search.vue";
import CreateEdit from "./CreateEdit.vue";
import AssignClient from "./AssignClient.vue";
import AssignCOB from "./AssignCOB.vue";

defineProps({
    users: Object,
    roles: Object,
    slug: Object,
});

const slug = usePage().props.slug;

const user_create_edit_ref = ref(null);
const edit = (id) => {
    user_create_edit_ref.value.edit(id)
};

const assign_client_ref = ref(null);
const assignClient = (id) => {
    assign_client_ref.value.assignClient(id)
};

const assign_cob_ref = ref(null);
const assignCob = (id) => {
    assign_cob_ref.value.assignCob(id)
};
const userAudit = (userId) => {
    router.get(route("user.audit", userId));
};
</script>


<template>

    <Head title="Users" />

    <AuthenticatedLayout>
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">User Management</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;">
                                        <i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">User List</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="ms-auto">
                        <CreateEdit ref="user_create_edit_ref" v-bind="$props"></CreateEdit>
                        <AssignClient ref="assign_client_ref" v-bind="$props"></AssignClient>
                        <AssignCOB ref="assign_cob_ref" v-bind="$props" />
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <Search :route_name="route('user.index', { slug })" />
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered table-hover table-sm text-uppercase"
                                style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sr.No.</th>
                                        <th v-if="slug == 'clients'">Code</th>
                                        <th>Name</th>
                                        <th v-if="slug == 'users'">Email</th>
                                        <th>Role</th>
                                        <th>Register Date</th>
                                        <th colspan="3"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(user, index) in users.data">
                                        <tr>
                                            <td>{{ (users.current_page - 1) * users.per_page + index + 1 }}</td>
                                            <td v-if="slug == 'clients'">{{ user.code }}</td>
                                            <td class="text-capitalize">{{ user.name }}</td>
                                            <td class="text-lowercase" v-if="slug == 'users'">{{ user.email }}</td>
                                            <td class="text-capitalize">{{ user.role }}</td>
                                            <td>{{ user.created_at }}</td>

                                            <td>
                                                <PrimaryButton class="mr-1" @click="edit(user.id)" title="Edit"><i
                                                        class="bx bx-edit mr-1"></i> Edit</PrimaryButton>
                                                        <PrimaryButton @click="userAudit(user.id)">
                                                            <i class="bx bx-list-ul mr-1"></i> Audit
                                                        </PrimaryButton>

                                                <template v-if="user.role_id != 1">
                                                    <PrimaryButton class=" mr-1" @click="assignCob(user.id)"
                                                        v-if="slug == 'users'">
                                                        <i class="bx bx-list-ul mr-1"></i>COB <small class="ml-1">{{
                                                            user.user_cobs_count }}/{{
                                                                user.total_cob_count }}</small>
                                                    </PrimaryButton>

                                                    <PrimaryButton class="" @click="assignClient(user.id)"
                                                        v-if="slug == 'users'">
                                                        <i class="bx bx-list-ul mr-1"></i>Client <small class="ml-1">{{
                                                            user.user_clients_count }}/{{
                                                                user.total_client_count }}</small>
                                                    </PrimaryButton>
                                                </template>

                                                <template v-if="user.role_id == 1">
                                                    <span style="font-size: 10px;" class="text-primary"><b>The admin has access to all COBs & clients.</b></span>
                                                </template>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            <h6>Showing {{ users.from }} to {{ users.to }} of {{ users.total }} entries</h6>
                        </div>
                        <div class="float-right">
                            <Paginate :links="users.links" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>
