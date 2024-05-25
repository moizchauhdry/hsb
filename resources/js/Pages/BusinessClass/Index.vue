<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import "@vuepic/vue-datepicker/dist/main.css";
import CreateEdit from "./CreateUpdate.vue";
import { ref } from "vue";

defineProps({
    businessClasses: Array,
    businessClass: Object,
});

const create_edit_ref = ref(null);
const edit = (id) => {
    create_edit_ref.value.edit(id)
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

                <div class="card">
                    <div class="card-body">
                        <div class="d-lg-flex align-items-center mb-4 gap-3">
                            <div class="position-relative">
                                <input type="text" class="form-control ps-5 radius-30"
                                    placeholder="Search Business Class"> <span
                                    class="position-absolute top-50 product-show translate-middle-y"><i
                                        class="bx bx-search"></i></span>
                            </div>
                            <div class="ms-auto">
                                <CreateEdit v-bind="$props" ref="create_edit_ref"></CreateEdit>
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
                                    <template v-for="(cls, index) in businessClasses.data" :key="cls.id">
                                        <tr>
                                            <td>{{ cls.id }}</td>
                                            <td>{{ cls.class_name }}</td>
                                            <td>{{ cls.percentage }}</td>
                                            <td>{{ cls.created_at }}</td>
                                            <td>
                                                <button type="button" @click="edit(cls.id)" title="Edit"
                                                    data-bs-toggle="modal" data-bs-target="#exampleLargeModal"
                                                    class="btn btn-primary btn-sm radius-30"><i
                                                        class="bx bx-edit"></i></button>
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
