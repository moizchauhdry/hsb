<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
defineProps({
    work_orders: Object,
});
</script>

<template>

    <Head title="Pending Work Orders" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pending Work Orders
            </h2>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="m-4 flex flex-wrap justify-between">
                        <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/3 xl:w-1/3 p-1"
                            v-for="(wo, index) in work_orders.data">
                            <a href="#"
                                class="block max-w-sm p-6 bg-white border border-gray-100 rounded-lg shadow-lg hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-100">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight ">
                                    WO #{{ wo.id }}</h5>
                                <p class="font-normal ">
                                    <b>Customer Name:</b> {{ wo.wo_customer_name }} <br>
                                    <b>Job Title:</b> {{ wo.wo_title }} <br>
                                    <b>Order Date:</b> {{ wo.created_at }} <br>
                                    <b>Return Date:</b> {{ wo.wo_return_date }} <br>
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </AuthenticatedLayout>

</template>