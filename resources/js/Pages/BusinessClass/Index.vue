<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import "@vuepic/vue-datepicker/dist/main.css";
import CreateEdit from "./CreateUpdate.vue";
import { ref } from "vue";
import Paginate from "@/Components/Paginate.vue";
import SuccessButton from "@/Components/SuccessButton.vue";

defineProps({
    businessClasses: Array,
    businessClass: Object,
});

const create_edit_ref = ref(null);
const edit = (id) => {
    create_edit_ref.value.edit(id)
};



const search_form = useForm({
    search: ""
});

const search = () => {
    search_form.post(route("businessClass.index"), {
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


</script>

<template>

    <Head title="Business Class" />

    <AuthenticatedLayout>
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Business Class</div>
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
                        <form @submit.prevent="search">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <input type="text" v-model="search_form.search" class="form-control"
                                        placeholder="Search">
                                </div>
                                <div class="col-md-3">
                                    <SuccessButton class="px-4 py-1" :class="{ 'opacity-25': search_form.processing }"
                                        :disabled="search_form.processing">
                                        Search
                                    </SuccessButton>
                                </div>
                            </div>
                        </form>

                        <div class="d-lg-flex align-items-center mb-4 gap-3">
                            <div class="ms-auto">
                                <CreateEdit v-bind="$props" ref="create_edit_ref"></CreateEdit>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-light">
                                    <tr class="text-uppercase">
                                        <th>Sr.No.</th>
                                        <th>COB Code</th>
                                        <th>Class Name</th>
                                        <th>Department</th>
                                        <th>Percentage</th>
                                        <th>Created Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(cls, index) in businessClasses.data" :key="cls.id">
                                        <tr>
                                            <td>{{ (businessClasses.current_page - 1) * businessClasses.per_page + index
                                                + 1 }}</td>
                                            <td>
                                                {{ cls.code }}
                                            </td>
                                            <td>{{ cls.class_name }}</td>
                                            <td>{{ cls.department_name }}</td>
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
                    <div class="card-footer">
                        <div class="float-right">
                            <Paginate :links="businessClasses.links" :scroll="true" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>
