<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import axios from 'axios';
import Multiselect from "@vueform/multiselect";

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

    axios.get("/business-class/create")
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
    form.post(route("businessClass.store"), {
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

    axios.get(`/business-class/edit/${id}`)
        .then(({ data }) => {
            form.business_class_id = data.business_cls.id;
            form.class_name = data.business_cls.class_name;
            form.percentage = data.business_cls.percentage;
            form.department_id = data.business_cls.department_id;
            insurances.value = data.insurances;
            // form.insurance_id = data.business_cls.business_insurance.map(rel => rel.insurance_id);
            form.insurance_id = data.selected_insurers;
        });
};

defineExpose({ edit: (id) => edit(id) });

const update = () => {
    form.post(route("businessClass.update"), {
        preserveScroll: true,
        onSuccess: () => close(),
        onError: () => error(),
        onFinish: () => { },
    });
};

</script>

<template>
    <div class="col">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleLargeModal"
            @click="create"><i class="bx bx-plus"></i>Add</button>

        <div class="modal fade show" id="exampleLargeModal" tabindex="-1" aria-hidden="true" style="display: block;"
            v-if="modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form @submit.prevent="edit_mode ? update() : submit()">
                        <div class="modal-header">
                            <h5 class="modal-title">Business Class</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                @click="close"></button>
                        </div>
                        <div class="modal-body">
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


                                <!-- <select id="input21" class="form-select" multiple v-model="form.insurance_id">
                                        <template v-if="insurances.length > 0">
                                            <template v-for="insurance in insurances" :key="insurance.id">
                                                <option :value="insurance.id"
                                                    :selected="edit_mode && form.insurance_id.includes(insurance.id)">
                                                    {{ insurance.name }}
                                                </option>
                                            </template>
                                        </template>
                                        <template v-else>
                                            <p>No insurance options available.</p>
                                        </template>
                                    </select> -->


                                <div class="col-md-12">
                                    <label for="input21" class="form-label">Insurer</label>

                                    <Multiselect style="margin-top: 3px !important" mode="tags" :searchable="false"
                                        v-model="form.insurance_id" :options="insurances">
                                    </Multiselect>

                                    <InputError :message="form.errors.insurance_id" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                                @click="closeModal">Close</button> -->
                            <button type="submit" class="btn btn-primary btn-sm">
                                {{ edit_mode ? 'Update' : 'Save' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>