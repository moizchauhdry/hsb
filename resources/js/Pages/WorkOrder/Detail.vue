<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { onMounted } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import Multiselect from "@vueform/multiselect";
import SuccessButton from "@/Components/SuccessButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { EditorContent, useEditor } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'

const props = defineProps({
    edit_mode: Boolean,
    work_order: Object,
});

const work_order = usePage().props.work_order;
const edit_mode = usePage().props.edit_mode;

var wo_item_categories = [
    { value: 1, label: "Service and Labor" },
    { value: 2, label: "Parts and Materials" },
];

// var wo_bike_categories = [
//     { value: "Electric Bike", label: "Electric Bike" },
//     { value: "Electric Scooter", label: "Electric Scooter" },
// ];

var wo_statuses = [
    { value: 1, label: "Pending" },
    { value: 2, label: "Pickup" },
];

const form = useForm({
    wo_id: work_order?.id,
    wo_title: work_order?.wo_title,
    wo_customer_name: work_order?.wo_customer_name,
    wo_bike_category: work_order?.wo_bike_category,
    wo_bike_model: work_order?.wo_bike_model,
    wo_bike_color: work_order?.wo_bike_color,
    wo_sr_no: work_order?.wo_sr_no,
    wo_po_no: work_order?.wo_po_no,
    wo_bike_warranty: work_order?.wo_bike_warranty,
    wo_return_date: work_order?.wo_return_date,
    wo_status: work_order?.wo_status,
    wo_notes: work_order?.wo_notes,

    wo_addr_customer_name: work_order?.billing_address?.wo_addr_customer_name,
    wo_addr_str_address: work_order?.billing_address?.wo_addr_str_address,
    wo_addr_city: work_order?.billing_address?.wo_addr_city,
    wo_addr_state: work_order?.billing_address?.wo_addr_state,
    wo_addr_zipcode: work_order?.billing_address?.wo_addr_zipcode,
    wo_addr_phone: work_order?.billing_address?.wo_addr_phone,
    wo_addr_email: work_order?.billing_address?.wo_addr_email,

    wo_items: work_order?.items,

    wo_subtotal: 0,
    wo_discount: work_order?.wo_discount,
    wo_tax_rate: 5,
    wo_tax_total: 0,
    wo_grand_total: 0,
});

const submit = () => {
    form.post(route("work-order.update"));
};

const addItem = () => {
    form.wo_items.push({
        wo_item_category_id: "",
        wo_item_name: "",
        wo_item_hours: "",
        wo_item_rate: "",
    });
};

const removeItem = (index) => {
    form.wo_items.splice(index, 1);
    getGrandTotal();
};

const getGrandTotal = () => {
    form.wo_subtotal = 0;

    if (!form.wo_discount) {
        form.wo_discount = 0;
    }

    form.wo_items.forEach((item) => {
        form.wo_subtotal += parseFloat(item.wo_item_line_total);
    });

    form.wo_tax_total =
        parseFloat(form.wo_subtotal - form.wo_discount) *
        (form.wo_tax_rate / 100);

    console.log(form.wo_discount);

    form.wo_grand_total =
        form.wo_subtotal - form.wo_discount + form.wo_tax_total;
};

const getLineTotal = (index) => {
    const item = form.wo_items[index];
    const lineTotal = item.wo_item_hours * item.wo_item_rate;
    item.wo_item_line_total = lineTotal.toFixed(2);

    getGrandTotal();
};

const editor = useEditor({
    onUpdate: ({ editor }) => {
        form.wo_notes = editor.getHTML();
    },
    editorProps: {
        attributes: {
            class: "border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm p-2 min-h-[12rem]"
        },
    },
    content: form.wo_notes,
    extensions: [
        StarterKit,
    ],
})

onMounted(() => {
    if (!edit_mode) {
        form.wo_items = [
            {
                wo_item_category_id: "",
                wo_item_name: "",
                wo_item_hours: "",
                wo_item_rate: "",
            },
        ];
    }

    if (edit_mode) {
        getGrandTotal();
    }
});
</script>

<style src="@vueform/multiselect/themes/default.css"></style>

<style>
input[type="number"]:disabled {
    opacity: 0.5;
    background-color: #eee;
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    display: none;
    -webkit-appearance: none;
    margin: 0;
}

input:disabled {
    cursor: not-allowed;
}
</style>

