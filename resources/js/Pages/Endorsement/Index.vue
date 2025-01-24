<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import Filter from "./Filter.vue";
import Paginate from "@/Components/Paginate.vue";
import Search from "@/Components/Search.vue";
import Table from "./Table.vue";

const props = defineProps({
    policies: Array,
    filters: Object
});

const permission = usePage().props.can;
</script>

<template>

    <Head title="Policies" />

    <AuthenticatedLayout>
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <div class="page-breadcrumb d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Endorsements</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;">
                                        <i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active text-capitalize" aria-current="page">List
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="ms-auto">
                        <!-- ref -->
                    </div>

                    <div class="ms-auto" style="display: flex; justify-content: space-between; align-items: center;">
                        <Filter :filters="props.filters"></Filter>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <Search :route_name="route('endorsement.index')" />
                    </div>
                    <div class="card-body">
                        <Table v-bind="$props" :detail="true"></Table>
                    </div>
                    <div class="card-footer">
                        <div class="float-left">
                            <span>Showing {{ policies.from }} to {{ policies.to }} of {{ policies.total }}entries</span>
                        </div>
                        <div class="float-right">
                            <Paginate :links="policies.links" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>