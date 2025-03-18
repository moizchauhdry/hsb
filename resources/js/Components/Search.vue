<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import DarkButton from "./DarkButton.vue";
import SecondaryButton from "./SecondaryButton.vue";

const props = defineProps({
    route_name: Array,
    filters: Object,
});

const form = useForm({
    page_count: 10,
    search: "",
    date_type: "",
    from_date: "",
    to_date: "",
    policy_type: [],
    client_ids: [],
    agency: [],
    insurer: [],
    department: [],
    group: [],
    cob: [],
});

const search = () => {

    var saved_filters = "";
    saved_filters = props.filters;

    form.date_type = saved_filters?.date_type || ""
    form.from_date = saved_filters?.from_date || ""
    form.to_date = saved_filters?.to_date || ""
    form.policy_type = saved_filters?.policy_type || []
    form.client_ids = saved_filters?.client_ids || []
    form.agency = saved_filters?.agency || []
    form.insurer = saved_filters?.insurer || []
    form.department = saved_filters?.department || []
    form.group = saved_filters?.group || []
    form.cob = saved_filters?.cob || []

    var filters = {
        search: form.search,
        page_count: form.page_count,

        date_type: form.date_type,
        from_date: form.from_date,
        to_date: form.to_date,
        policy_type: form.policy_type,
        client_ids: form.client_ids,
        agency: form.agency,
        insurer: form.insurer,
        cob: form.cob,
        department: form.department,
        group: form.group,
    };

    const queryParams = new URLSearchParams(filters).toString();
    var url_with_filters = `${props.route_name}?${queryParams}`;

    form.post(url_with_filters, {
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

const reset = () => {
    form.search = "";
    search();
};
</script>

<template>
    <div class="row align-items-center">
        <div class="col-12 col-md-2 d-flex align-items-center mb-2 mb-md-0">
            <span class="mr-2">Show</span>
            <select v-model="form.page_count" class="form-control" style="width: 70px;" @change="search()">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="500">500</option>
            </select>
            <span class="ml-2">entries</span>
        </div>

        <div class="col-12 col-md-10">
            <form class="d-flex flex-column flex-md-row justify-content-md-end align-items-md-center"
                @submit.prevent="search()">
                <div class="d-flex flex-column flex-md-row align-items-md-center">
                    <input type="text" v-model="form.search" class="form-control mr-2 mb-2" placeholder="Search"
                        style="width: 100%;">
                    <div class="d-flex">
                        <DarkButton class="mb-2 px-4 py-1 mr-1" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">Search</DarkButton>
                        <SecondaryButton class="mb-2 px-2 py-1" @click="reset()"><i class="bx bx-refresh text-lg"></i>
                        </SecondaryButton>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
