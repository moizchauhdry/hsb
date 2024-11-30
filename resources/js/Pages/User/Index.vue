<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import Paginate from "@/Components/Paginate.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import CreateEdit from "./CreateEdit.vue";

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

const search_form = useForm({
    search: ""
});

const search = () => {
    search_form.post(route("user.index", slug), {
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
                        <!-- CREATE & UPDATE MODAL -->
                        <div class="col">

                            <CreateEdit ref="user_create_edit_ref" v-bind="$props"></CreateEdit>
                           
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <!-- <form @submit.prevent="search">
                            <div class="row mb-3 d-flex align-items-center">
                                <div class="col-md-3">
                                    <input type="text" v-model="search_form.search" class="form-control"
                                        placeholder="Search">
                                </div>
                                <div class="col-md-3">
                                    <SuccessButton class="px-4 py-1 mr-1" :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing">
                                        Search
                                    </SuccessButton>
                                    <DangerButton class="px-4 py-1 mr-1" @click="reset()">Cancel</DangerButton>
                                </div>
                            </div>
                        </form> -->

                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr class="text-uppercase">
                                        <th>Sr.No.</th>
                                        <th v-if="slug == 'clients'">Code</th>
                                        <th>Name</th>
                                        <th v-if="slug == 'users'">Email</th>
                                        <th>Role</th>
                                        <th>Register Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(user, index) in users.data">
                                        <tr>
                                            <td>{{ (users.current_page - 1) * users.per_page + index + 1 }}</td>
                                            <td v-if="slug == 'clients'">{{ user.code }}</td>
                                            <td class="text-capitalize">{{ user.name }}</td>
                                            <td v-if="slug == 'users'">{{ user.email }}</td>
                                            <td class="text-capitalize">{{ user.role }}</td>
                                            <td>{{ user.created_at }}</td>
                                            <td>
                                                <PrimaryButton @click="edit(user.id)" title="Edit"><i
                                                        class="bx bx-edit mr-1"></i> Edit</PrimaryButton>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="float-right">
                            <Paginate :links="users.links" :scroll="true" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>