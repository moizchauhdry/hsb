<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import moment from 'moment';

const props = defineProps({
    data: Array,
    filter_route: {
        type: String,
        required: true
    }
});

const modal = ref(false);
const edit = ref(false);
const slug = usePage().props.slug;
const filter = usePage().props.filter;
console.log(usePage().props)

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
});

const create = () => {
    modal.value = true;
    edit.value = false;

    form.month = filter.month;
    form.year = filter.year;
    form.date_type = "claim_at";

    saved_filters = localStorage.getItem('filters');

    if (saved_filters) {
        saved_filters = JSON.parse(saved_filters);
        form.date_type = saved_filters.date_type
        form.month = saved_filters.month
        form.year = saved_filters.year
    }
};

const submit = () => {
    var filters = {
        date_type: form.date_type,
        month: form.month,
        year: form.year,
    };

    const queryParams = new URLSearchParams(filters).toString();

    var url_with_filters;
    url_with_filters = `${route("claim.index", slug)}?${queryParams}`;

    form.post(url_with_filters, {
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

const closeModal = () => {
    modal.value = false;
    form.reset();
};

const format_date = (date) => {
    let parsedDate = moment(date);
    let formattedDate = parsedDate.format('YYYY-MM-DD');
    return formattedDate;
}
</script>

<template>
    <PrimaryButton @click="create" type="button" class="mx-1">Search Filters</PrimaryButton>

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
                                <option value="claim_at">Claim Date</option>
                                <option value="intimation_at">Intimation Date</option>
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
