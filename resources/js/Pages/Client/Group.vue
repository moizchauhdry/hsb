<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import Paginate from "@/Components/Paginate.vue";
import Search from "@/Components/Search.vue";
import CreateEdit from "./CreateEdit.vue";
import IconButton from "@/Components/IconButton.vue";
import Filter from "./Filter.vue";

const props = defineProps({
    groups: Object,
    filter: Array,
});


const user_create_edit_ref = ref(null);
const edit = (id) => {
    user_create_edit_ref.value.edit(id)
};

const generateFilterUrl = (group_code) => {
    var filters = {
        client_group_code: group_code,
        date_type: props.filter['date_type'],
        from_date: props.filter['from_date'],
        to_date: props.filter['to_date'],
        policy_type: props.filter['policy_type'],
        agency: props.filter['agency'],
        insurer: props.filter['insurer'],
        department: props.filter['department'],
        group: props.filter['group'],
        cob: props.filter['cob'],
    };

    const queryParams = new URLSearchParams(filters).toString();
    return `${route("client.index")}?${queryParams}`;
};

const generateFilterUrl2 = (client_ids) => {
    var filters = {
        client: client_ids,
    };

    const queryParams = new URLSearchParams(filters).toString();
    return `${route("policy.index")}?${queryParams}`;
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
                    <div class="breadcrumb-title pe-3">My Clients</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;">
                                        <i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Client List</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="ms-auto">
                        <Filter :filter_route="'group'"></Filter>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <Search :route_name="route('client.group.index')" />
                    </div>

                    <div class="card-body">
                        <div style="overflow-x: auto">
                            <table id="example" class="table table-bordered table-hover table-sm text-uppercase"
                                style="width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 50px;">SR.</th>
                                        <th style="min-width: 250px;">Client Name</th>
                                        <th style="min-width: 80px;">Subsidiaries</th>
                                        <th style="min-width: 80px;">Policies</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(group, index) in groups.data">
                                        <tr>
                                            <td>{{ (groups.current_page - 1) * groups.per_page + index + 1 }}</td>
                                            <td>{{ group.group_name }}</td>
                                            <td>
                                                <a :href="generateFilterUrl(group.group_code)" target="_blank">
                                                    <span class="badge bg-dark">
                                                        {{ group.client_count }} <i class="bx bx-link-external"></i>
                                                    </span>
                                                </a>
                                            </td>
                                            <td>
                                                <a :href="generateFilterUrl2(group.client_ids)" target="_blank">
                                                    <span class="badge bg-dark">
                                                        {{ group.policy_count }} <i class="bx bx-link-external"></i>
                                                    </span>
                                                </a>
                                            </td>
                                            <!-- <td>
                                                <IconButton @click="edit(group.group_id)" title="Edit">
                                                    <i class="bx bx-edit bx-text-md"></i>
                                                </IconButton>
                                            </td> -->
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            <span>Showing {{ groups.from }} to {{ groups.to }} of {{ groups.total }} entries</span>
                        </div>
                        <div class="float-right">
                            <Paginate :links="groups.links" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>