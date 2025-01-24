<script setup>
import PolicyTable from "../Partial/PolicyTable.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";

defineProps({
    leads: Array,
});

const { props } = usePage();
const permission = props.can;
</script>


<template>
    <PolicyTable :policies="leads" :detail="false"  v-if="permission.coinsurer"></PolicyTable>

    <div v-if="!permission.coinsurer">
        <h5 class="text-center">You do not have permission to view this tab.</h5>
    </div>
</template>