<template>

    <Head :title="edit_mode ? 'Edit Work Order' : 'Create Work Order'" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ edit_mode ? "Edit" : "Create" }} Work Order
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit()" class="mt-6 space-y-3">

                            <!-- WORK ORDER - CUSTOMER INFORMATION -->
                            <h3 class="text-2xl font-bold text-center"> Work Order - Customer Information</h3>
                            <hr class="w-48 h-1 mx-auto bg-gray-100 border-0 rounded dark:bg-gray-700" />

                            <div class="grid gap-4 lg:grid-cols-4 md:grid-cols-2 grid-rows-1">
                                <div>
                                    <InputLabel for="wo_addr_customer_name" value="Customer Name" />
                                    <TextInput id="wo_addr_customer_name" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_addr_customer_name" />
                                    <InputError class="mt-2" :message="form.errors.wo_addr_customer_name" />
                                </div>

                                <div>
                                    <InputLabel for="wo_addr_str_address" value="Street Address" />
                                    <TextInput id="wo_addr_str_address" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_addr_str_address" />
                                    <InputError class="mt-2" :message="form.errors.wo_addr_str_address" />
                                </div>

                                <div>
                                    <InputLabel for="city" value="City" />
                                    <TextInput id="wo_addr_city" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_addr_city" />
                                    <InputError class="mt-2" :message="form.errors.wo_addr_city" />
                                </div>

                                <div>
                                    <InputLabel for="state" value="State" />
                                    <TextInput id="wo_addr_state" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_addr_state" />
                                    <InputError class="mt-2" :message="form.errors.wo_addr_state" />
                                </div>

                                <div>
                                    <InputLabel for="zipcode" value="Zipcode" />
                                    <TextInput id="wo_addr_zipcode" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_addr_zipcode" />
                                    <InputError class="mt-2" :message="form.errors.wo_addr_zipcode" />
                                </div>

                                <div>
                                    <InputLabel for="phone" value="Phone" />
                                    <TextInput id="phone" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_addr_phone" />
                                    <InputError class="mt-2" :message="form.errors.wo_addr_phone" />
                                </div>

                                <div>
                                    <InputLabel for="email" value="Email" />
                                    <TextInput id="email" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_addr_email" />
                                    <InputError class="mt-2" :message="form.errors.wo_addr_email" />
                                </div>
                            </div>

                            <!-- WORK ORDER - ITEM INFORMATION -->
                            <h4 class="text-2xl font-bold text-center">Work Order - Item Information</h4>
                            <hr class="w-48 h-1 mx-auto bg-gray-100 border-0 rounded dark:bg-gray-700" />

                            <div class="grid gap-4 lg:grid-cols-3 md:grid-cols-2 grid-rows-1">
                                <div>
                                    <InputLabel for="wo_title" value="Job Title" />
                                    <TextInput id="wo_title" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_title" />
                                    <InputError class="mt-2" :message="form.errors.wo_title" />
                                </div>

                                <!-- <div>
                                    <InputLabel for="wo_customer_name" value="Customer Name" />
                                    <TextInput id="wo_customer_name" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_customer_name" />
                                    <InputError class="mt-2" :message="form.errors.wo_customer_name" />
                                </div> -->

                                <div>
                                    <InputLabel for="wo_bike_category" value="Category" />
                                    <TextInput id="wo_bike_category" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_bike_category" />
                                    <!-- <Multiselect style="margin-top: 3px !important" v-model="form.wo_bike_category"
                                        :options="wo_bike_categories" /> -->
                                    <InputError class="mt-2" :message="form.errors.wo_bike_category" />
                                </div>

                                <div>
                                    <InputLabel for="wo_bike_model" value="Model" />
                                    <TextInput id="wo_bike_model" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_bike_model" />
                                    <InputError class="mt-2" :message="form.errors.wo_bike_model" />
                                </div>

                                <div>
                                    <InputLabel for="wo_bike_color" value="Color" />
                                    <TextInput id="wo_bike_color" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_bike_color" />
                                    <InputError class="mt-2" :message="form.errors.wo_bike_color" />
                                </div>

                                <div>
                                    <InputLabel for="wo_sr_no" value="Serial Number" />
                                    <TextInput id="wo_sr_no" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_sr_no" />
                                    <InputError class="mt-2" :message="form.errors.wo_sr_no" />
                                </div>

                                <div>
                                    <InputLabel for="wo_po_no" value="PO Number" />
                                    <TextInput id="wo_po_no" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_po_no" />
                                    <InputError class="mt-2" :message="form.errors.wo_po_no" />
                                </div>

                                <div>
                                    <InputLabel for="wo_bike_warranty" value="Warranty" />
                                    <TextInput id="wo_bike_warranty" type="text" class="mt-1 block w-full"
                                        v-model="form.wo_bike_warranty" />
                                    <InputError class="mt-2" :message="form.errors.wo_bike_warranty" />
                                </div>

                                <div>
                                    <InputLabel for="wo_return_date" value="Return Date" class="mb-1" />
                                    <VueDatePicker v-model="form.wo_return_date" :teleport="true"></VueDatePicker>
                                    <InputError class="mt-2" :message="form.errors.wo_return_date" />
                                </div>

                                <div>
                                    <InputLabel for="wo_status" value="Status" />
                                    <Multiselect style="margin-top: 3px !important" v-model="form.wo_status"
                                        :options="wo_statuses" />
                                    <InputError class="mt-2" :message="form.errors.wo_status" />
                                </div>

                                <div>
                                    <InputLabel for="wo_notes" value="Notes" />
                                    <editor-content :editor="editor" />
                                    <InputError class="mt-2" :message="form.errors.wo_notes" />
                                </div>
                            </div>

                            <!-- WORK ORDER - INVOICING -->
                            <h4 class="text-2xl font-bold text-center">Work Order - Invoicing</h4>
                            <hr class="w-48 h-1 mx-auto bg-gray-100 border-0 rounded dark:bg-gray-700" />
                            <div class="grid gap-4 lg:grid-cols-1 md:grid-cols-1 grid-rows-1">
                                <div>
                                    <SuccessButton :disabled="form.processing" @click="addItem()" type="button"
                                        class="float-right">
                                        Add Item
                                    </SuccessButton>
                                </div>
                            </div>

                            <template v-for="(item, index) in form.wo_items" :key="item.id">
                                <div class="grid gap-1 lg:grid-cols-8 md:grid-cols-8 grid-rows-1">
                                    <div class="col-span-2">
                                        <InputLabel :value="'Item Category #' + (index + 1)" />
                                        <Multiselect style="margin-top: 3px !important"
                                            v-model="item.wo_item_category_id" :options="wo_item_categories" />
                                    </div>

                                    <div class="col-span-3">
                                        <InputLabel for="item_name" value="Item Name" />
                                        <TextInput id="wo_item_name" type="text" class="mt-1 block w-full"
                                            v-model="item.wo_item_name" />
                                    </div>

                                    <div class="col-span-1">
                                        <InputLabel for="wo_item_hours"
                                            :value="item.wo_item_category_id == 1 ? 'Hours' : 'Quantity'" />
                                        <TextInput id="wo_item_hours" type="number" class="mt-1 block w-full"
                                            v-model="item.wo_item_hours" @keyup="getLineTotal(index)" />
                                    </div>

                                    <div class="col-span-1">
                                        <InputLabel for="wo_item_rate" value="Rate" />
                                        <TextInput id="wo_item_rate" type="number" class="mt-1 block w-full"
                                            v-model="item.wo_item_rate" @keyup="getLineTotal(index)" />
                                    </div>

                                    <div class="col-span-1">
                                        <InputLabel for="line_total" value="Line Total" />
                                        <TextInput disabled id="line_total" type="number" class="mt-1 block w-full"
                                            v-model="item.wo_item_line_total" />
                                    </div>
                                </div>

                                <div class="grid gap-4 lg:grid-cols-1 md:grid-cols-1 grid-rows-1"
                                    v-if="index === form.wo_items.length - 1 && index != 0">
                                    <div>
                                        <DangerButton :disabled="form.processing" type="button"
                                            class="float-right text-xs" @click="removeItem(index)">
                                            Remove
                                        </DangerButton>
                                    </div>
                                </div>
                            </template>

                            <div class="grid gap-4 lg:grid-cols-1 md:grid-cols-1 grid-rows-1">
                                <div class="justify-self-end">
                                    <div class="border p-4" style="width: 250px">
                                        <div class="flex justify-between mb-4">
                                            <div class="font-bold">
                                                SUBTOTAL
                                            </div>
                                            <div class="font-bold">
                                                ${{ form.wo_subtotal.toFixed(2) }}
                                            </div>
                                        </div>
                                        <div class="flex justify-between mb-4">
                                            <div class="font-bold">
                                                DISCOUNT
                                            </div>
                                            <div class="font-bold">
                                                $
                                                <TextInput type="number" class="ml-1 w-20" v-model="form.wo_discount"
                                                    @keyup="getGrandTotal()" />
                                            </div>
                                        </div>
                                        <div class="flex justify-between mb-4">
                                            <div>TAX RATE</div>
                                            <div>{{ form.wo_tax_rate }}%</div>
                                        </div>
                                        <div class="flex justify-between mb-4">
                                            <div>TAX TOTAL</div>
                                            <div>
                                                ${{ form.wo_tax_total.toFixed(2) }}
                                            </div>
                                        </div>
                                        <div class="flex justify-between mb-4 bg-blue-100 p-2">
                                            <div class="font-bold">
                                                GRAND TOTAL
                                            </div>
                                            <div class="font-bold">
                                                ${{ form.wo_grand_total.toFixed(2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <PrimaryButton :disabled="form.processing">
                                    {{ edit_mode ? "Update" : "Save" }}
                                </PrimaryButton>

                                <Link :href="route('work-order.index')">
                                <DangerButton :disabled="form.processing">
                                    Cancel
                                </DangerButton>
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
