<script setup>
import PolicyTable from "../Partial/PolicyTable.vue";

defineProps({
    leads: Array,
});
</script>


<template>
    <PolicyTable :policies="leads" :detail="false"></PolicyTable>
</template>