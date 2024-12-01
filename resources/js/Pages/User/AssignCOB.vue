<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import axios from 'axios';
import Modal from "@/Components/Modal.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps({
    roles: Object,
    slug: Object,
});

const modal = ref(false);
const edit_mode = ref(false);
const items = ref([]);
const selectedItems = ref(new Set());
const currentPage = ref(1);
const lastPage = ref(1);
const pageSelections = ref({});
const searchQuery = ref('');

const form = useForm({
    user_id: "",
    cob_id: [],
});


const submit = () => {
    form.cob_id = Array.from(selectedItems.value);

    form.post(route("user.assign-cob"), {
        preserveScroll: true,
        onSuccess: () => {
            modal.value = false;
        },
        onError: () => { },
        onFinish: () => { },
    });
};

const edit = (id) => {
    modal.value = true;
    edit_mode.value = true;
    form.user_id = id;
    form.cob_id = [];
    selectedItems.value = new Set();
    fetchSavedSelectedItems(id);
};

const fetchSavedSelectedItems = async (id) => {
    try {
        const response = await axios.get(`/users/selected-cob/${id}`);
        const savedItems = response.data.items;
        console.log(savedItems, 'saved');
        savedItems.forEach(item => selectedItems.value.add(item));
        fetchItems(); // Fetch items for pagination
    } catch (error) {
        console.error('Error fetching saved items:', error);
    }
};

const close = () => {
    modal.value = false;
    form.reset();
};

const fetchItems = async (page = 1) => {
    try {
        const response = await axios.get(`/axios/fetch/cobs`, {
            params: {
                page: page,
                search: searchQuery.value,
            },
        });
        items.value = response.data.data;
        currentPage.value = response.data.current_page;
        lastPage.value = response.data.last_page;
        pageSelections.value[page] = pageSelections.value[page] || false;
    } catch (error) {
        console.error("Error fetching items:", error);
    }
};

const toggleSelectAll = () => {
    const page = currentPage.value;
    const isSelected = !pageSelections.value[page];
    pageSelections.value[page] = isSelected;

    if (isSelected) {
        items.value.forEach((item) => selectedItems.value.add(item.id));
    } else {
        items.value.forEach((item) => selectedItems.value.delete(item.id));
    }
};

const isItemSelected = (id) => selectedItems.value.has(id);

const toggleItemSelection = (id) => {
    if (selectedItems.value.has(id)) {
        selectedItems.value.delete(id);
    } else {
        selectedItems.value.add(id);
    }
};


defineExpose({ assignCob: (id) => edit(id) });

</script>

<template>
    <Modal :show="modal" @close="close">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 flex justify-between items-center">
                <span>Assign Class of Business</span>
                <span class="text-sm text-gray-500">
                    <span v-if="form.user_id" class="mr-2">ID #{{ form.user_id }}</span>
                </span>
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                <hr>
            </p>

            <div class="mt-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" v-model="searchQuery" class="form-control mr-2 mb-2"
                                    placeholder="Search" style="width: 100%;" @keyup="fetchItems()">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-hover">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th>
                                            <input type="checkbox" :checked="pageSelections[currentPage]"
                                                @change="toggleSelectAll" /> ID
                                        </th>
                                        <th>Name</th>
                                        <th>Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in items" :key="item.id">
                                        <td>
                                            <input type="checkbox" :value="item.id" :checked="isItemSelected(item.id)"
                                                @change="toggleItemSelection(item.id)" /> {{ item.id }}
                                        </td>
                                        <td>{{ item.class_name }}</td>
                                        <td>{{ item.code }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="card-footer" v-if="cobs.data && cobs.data.length">
                        <div v-if="cobs">
                            <ul class="pagination">
                                <li v-for="link in cobs.links" :key="link.label" :class="{ active: link.active }">
                                    <button :disabled="!link.url" @click="handlePageChange(link)"
                                        v-html="link.label"></button>
                                </li>
                            </ul>
                        </div>
                    </div> -->
                    <div class="card-footer">
                        <div class="">
                            <button class="btn btn-primary btn-sm mr-1 pb-2" :disabled="currentPage === 1"
                                @click="fetchItems(currentPage - 1)">
                                <i class="bx bx-arrow-to-left"></i>
                            </button>
                            <button class="btn btn-primary btn-sm mr-1 pb-2" :disabled="currentPage === lastPage"
                                @click="fetchItems(currentPage + 1)">
                                <i class="bx bx-arrow-to-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="close"> Cancel </SecondaryButton>

                <SuccessButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    @click="submit()">
                    Save
                </SuccessButton>
            </div>
        </div>
    </Modal>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
