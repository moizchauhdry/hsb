<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from 'axios';

import Modal from "@/Components/Modal.vue";
import InputError from "@/Components/InputError.vue";

import PrimaryButton from "@/Components/PrimaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps({
    roles: Object,
    slug: Object,
});

const modal = ref(false);
const edit_mode = ref(false);
const slug = usePage().props.slug;
const cobs = ref([]);

const form = useForm({
    user_id: "",
    name: "",
    phone: "",
    email: "",
    address: "",
    cnic_name: "",
    cnic_no: "",
    cnic_expiry_date: "",
    father_name: "",
    gender: "",
    dob: "",
    type: "",
    designation: "",
    qualification: "",
    password: "",
    password_confirmation: "",
    role: "",

    cob_id: [],
});

const create = () => {
    modal.value = true;
    edit_mode.value = false;

    fetchCobs();
};

const submit = () => {
    form.post(route("user.create"), {
        preserveScroll: true,
        onSuccess: () => close(),
        onError: () => error(),
        onFinish: () => { },
    });
};

const edit = (id) => {
    modal.value = true;
    edit_mode.value = true;
    fetchCobs();

    axios.get(`/users/edit/${id}`)
        .then(({ data }) => {
            console.log(data)

            form.user_id = data.user.id;
            form.name = data.user.name;
            form.phone = data.user.phone;
            form.email = data.user.email;
            form.address = data.user.address;
            form.cnic_name = data.user.cnic_name;
            form.cnic_no = data.user.cnic_no;
            form.cnic_expiry_date = data.user.cnic_expiry_date;
            form.father_name = data.user.father_name;
            form.gender = data.user.gender;
            form.dob = data.user.dob;
            form.type = data.user.type;
            form.designation = data.user.designation;
            form.qualification = data.user.qualification;
            form.role = data.role_id;
            form.cob_id = data.selected_cobs;
        });
};

const update = () => {
    form.post(route("user.update"), {
        preserveScroll: true,
        onSuccess: () => close(),
        onError: () => error(),
        onFinish: () => { },
    });
};

const error = () => {
    // alert('error');
};

const close = () => {
    modal.value = false;
    form.reset();
};

const fetchCobs = () => {
    axios.get("/axios/fetch/cobs")
        .then(({ data }) => {
            console.log(data)
            cobs.value = data.cobs;
        });
};

defineExpose({ edit: (id) => edit(id) });

</script>

