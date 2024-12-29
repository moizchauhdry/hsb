<script setup>
import PolicyList from "../Partial/PolicyTable.vue";

defineProps({
    endorsements: Array,
});
</script>


<template>
    <PolicyList :policies="endorsements" :detail="false"></PolicyList>
</template>