<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from '@/Components/InputError.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import Multiselect from "@vueform/multiselect";
import DarkButton from "@/Components/DarkButton.vue";
import moment from 'moment';

const props = defineProps({
    filter_route: {
        type: String,
        required: true
    }
});

const modal = ref(false);
const edit = ref(false);
const slug = usePage().props.slug;

const policy_types = ref([]);
const fetchPolicyTypes = () => {
    axios.get(`/axios/fetch/policy-types`)
        .then(({ data }) => {
            policy_types.value = data.policy_types;
        });
};

const clients = ref([]);
const fetchClients = () => {
    axios.get(`/axios/fetch/clients/v2`)
        .then(({ data }) => {
            clients.value = data.clients;
        });
};

const agencies = ref([]);
const fetchAgencies = () => {
    axios.get(`/axios/fetch/agencies`)
        .then(({ data }) => {
            agencies.value = data.agencies;
        });
};


const insurers = ref([]);
const fetchInsurers = () => {
    axios.get(`/axios/fetch/insurers`)
        .then(({ data }) => {
            insurers.value = data.insurers;
        });
};

const departments = ref([]);
const fetchDepartments = () => {
    axios.get(`/axios/fetch/departments`)
        .then(({ data }) => {
            departments.value = data.departments;
        });
};

// const cobs = ref([]);
// const fetchCobs = (group_ids) => {
//     axios.post(`/axios/fetch/cobs/v2`, { group_ids: group_ids })
//         .then(({ data }) => {
//             cobs.value = data.cobs;
//         });
// };

const cobs = ref([]);
const fetchCobs = (department_ids) => {
    axios.post(`/axios/fetch/cobs/v2`, { department_ids: department_ids })
        .then(({ data }) => {
            cobs.value = data.cobs;
        });
};

var saved_filters = "";

const form = useForm({
    date_type: "",
    date_value: "",

    from_date: "",
    to_date: "",

    policy_type: [],
    client: [],
    agency: [],
    insurer: [],
    department: [],
    group: [],
    cob: [],
});

const create = () => {
    modal.value = true;
    edit.value = false;

    fetchPolicyTypes();
    fetchClients();
    fetchAgencies();
    fetchInsurers();
    fetchDepartments();

    saved_filters = localStorage.getItem('filters');
    if (saved_filters) {
        saved_filters = JSON.parse(saved_filters);

        form.date_type = saved_filters.date_type
        form.date_value = saved_filters.date_value

        form.from_date = saved_filters.from_date
        form.to_date = saved_filters.to_date

        form.policy_type = saved_filters.policy_type
        form.client = saved_filters.client
        form.agency = saved_filters.agency
        form.insurer = saved_filters.insurer
        form.cob = saved_filters.cob
        form.department = saved_filters.department
        form.group = saved_filters.group
    }
};

const submit = () => {
    var filters = {
        date_type: form.date_type,
        date_value: form.date_value,

        from_date: form.from_date,
        to_date: form.to_date,

        policy_type: form.policy_type,
        client: form.client,
        agency: form.agency,
        insurer: form.insurer,
        cob: form.cob,
        department: form.department,
        group: form.group,
    };

    const queryParams = new URLSearchParams(filters).toString();

    var urlWithFilters;

    if (props.filter_route === 'report') {
        urlWithFilters = `${route("report.index", slug)}?${queryParams}`;
    }

    if (props.filter_route === 'policy') {
        urlWithFilters = `${route("policy.index")}?${queryParams}`;
    }

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
    let formattedDate = parsedDate.format('YYYY-MM-DD');
    return formattedDate;
}

watch(() => form.department, (new_department) => {
    if (new_department && new_department.length > 0) {
        // fetchGroups(form.department);
        fetchCobs(form.department);
    }
});

// watch(() => form.group, (new_group) => {
//     if (new_group && new_group.length > 0) {
//         fetchCobs(form.group);
//     }
// });

watch(() => form.date_value, (newValue) => {
    if (form.date_value != null) {
        form.from_date = format_date(form.date_value[0]);
        form.to_date = format_date(form.date_value[1]);
    } else {
        form.from_date = "";
        form.to_date = "";
    }
});

</script>

<template>
    <DarkButton @click="create" type="button" class="mx-1"><i class="bx bx-search-alt text-lg mr-1"></i> Filters
    </DarkButton>

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
                                <template v-if="props.filter_route == 'policies' || props.filter_route == 'endorsement'">
                                    <option value="">All</option>
                                    <option value="date_of_issuance">Issuance Date</option>
                                    <option value="policy_period_start">Inception Date</option>
                                    <option value="policy_period_end">Expiry Date</option>
                                    <option value="created_at">Created At</option>
                                </template>
                                <template v-if="props.filter_route == 'renewals'">
                                    <option value="policy_period_end">Expiry Date</option>
                                </template>
                            </select>
                        </div>

                        <div class="col-md-6" v-if="form.date_type">
                            <InputLabel for="" :value="form.date_type" class="mb-1 uppercase" />
                            <VueDatePicker v-model="form.date_value" range :enable-time-picker="false"
                                :show-time="false"></VueDatePicker>
                        </div>

                        <hr style="margin-top: 30px">

                        <div class="col-md-6" v-if="props.filter_route == 'policies'">
                            <InputLabel for="" value="Policy Type" class="mb-1" />
                            <Multiselect v-model="form.policy_type" :options="policy_types" :searchable="true"
                                mode="tags">
                            </Multiselect>
                        </div>

                        <div class="col-md-12">
                            <InputLabel for="" value="Insurer" class="mb-1" />
                            <Multiselect v-model="form.insurer" :options="insurers" :searchable="true" mode="tags">
                            </Multiselect>
                        </div>

                        <div class="col-md-12">
                            <InputLabel for="" value="Agency" class="mb-1" />
                            <Multiselect v-model="form.agency" :options="agencies" :searchable="true" mode="tags">
                            </Multiselect>
                        </div>

                        <div class="col-md-12">
                            <InputLabel for="" value="Client" class="mb-1" />
                            <Multiselect v-model="form.client" :options="clients" :searchable="true" mode="tags">
                            </Multiselect>
                        </div>

                        <div class="col-md-12">
                            <InputLabel for="" value="Department" class="mb-1" />
                            <Multiselect v-model="form.department" :options="departments" :searchable="true"
                                mode="tags">
                            </Multiselect>
                        </div>

                        <!-- <div class="col-md-12">
                            <InputLabel for="" value="Group" class="mb-1" />
                            <Multiselect v-model="form.group" :options="groups" :searchable="true" mode="tags">
                            </Multiselect>
                        </div> -->

                        <div class="col-md-12">
                            <InputLabel for="" value="Class of Business" class="mb-1" />
                            <Multiselect v-model="form.cob" :options="cobs" :searchable="true" mode="tags">
                            </Multiselect>
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

<style src="@vueform/multiselect/themes/default.css"></style>