<template>
    <PrimaryButton @click="create"><i class="bx bx-plus text-lg"></i>Add Client</PrimaryButton>

    <Modal :show="modal" @close="close">
        <form @submit.prevent="edit_mode ? update() : submit()">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 flex justify-between items-center">
                    <span>{{ edit_mode ? 'Edit' : 'Add' }} Client</span>
                    <span class="text-sm text-gray-500">
                        <span v-if="form.user_id" class="mr-2">ID #{{ form.user_id }}</span>
                    </span>
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    <hr>
                </p>

                <div class="mt-6">
                    <div class="row g-3">
                        <div class="col-md-6" style="display: none;">
                            <label for="input21" class="form-label">Role</label>
                            <select id="input21" class="form-select text-capitalize" v-model="form.role">
                                <option value="">Choose...</option>
                                <template v-for="role in roles" :key="role.id">
                                    <option :value="role.id">{{ role.name }}</option>
                                </template>
                            </select>
                            <InputError :message="form.errors.role" />
                        </div>
                        <div class="col-md-6">
                            <label for="input16" class="form-label">Type</label>
                            <select id="input21" class="form-select" v-model="form.type">
                                <option value="">Choose...</option>
                                <option :value="'individual'">Individual</option>
                                <option :value="'business'">Business</option>
                            </select>
                            <InputError :message="form.errors.type" />
                        </div>
                        
                        <div class="col-md-6">
                            <label for="input13" class="form-label">Name</label>
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" id="input13" placeholder="" v-model="form.name">
                                <span class="position-absolute top-50 translate-middle-y"><i
                                        class='bx bx-user'></i></span>
                            </div>
                            <InputError :message="form.errors.name" />
                        </div>
                        <div class="col-md-6">
                            <label for="input13" class="form-label">Father Name</label>
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" id="input13" placeholder=""
                                    v-model="form.father_name">
                                <span class="position-absolute top-50 translate-middle-y"><i
                                        class='bx bx-user'></i></span>
                            </div>
                            <InputError :message="form.errors.father_name" />
                        </div>
                      
                        <div class="col-md-6">
                            <label for="input15" class="form-label">Phone</label>
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" id="input15" placeholder=""
                                    v-model="form.phone">
                                <span class="position-absolute top-50 translate-middle-y"><i
                                        class='bx bx-phone'></i></span>
                            </div>
                            <InputError :message="form.errors.phone" />
                        </div>

                        <div class="col-md-6">
                            <label for="input16" class="form-label">{{ form.type == "individual" ?
                                'CNIC' : "NTN" }} #</label>
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" id="input16"
                                    :placeholder="form.type == 1 ? 'xxxxx-xxxxxxx-x' : ''" v-model="form.cnic_no">
                                <span class="position-absolute top-50 translate-middle-y"><i
                                        class='bx bx-id-card'></i></span>
                            </div>
                            <InputError :message="form.errors.cnic_no" />
                        </div>

                        <div class="col-md-6">
                            <label for="input16" class="form-label">Email</label>
                            <div class="position-relative input-icon">
                                <input type="text" class="form-control" id="input16" placeholder=""
                                    v-model="form.email">
                                <span class="position-absolute top-50 translate-middle-y"><i
                                        class='bx bx-envelope'></i></span>
                            </div>
                            <InputError :message="form.errors.email" />
                        </div>

                        <template v-if="form.type == 'individual'">

                            <div class="col-md-6">
                                <label for="input16" class="form-label">CNIC Expiry
                                    Date</label>
                                <div class="position-relative input-icon">
                                    <input type="date" class="form-control" id="input16" placeholder=""
                                        v-model="form.cnic_expiry_date">
                                    <span class="position-absolute top-50 translate-middle-y"><i
                                            class='bx bx-id-card'></i></span>
                                </div>
                                <InputError :message="form.errors.cnic_expiry_date" />
                            </div>
                            <div class="col-md-6">
                                <label for="input16" class="form-label">Gender</label>
                                <select id="input21" class="form-select" v-model="form.gender">
                                    <option selected disabled>Choose Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="others">Others</option>
                                </select>
                                <InputError :message="form.errors.gender" />
                            </div>
                            <div class="col-md-6">
                                <label for="input16" class="form-label">Date Of
                                    Birth</label>
                                <div class="position-relative input-icon">
                                    <input type="date" class="form-control" id="input16" placeholder=""
                                        v-model="form.dob">
                                    <span class="position-absolute top-50 translate-middle-y"><i
                                            class='bx bx-id-card'></i></span>
                                </div>
                                <InputError :message="form.errors.dob" />
                            </div>

                            <div class="col-md-6">
                                <label for="input16" class="form-label">Designation</label>
                                <div class="position-relative input-icon">
                                    <input type="text" class="form-control" id="input16" placeholder=""
                                        v-model="form.designation">
                                    <span class="position-absolute top-50 translate-middle-y"><i
                                            class='bx bx-chair'></i></span>
                                </div>
                                <InputError :message="form.errors.designation" />
                            </div>

                            <div class="col-md-6">
                                <label for="input16" class="form-label">Qualification</label>
                                <div class="position-relative input-icon">
                                    <input type="text" class="form-control" id="input16" placeholder=""
                                        v-model="form.qualification">
                                    <span class="position-absolute top-50 translate-middle-y"><i
                                            class='bx bx-book-reader'></i></span>
                                </div>
                                <InputError :message="form.errors.qualification" />
                            </div>
                        </template>

                        
                        <div class="col-md-12">
                            <label for="input16" class="form-label">Address</label>
                            <textarea v-model="form.address" class="form-control" rows="4"></textarea>
                            <InputError :message="form.errors.address" />
                        </div>

                    
                        <template v-if="!edit_mode && form.role != 2">
                            <div class="col-md-6">
                                <label for="input17" class="form-label">Password</label>
                                <div class="position-relative input-icon">
                                    <input type="password" class="form-control" id="input17" placeholder=""
                                        v-model="form.password">
                                    <span class="position-absolute top-50 translate-middle-y"><i
                                            class='bx bx-lock-alt'></i></span>
                                </div>
                                <InputError :message="form.errors.password" />
                            </div>
                            <div class="col-md-6">
                                <label for="input17" class="form-label">Confirm
                                    Password</label>
                                <div class="position-relative input-icon">
                                    <input type="password" class="form-control" id="input17" placeholder=""
                                        v-model="form.password_confirmation">
                                    <span class="position-absolute top-50 translate-middle-y"><i
                                            class='bx bx-lock-alt'></i></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="close"> Cancel </SecondaryButton>

                    <SuccessButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Save
                    </SuccessButton>
                </div>
            </div>
        </form>
    </Modal>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>