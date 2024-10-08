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
    clients: Array
});

const modal = ref(false);
const edit = ref(false);
const clients = usePage().props.clients;

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
    client: "",
    month: "",
    year: "",
});

const create = () => {
    modal.value = true;
    edit.value = false;
    saved_filters = localStorage.getItem('filters');
    if (saved_filters) {
        saved_filters = JSON.parse(saved_filters);

        if (form.client == "") {
            form.client = saved_filters.client
        }

        form.month = saved_filters.month
        form.year = saved_filters.year
    }
};

const submit = () => {
    var filters = {
        client: form.client,
        month: form.month,
        year: form.year,
    };

    const queryParams = new URLSearchParams(filters).toString();
    const urlWithFilters = `${route("report.sale.index")}?${queryParams}`;

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
                <h2 class="text-lg font-medium text-gray-900">Search Ledger</h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once your account is added, you can simply search by account
                    number, and the address will be fetched accordingly in invoice form.
                </p>

                <div class="mt-6">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <InputLabel for="" value="Client" class="mb-1" />
                            <select v-model="form.client" class="form-control">
                                <option :value="''">All</option>
                                <template v-for="client in clients">
                                    <option :value="client.id">{{ client.name }}</option>
                                </template>
                            </select>
                            <InputError :message="form.errors.client" />
                        </div>

                        <div class="col-md-4">
                            <InputLabel for="" value="Month" class="mb-1" />
                            <select v-model="form.month" class="form-control">
                                <template v-for="month in months">
                                    <option :value="month.id">{{ month.name }}</option>
                                </template>
                            </select>
                            <InputError :message="form.errors.client" />
                        </div>

                        <div class="col-md-2">
                            <InputLabel for="" value="Year" class="mb-1" />
                            <select v-model="form.year" class="form-control">
                                <template v-for="year in years">
                                    <option :value="year.value">{{ year.value }}</option>
                                </template>
                            </select>
                            <InputError :message="form.errors.client" />
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
