<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import "@vuepic/vue-datepicker/dist/main.css";
import CreateEdit from "./CreateUpdate.vue";
import { ref } from "vue";
import Paginate from "@/Components/Paginate.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import IconButton from "@/Components/IconButton.vue";
import Search from "@/Components/Search.vue";

defineProps({
    cobs: Array,
    businessClass: Object,
});

const search_form = useForm({
    search: ""
});

const search = () => {
    search_form.post(route("cob.index"), {
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


const cob_create_edit_ref = ref(null);
const edit = (id) => {
    cob_create_edit_ref.value.edit(id)
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
                    <div class="ms-auto">
                        <CreateEdit ref="cob_create_edit_ref" v-bind="$props"></CreateEdit>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <Search :route_name="route('cob.index')" />
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-light">
                                    <tr class="text-uppercase">
                                        <th>Sr.</th>
                                        <th>Class Name</th>
                                        <th>Department</th>
                                        <th>Group</th>
                                        <th>Created Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(cob, index) in cobs.data" :key="cob.id">
                                        <tr>
                                            <td>{{ (cobs.current_page - 1) * cobs.per_page + index + 1 }}</td>
                                            <td><span :title="cob.cob_code">{{ cob.cob_name }}</span></td>
                                            <td><span class="badge bg-secondary">{{ cob.department_name }}</span></td>
                                            <td><span class="badge bg-secondary">{{ cob.group_name }}</span></td>
                                            <td>{{ cob.created_at }}</td>
                                            <td>
                                                <IconButton @click="edit(cob.cob_id)" title="Edit">
                                                    <i class="bx bx-edit bx-text-md"></i>
                                                </IconButton>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="float-right">
                            <Paginate :links="cobs.links" :scroll="true" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>
