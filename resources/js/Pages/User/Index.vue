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

const form = useForm({
    name: "",
    phone: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "",
});

const createUser = () => {
    user_modal.value = true;
};

const submit = () => {
    form.post(route("user.update"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => error(),
        onFinish: () => form.reset(),
    });
};

const error = () => {
    alert('error');
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
                                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">User List</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="ms-auto">
                        <div class="btn-group">
                            <div class="col">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleLargeModal" @click="createUser">Add User</button>
                                <!-- Modal -->
                                <div class="modal fade show" id="exampleLargeModal" tabindex="-1" aria-hidden="true"
                                    style="display: block;" v-if="user_modal">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <form @submit.prevent="submit()">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Manage Users</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close" @click="closeModal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-12">
                                                            <label for="input13" class="form-label">Name</label>
                                                            <div class="position-relative input-icon">
                                                                <input type="text" class="form-control" id="input13"
                                                                    placeholder="Name" v-model="form.name">
                                                                <span
                                                                    class="position-absolute top-50 translate-middle-y"><i
                                                                        class='bx bx-user'></i></span>
                                                            </div>
                                                            <InputError :message="form.errors.name" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="input15" class="form-label">Phone</label>
                                                            <div class="position-relative input-icon">
                                                                <input type="text" class="form-control" id="input15"
                                                                    placeholder="Phone" v-model="form.phone">
                                                                <span
                                                                    class="position-absolute top-50 translate-middle-y"><i
                                                                        class='bx bx-phone'></i></span>
                                                            </div>
                                                            <InputError :message="form.errors.name" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="input16" class="form-label">Email</label>
                                                            <div class="position-relative input-icon">
                                                                <input type="text" class="form-control" id="input16"
                                                                    placeholder="Email" v-model="form.email">
                                                                <span
                                                                    class="position-absolute top-50 translate-middle-y"><i
                                                                        class='bx bx-envelope'></i></span>
                                                            </div>
                                                            <InputError :message="form.errors.email" />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="input17" class="form-label">Password</label>
                                                            <div class="position-relative input-icon">
                                                                <input type="password" class="form-control" id="input17"
                                                                    placeholder="Password" v-model="form.password">
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
                                                                    placeholder="Password"
                                                                    v-model="form.password_confirmation">
                                                                <span
                                                                    class="position-absolute top-50 translate-middle-y"><i
                                                                        class='bx bx-lock-alt'></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="input21" class="form-label">Role</label>
                                                            <select id="input21" class="form-select"
                                                                v-model="form.role">
                                                                <option value="">Choose...</option>
                                                                <template v-for="role in roles" :key="role.id">
                                                                    <option :value="role.id">{{ role.name }}</option>
                                                                </template>
                                                            </select>
                                                            <InputError :message="form.errors.role" />
                                                        </div>
                                                        <!-- <div class="col-md-12">
                                                            <label for="input19" class="form-label">Country</label>
                                                            <select id="input19" class="form-select"
                                                                v-model="form.country">
                                                                <option selected>Choose...</option>
                                                                <option>One</option>
                                                                <option>Two</option>
                                                                <option>Three</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="input20" class="form-label">City</label>
                                                            <div class="position-relative input-icon">
                                                                <input type="text" class="form-control" id="input20"
                                                                    placeholder="City" v-model="form.city">
                                                                <span
                                                                    class="position-absolute top-50 translate-middle-y"><i
                                                                        class='bx bx-buildings'></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="input21" class="form-label">State</label>
                                                            <select id="input21" class="form-select"
                                                                v-model="form.state">
                                                                <option selected>Choose...</option>
                                                                <option>One</option>
                                                                <option>Two</option>
                                                                <option>Three</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="input22" class="form-label">Zip</label>
                                                            <div class="position-relative input-icon">
                                                                <input type="text" class="form-control" id="input22"
                                                                    placeholder="Zip" v-model="form.zip">
                                                                <span
                                                                    class="position-absolute top-50 translate-middle-y"><i
                                                                        class='bx bx-pin'></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="input23" class="form-label">Address</label>
                                                            <textarea class="form-control" id="input23"
                                                                v-model="form.address" placeholder="Address ..."
                                                                rows="3"></textarea>
                                                        </div> -->
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal" @click="closeModal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end breadcrumb-->
                <!-- <h6 class="mb-0 text-uppercase">Admin Users</h6>
                <hr /> -->
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
                                               <button type="button" @click="createUser(user.id)" clas="btn btn-primary">Edit</button>
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