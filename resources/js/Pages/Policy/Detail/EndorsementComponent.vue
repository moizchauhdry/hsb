<script setup>
import PolicyList from "../Partial/PolicyTable.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";

defineProps({
    endorsements: Array,
});

const { props } = usePage();
const permission = props.can;
</script>


<template>
    <PolicyList :policies="endorsements" :detail="false" v-if="permission.endorsements"></PolicyList>

    <div v-if="!permission.endorsements">
        <h5 class="text-center">You do not have permission to view this tab.</h5>
    </div>
</template>