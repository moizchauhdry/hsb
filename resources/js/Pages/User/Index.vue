<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";

defineProps({
    users: Object,
    roles: Object,
});

const user_modal = ref(false);
const edit_mode = ref(false);

const form = useForm({
    user_id: "",
    name: "",
    phone: "",
    email: "",
    address: "",
    cnic_no: "",
    designation: "",
    qualification: "",
    password: "",
    password_confirmation: "",
    role: "",
});

const create = () => {
    user_modal.value = true;
    edit_mode.value = false;
};

const submit = () => {
    form.post(route("user.create"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => error(),
        onFinish: () => { },
    });
};

const edit = (user) => {
    user_modal.value = true;
    edit_mode.value = true;

    form.user_id = user.id;
    form.name = user.name;
    form.phone = user.phone;
    form.email = user.email;
    form.address = user.address;
    form.cnic_no = user.cnic_no;
    form.designation = user.designation;
    form.qualification = user.qualification;
    form.role = user.role_id;
};

const update = () => {
    form.post(route("user.update"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => error(),
        onFinish: () => { },
    });
};

const error = () => {
    // alert('error');
};

const closeModal = () => {
    user_modal.value = false;
    form.reset();
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
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#exampleLargeModal" @click="create"><i
                                    class="bx bx-plus"></i>Add</button>
                            <div class="modal fade show" id="exampleLargeModal" tabindex="-1" aria-hidden="true"
                                style="display: block;" v-if="user_modal">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <form @submit.prevent="edit_mode ? update() : submit()">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Manage Users</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close" @click="closeModal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label for="input21" class="form-label">Role</label>
                                                        <select id="input21" class="form-select" v-model="form.role">
                                                            <option value="">Choose...</option>
                                                            <template v-for="role in roles" :key="role.id">
                                                                <option :value="role.id">{{ role.name }}</option>
                                                            </template>
                                                        </select>
                                                        <InputError :message="form.errors.role" />
                                                    </div>
                                                    <div class="col-md-8">
                                                        <label for="input13" class="form-label">Name</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.name">
                                                            <span class="position-absolute top-50 translate-middle-y"><i
                                                                    class='bx bx-user'></i></span>
                                                        </div>
                                                        <InputError :message="form.errors.name" />
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="input16" class="form-label">Email</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input16"
                                                                placeholder="" v-model="form.email">
                                                            <span class="position-absolute top-50 translate-middle-y"><i
                                                                    class='bx bx-envelope'></i></span>
                                                        </div>
                                                        <InputError :message="form.errors.email" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input15" class="form-label">Phone</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input15"
                                                                placeholder="" v-model="form.phone">
                                                            <span class="position-absolute top-50 translate-middle-y"><i
                                                                    class='bx bx-phone'></i></span>
                                                        </div>
                                                        <InputError :message="form.errors.phone" />
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="input16" class="form-label">CNIC</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input16"
                                                                placeholder="xxxxx-xxxxxxx-x" v-model="form.cnic_no">
                                                            <span class="position-absolute top-50 translate-middle-y"><i
                                                                    class='bx bx-id-card'></i></span>
                                                        </div>
                                                        <InputError :message="form.errors.cnic_no" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input16" class="form-label">Designation</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input16"
                                                                placeholder="" v-model="form.designation">
                                                            <span class="position-absolute top-50 translate-middle-y"><i
                                                                    class='bx bx-chair'></i></span>
                                                        </div>
                                                        <InputError :message="form.errors.designation" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input16" class="form-label">Qualification</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input16"
                                                                placeholder=""
                                                                v-model="form.qualification">
                                                            <span class="position-absolute top-50 translate-middle-y"><i
                                                                    class='bx bx-book-reader'></i></span>
                                                        </div>
                                                        <InputError :message="form.errors.qualification" />
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label for="input16" class="form-label">Address</label>
                                                        <textarea v-model="form.address" class="form-control"
                                                            rows="4"></textarea>
                                                        <InputError :message="form.errors.address" />
                                                    </div>

                                                    <template v-if="!edit_mode">
                                                        <div class="col-md-6">
                                                            <label for="input17" class="form-label">Password</label>
                                                            <div class="position-relative input-icon">
                                                                <input type="password" class="form-control" id="input17"
                                                                    placeholder="" v-model="form.password">
                                                                <span
                                                                    class="position-absolute top-50 translate-middle-y"><i
                                                                        class='bx bx-lock-alt'></i></span>
                                                            </div>
                                                            <InputError :message="form.errors.password" />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="input17" class="form-label">Confirm
                                                                Password</label>
                                                            <div class="position-relative input-icon">
                                                                <input type="password" class="form-control" id="input17"
                                                                    placeholder=""
                                                                    v-model="form.password_confirmation">
                                                                <span
                                                                    class="position-absolute top-50 translate-middle-y"><i
                                                                        class='bx bx-lock-alt'></i></span>
                                                            </div>
                                                        </div>
                                                    </template>

                                                    
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
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Register Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(user, index) in users.data">
                                        <tr>
                                            <td>{{ user.id }}</td>
                                            <td>{{ user.name }}</td>
                                            <td>{{ user.email }}</td>
                                            <td>{{ user.role }}</td>
                                            <td>{{ user.created_at }}</td>
                                            <td>
                                                <button type="button" @click="edit(user)" title="Edit"
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