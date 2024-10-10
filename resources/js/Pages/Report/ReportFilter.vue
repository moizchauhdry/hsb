<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from '@/Components/InputError.vue';
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { onMounted } from "vue";
import moment from 'moment';


defineProps({
    data: Array
});

const modal = ref(false);
const edit = ref(false);
const data = usePage().props.data;
const slug = usePage().props.slug;
const filter = usePage().props.filter;

var months = [
    { id: 1, name: "January" },
    { id: 2, name: "February" },
    { id: 3, name: "March" },
    { id: 4, name: "April" },
    { id: 5, name: "May" },
    { id: 6, name: "June" },
    { id: 7, name: "July" },
    { id: 8, name: "August" },
    { id: 9, name: "September" },
    { id: 10, name: "October" },
    { id: 11, name: "November" },
    { id: 12, name: "December" },
];

var years = [
    { id: 1, value: "2023" },
    { id: 2, value: "2024" },
    { id: 3, value: "2025" },
    { id: 4, value: "2026" },
    { id: 5, value: "2027" },
    { id: 6, value: "2028" },
    { id: 7, value: "2029" },
    { id: 8, value: "2030" },
];

var saved_filters = "";

const form = useForm({
    date_type: "",
    month: "",
    year: "",
    policy_type: "",

    client: "",
    agency: "",
    insurer: "",
    cob: "",
});

const create = () => {
    modal.value = true;
    edit.value = false;

    form.month = filter.month;
    form.year = filter.year;
    form.date_type = "date_of_insurance";

    saved_filters = localStorage.getItem('filters');

    if (saved_filters) {
        saved_filters = JSON.parse(saved_filters);
        form.date_type = saved_filters.date_type
        form.month = saved_filters.month
        form.year = saved_filters.year
        form.policy_type = saved_filters.policy_type
        form.client = saved_filters.client
        form.agency = saved_filters.agency
        form.cob = saved_filters.cob
        form.insurer = saved_filters.insurer
    }
};

const submit = () => {
    var filters = {
        date_type: form.date_type,
        month: form.month,
        year: form.year,
        policy_type: form.policy_type,

        client: form.client,
        agency: form.agency,
        cob: form.cob,
        insurer: form.insurer,
    };

    const queryParams = new URLSearchParams(filters).toString();
    const urlWithFilters = `${route("report.index", slug)}?${queryParams}`;

    form.post(urlWithFilters, {
        preserveScroll: true,
        onSuccess: (response) => {
            closeModal();
            localStorage.setItem('filters', JSON.stringify(filters));
        },
        onError: (errors) => {
            console.log(errors);
        },
        onFinish: () => { },
    });
};

const error = () => {
    // 
};

const closeModal = () => {
    modal.value = false;
    form.reset();
};

const format_date = (date) => {
    let parsedDate = moment(date);
    // let newDate = parsedDate.add(5, 'hours');
    let formattedDate = parsedDate.format('YYYY-MM-DD');
    return formattedDate;
}

// watch(
//     () => {
//         if (form.from_date) {
//             form.from_date = format_date(form.from_date)
//         }
//         if (form.to_date) {
//             form.to_date = format_date(form.to_date)
//         }
//     }
// );
</script>

<template>
    <PrimaryButton @click="create" type="button" class="mx-1">Search</PrimaryButton>

    <Modal :show="modal" @close="closeModal">
        <form @submit.prevent="edit ? update() : submit()">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Search Filters</h2>

                <p class="mt-1 text-sm text-gray-600">
                    <hr>
                </p>

                <div class="mt-6">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <InputLabel for="" value="Date Type" class="mb-1" />
                            <select v-model="form.date_type" class="form-control">
                                <option value="date_of_insurance">Insurance Date</option>
                                <option value="policy_start_period">Policy Start Date</option>
                                <option value="policy_end_period">Policy End Date</option>
                                <option value="created_at">Created Date</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <InputLabel for="" value="Month" class="mb-1" />
                            <select v-model="form.month" class="form-control">
                                <template v-for="month in months">
                                    <option :value="month.id">{{ month.name }}</option>
                                </template>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <InputLabel for="" value="Year" class="mb-1" />
                            <select v-model="form.year" class="form-control">
                                <template v-for="year in years">
                                    <option :value="year.value">{{ year.value }}</option>
                                </template>
                            </select>
                        </div>

                        <div class="col-md-6" v-if="slug == 'sales'">
                            <InputLabel for="" value="Policy Type" class="mb-1" />
                            <select v-model="form.policy_type" class="form-control">
                                <option value="">All Types</option>
                                <option value="new">New</option>
                                <option value="renewal">Renewal</option>
                                <option value="endorsment">Endorsment</option>
                                <option value="others">Others</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <InputLabel for="" value="Client" class="mb-1" />
                            <select v-model="form.client" class="form-control">
                                <option :value="''">All Clients</option>
                                <template v-for="client in data.clients">
                                    <option :value="client.id">{{ client.name }}</option>
                                </template>
                            </select>
                            <InputError :message="form.errors.client" />
                        </div>

                        <div class="col-md-6">
                            <InputLabel for="" value="Agency" class="mb-1" />
                            <select v-model="form.agency" class="form-control">
                                <option :value="''">All Agencies</option>
                                <template v-for="agency in data.agencies">
                                    <option :value="agency.id">{{ agency.name }}</option>
                                </template>
                            </select>
                            <InputError :message="form.errors.agency" />
                        </div>

                        <div class="col-md-6">
                            <InputLabel for="" value="Insurer" class="mb-1" />
                            <select v-model="form.insurer" class="form-control">
                                <option :value="''">All Insurers</option>
                                <template v-for="insurer in data.insurers">
                                    <option :value="insurer.id">{{ insurer.name }}</option>
                                </template>
                            </select>
                            <InputError :message="form.errors.insurer" />
                        </div>

                        <div class="col-md-6">
                            <InputLabel for="" value="Class of Business" class="mb-1" />
                            <select v-model="form.cob" class="form-control">
                                <option :value="''">All Classes</option>
                                <template v-for="cob in data.cobs">
                                    <option :value="cob.id">{{ cob.class_name }}</option>
                                </template>
                            </select>
                            <InputError :message="form.errors.cob" />
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <SuccessButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Search
                    </SuccessButton>
                </div>
            </div>
        </form>
    </Modal>
</template>
