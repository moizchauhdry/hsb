<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import "@vuepic/vue-datepicker/dist/main.css";
import CreateEdit from "./CreateUpdate.vue";
import { ref } from "vue";
import axios from 'axios';

defineProps({
    businessClasses: Array,
    businessClass: Object,
});

const modal = ref(false);
const edit_mode = ref(false);

const form = useForm({
    business_class_id: "",
    class_name: "",
    percentage: "",
    insurance_id: ""
});

const edit = (businessClass) => {
    modal.value = true;
    edit_mode.value = true;
    axios.get("/businessClass/edit")
        .then(({ data }) => {
            insurances.value = data.insurances;
        });

    form.business_class_id = businessClass.id;
    form.class_name = businessClass.class_name;
    form.percentage = businessClass.percentage;
    form.insurance_id = businessClass.insurance_id;
    
};

</script>

<template>

    <Head title="Business Class" />

    <AuthenticatedLayout>
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Business Class </div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;">
                                        <i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Business Class List</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="modal fade show" id="exampleLargeModal" tabindex="-1" aria-hidden="true" style="display: block;"
            v-if="modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form @submit.prevent="submit()">
                        <div class="modal-header">
                            <h5 class="modal-title">Business Class</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                @click="close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="input13" class="form-label">Class Name</label>
                                    <input type="text" class="form-control" id="input13"
                                        placeholder="" v-model="form.class_name">
                                    
                                    <InputError :message="form.errors.class_name" />
                                </div>
                                <div class="col-md-6">
                                    <label for="input13" class="form-label">Percentage</label>
                                    <input type="number" class="form-control" id="input13"
                                        placeholder="" v-model="form.percentage" @input="validateInput">
                                    
                                    <InputError :message="form.errors.percentage" />
                                </div>
                                <div class="col-md-12">
                                    <label for="input21" class="form-label">Insurance</label>

                                    <select id="input21" class="form-select" multiple
                                        v-model="form.insurance_id">
                                        <template v-for="insurance in insurances" :key="insurance.id">
                                            <option :value="insurance.id">{{ insurance.name }}
                                            </option>
                                        </template>
                                    </select>
                                    <InputError :message="form.errors.insurance_id" />
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

                <div class="card">
                    <div class="card-body">
                        <div class="d-lg-flex align-items-center mb-4 gap-3">
                            <div class="position-relative">
                                <input type="text" class="form-control ps-5 radius-30" placeholder="Search Business Class"> <span
                                    class="position-absolute top-50 product-show translate-middle-y"><i
                                        class="bx bx-search"></i></span>
                            </div>
                            <div class="ms-auto">
                                <CreateEdit v-bind="$props"></CreateEdit>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Class Name</th>
                                        <th>Percentage</th>
                                        <th>Created Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(businessClas, index) in businessClasses.data">
                                        <tr>
                                            <td>{{ businessClas.id }}</td>
                                            <td>{{ businessClas.class_name }}</td>
                                            <td>{{ businessClas.percentage }}</td>
                                            <td>{{ businessClas.created_at }}</td>
                                            <td>
                                                <button type="button" @click="edit(businessClas)" title="Edit"  data-bs-toggle="modal" data-bs-target="#exampleLargeModal"
                                                    class="btn btn-primary btn-sm radius-30"><i class="bx bx-edit"></i></button>
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
