<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";

defineProps({
    classOfBusinesses: Object,
    insurances: Object
});

const class_of_business_modal = ref(false);
const edit_mode = ref(false);

const form = useForm({
    class_of_business_id: "",
    b_class_name: "",
    brokeage_rate_percentage: "",
    insurance_name: "",
});

const create = () => {
    class_of_business_modal.value = true;
    edit_mode.value = false;
};

const submit = () => {
    form.post(route("class-of-business.create"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => error(),
        onFinish: () => { },
    });
};

const edit = (classOfBusiness) => {
    class_of_business_modal.value = true;
    edit_mode.value = true;

    form.class_of_business_id = classOfBusiness.id;
    form.b_class_name = classOfBusiness.b_class_name;
    form.brokeage_rate_percentage = classOfBusiness.brokeage_rate_percentage;
    form.insurance_name = classOfBusiness.insurance_name;
};

const update = () => {
    form.post(route("class-of-business.update"), {
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
    class_of_business_modal.value = false;
    form.reset();
};

</script>

<template>

    <Head title="Class Of Business" />

    <AuthenticatedLayout>
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Class Of Business / Insurance </div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;">
                                        <i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Class Of Business List</li>
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
                                style="display: block;" v-if="class_of_business_modal">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <form @submit.prevent="edit_mode ? update() : submit()">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Class Of Business / Insurance</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close" @click="closeModal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">B Class Name</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.b_class_name">
                                                            <span class="position-absolute top-50 translate-middle-y"><i
                                                                    class='bx bx-user'></i></span>
                                                        </div>
                                                        <InputError :message="form.errors.b_class_name" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="input13" class="form-label">Brokeage Rate Percentage</label>
                                                        <div class="position-relative input-icon">
                                                            <input type="text" class="form-control" id="input13"
                                                                placeholder="" v-model="form.brokeage_rate_percentage">
                                                            <span class="position-absolute top-50 translate-middle-y">%</span>
                                                        </div>
                                                        <InputError :message="form.errors.brokeage_rate_percentage" />
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="input21" class="form-label">Insurance Name</label>
                                                        
                                                        <select id="input21" class="form-select" v-model="form.insurance_name" multiple="">
                                                            <option value="">Choose...</option>
                                                            <template v-for="insurance in insurances" :key="insurance.id">
                                                                <option :value="insurance.name">{{ insurance.name }}</option>
                                                            </template>
                                                        </select>
                                                        <InputError :message="form.errors.insurance_name" />
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
                                        <th>B Class Name</th>
                                        <th>Brokeage Rate Percentage</th>
                                        <th>Created Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(classOfBusiness, index) in classOfBusinesses.data">
                                        <tr>
                                            <td>{{ classOfBusiness.id }}</td>
                                            <td>{{ classOfBusiness.b_class_name }}</td>
                                            <td>{{ classOfBusiness.brokeage_rate_percentage }}</td>
                                            <td>{{ classOfBusiness.created_at }}</td>
                                            <td>
                                                <button type="button" @click="edit(classOfBusiness)" title="Edit"
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

