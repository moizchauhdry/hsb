<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";

defineProps({
    roles: Object,
    permissions: Object,
});

const modal = ref(false);
const edit_mode = ref(false);

const form = useForm({
    id: "",
    name: "",
    permissions: [],
});

const create = () => {
    modal.value = true;
    edit_mode.value = false;
};

const submit = () => {
    form.post(route("role.create"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => error(),
        onFinish: () => form.reset(),
    });
};

const edit = (role) => {
    modal.value = true;
    edit_mode.value = true;

    form.id = role.id;
    form.name = role.name;
    form.permissions = role.permissions;
};

const update = () => {
    form.post(route("role.update"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => error(),
        onFinish: () => form.reset(),
    });
};

const error = () => {
    // alert('error');
};

const closeModal = () => {
    modal.value = false;
    form.reset();
};
</script>


<template>

    <Head title="Roles" />

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
                                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Role & Permission</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="ms-auto">
                        <!-- CREATE & UPDATE MODAL -->
                        <div class="col">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#exampleLargeModal" @click="create">
                                <i class="bx bx-plus"></i>Add</button>
                            <div class="modal fade show" id="exampleLargeModal" tabindex="-1" aria-hidden="true"
                                style="display: block;" v-if="modal">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <form @submit.prevent="edit_mode ? update() : submit()">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Role & Permission</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close" @click="closeModal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-12">
                                                        <label for="input13" class="form-label">Role Name</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="Name" v-model="form.name">
                                                            <span class="position-absolute top-50 translate-middle-y"><i
                                                                    class='bx bx-user'></i></span>
                                                        </div>
                                                        <InputError :message="form.errors.name" />
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="input13" class="form-label">Role Name</label>

                                                        <template v-for="permission in permissions"
                                                            :key="permission.id">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    :value="permission.id" id="flexCheckDefault"
                                                                    v-model="form.permissions">
                                                                <label class="form-check-label" for="flexCheckDefault">
                                                                    {{ permission.name }}
                                                                </label>
                                                            </div>
                                                        </template>

                                                        <InputError :message="form.errors.permissions" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    data-bs-dismiss="modal" @click="closeModal">Close</button>

                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    {{ edit_mode ? 'Save & Update' : 'Save & Submit' }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Created Date</th>
                                        <th>Updated Date</th>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(role, index) in roles.data">
                                        <tr>
                                            <td>{{ role.id }}</td>
                                            <td>{{ role.name }}</td>
                                            <td>{{ role.created_at }}</td>
                                            <td>{{ role.updated_at }}</td>
                                            <td>
                                                <button type="button" @click="edit(role)" title="Edit"
                                                    clas="btn btn-primary"><i class="bx bx-edit"></i></button>
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