<script setup>
import PolicyList from "../Partial/PolicyTable.vue";

defineProps({
    renewals: Array,
});
</script>


<template>
    <PolicyList :policies="renewals" :detail="false"></PolicyList>
</template>