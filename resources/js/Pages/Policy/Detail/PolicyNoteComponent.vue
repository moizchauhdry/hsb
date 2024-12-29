<script setup>
import { ref } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import Paginate from "@/Components/Paginate.vue";
import AdditionalNotes from "../Partial/AdditionalNotes.vue";

const { props } = usePage();
const permission = props.can;

defineProps({
    policy: Object,
    policyNotes: Array,
});
</script>
<template>
    <AdditionalNotes v-bind="$props" v-if="permission.policy_note"></AdditionalNotes>

    <div class="table-responsive mt-2" v-if="permission.policy_note">
        <table class="table table-bordered text-uppercase" v-if="policyNotes.length > 0">
            <tbody>
                <tr>
                    <th colspan="4" class="bg-warning text-white">
                        Policy Notes
                    </th>
                </tr>
                <tr>
                    <th>Sr No.</th>
                    <th>Note</th>
                </tr>
                <template v-for="policyNote, index in policyNotes" :key="policyNote.id">
                    <tr>
                        <td>{{ ++index }}</td>
                        <td>{{ policyNote.additional_notes }}</td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>