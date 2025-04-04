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
    users: Object,
    roles: Object,
    filters: Object,
});


const user_create_edit_ref = ref(null);
const edit = (id) => {
    user_create_edit_ref.value.edit(id)
};

const generateFilterUrl = (client_ids) => {
    var filters = {
        client_ids: client_ids,
        date_type: props.filters['date_type'] ?? "", 
        from_date: props.filters['from_date'] ?? "", 
        to_date: props.filters['to_date'] ?? "", 
        policy_type: props.filters['policy_type'] ?? "", 
        agency: props.filters['agency'] ?? "", 
        insurer: props.filters['insurer'] ?? "", 
        department: props.filters['department'] ?? "", 
        group: props.filters['group'] ?? "", 
        cob: props.filters['cob'] ?? "", 
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
                        <CreateEdit ref="user_create_edit_ref" v-bind="$props"></CreateEdit>
                        <Filter :filters="props.filters" :filter_route="'client'"></Filter>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <Search :filters="props.filters" :route_name="route('client.index')" />
                    </div>

                    <div class="card-body">
                        <div style="overflow-x: auto">
                            <table id="example" class="table table-bordered table-hover table-sm text-uppercase"
                                style="width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 50px;">SR.</th>
                                        <th style="min-width: 250px;">Client Name</th>
                                        <th style="min-width: 80px;">Policies</th>
                                        <th style="min-width: 80px;">Claims</th>
                                        <th style="min-width: 200px;">Class of Business</th>
                                        <th style="min-width: 200px;">Insurers</th>
                                        <th style="min-width: 180px;">Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(user, index) in users.data">
                                        <tr>
                                            <td>{{ (users.current_page - 1) * users.per_page + index + 1 }}</td>
                                            <td>{{ user.user_name }}</td>
                                            <td>
                                                <a :href="generateFilterUrl(user.user_id)" target="_blank">
                                                    <span class="badge bg-dark">
                                                        {{ user.policy_count }} <i class="bx bx-link-external"></i>
                                                    </span>
                                                </a>
                                            </td>
                                            <td>
                                                <a :href="`${route('claim.index')}?client_ids=${user.user_id}`"
                                                    target="_blank">
                                                    <span class="badge bg-dark">
                                                        {{ user.policy_claim_count }} <i
                                                            class="bx bx-link-external"></i>
                                                    </span>
                                                </a>
                                            </td>
                                            <td>
                                                <span v-for="(cob, i) in user.cobs.split(',')" :key="'cob-' + i"
                                                    class="badge bg-secondary mr-1">{{ cob.trim() }}</span>
                                            </td>
                                            <td>
                                                <span v-for="(insurer, i) in user.insurers.split(',')"
                                                    :key="'insurer-' + i" class="badge bg-secondary mr-1">{{
                                                        insurer.trim() }}</span>
                                            </td>
                                            <td>{{ user.user_created_at }}</td>
                                            <td>
                                                <IconButton @click="edit(user.user_id)" title="Edit">
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
                        <div class="float-left">
                            <span>Showing {{ users.from }} to {{ users.to }} of {{ users.total }} entries</span>
                        </div>
                        <div class="float-right">
                            <Paginate :links="users.links" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>