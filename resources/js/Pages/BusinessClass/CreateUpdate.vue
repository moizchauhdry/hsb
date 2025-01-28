<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from 'axios';
import Multiselect from "@vueform/multiselect";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import InputError from "@/Components/InputError.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps({
    businessClass: Object,
});

const modal = ref(false);
const edit_mode = ref(false);
const businessClass = usePage().props.businessClass;

const insurances = ref([]);
const departments = ref([]);

const create = () => {
    modal.value = true;
    edit_mode.value = false;

    axios.get("/cobs/create")
        .then(({ data }) => {
            insurances.value = data.insurances;
            departments.value = data.departments;
        });
};

const form = useForm({
    business_class_id: "",
    class_name: "",
    percentage: "",
    insurance_id: [],
    department_id: "",
});

const submit = () => {
    form.post(route("cob.store"), {
        preserveScroll: true,
        onSuccess: () => close(),
        onError: () => error(),
        onFinish: () => { },
    });
};


const error = () => {
    // alert('error');
};

const close = () => {
    modal.value = false;
    form.reset();
};

const validateInput = (event) => {
    const regex = /^[0-9]*$/;
    const input = event.target.value;

    if (!regex.test(input)) {
        event.preventDefault();
    } else {
        form.percentage = input;
    }
};

const edit = (id) => {
    modal.value = true;
    edit_mode.value = true;

    axios.get(`/cobs/edit/${id}`)
        .then(({ data }) => {
            form.business_class_id = data.cob.id;
            form.class_name = data.cob.class_name;
            form.percentage = data.cob.percentage;
            form.department_id = data.cob.department_id;
            insurances.value = data.insurances;
            form.insurance_id = data.selected_insurers;
            departments.value = data.departments;
        });
};

defineExpose({ edit: (id) => edit(id) });

const update = () => {
    form.post(route("cob.update"), {
        preserveScroll: true,
        onSuccess: () => close(),
        onError: () => error(),
        onFinish: () => { },
    });
};

</script>

<template>
    <div class="col">
        <PrimaryButton @click="create"><i class="bx bx-plus text-lg"></i>Add Class</PrimaryButton>

        <Modal :show="modal" @close="close">
            <form @submit.prevent="edit_mode ? update() : submit()">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 flex justify-between items-center">
                        <span>{{ edit_mode ? 'Edit' : 'Add' }} Client</span>
                        <span class="text-sm text-gray-500">
                            <span v-if="form.user_id" class="mr-2">ID #{{ form.user_id }}</span>
                        </span>
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        <hr>
                    </p>

                    <div class="mt-6">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="input21" class="form-label">Department</label>

                                <select id="input21" class="form-select" v-model="form.department_id">
                                    <template v-for="department in departments" :key="department.id">
                                        <option :value="department.id">{{ department.name }}
                                        </option>
                                    </template>
                                </select>
                                <InputError :message="form.errors.department_id" />
                            </div>
                            <div class="col-md-6">
                                <label for="input13" class="form-label">Class Name</label>
                                <input type="text" class="form-control" id="input13" placeholder=""
                                    v-model="form.class_name">

                                <InputError :message="form.errors.class_name" />
                            </div>
                            <div class="col-md-2">
                                <label for="input13" class="form-label">Percentage</label>
                                <input type="number" class="form-control" id="input13" placeholder=""
                                    v-model="form.percentage" @input="validateInput">

                                <InputError :message="form.errors.percentage" />
                            </div>

                            <div class="col-md-12">
                                <label for="input21" class="form-label">Insurer</label>

                                <Multiselect style="margin-top: 3px !important" mode="tags" :searchable="false"
                                    v-model="form.insurance_id" :options="insurances">
                                </Multiselect>

                                <InputError :message="form.errors.insurance_id" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="close"> Cancel </SecondaryButton>

                        <SuccessButton class="ml-3" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Save
                        </SuccessButton>
                    </div>
                </div>
            </form>
        </Modal>



    </div>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>