<script setup>
import { ref } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import Paginate from "@/Components/Paginate.vue";
import Uploads from "./Uploads.vue";

const { props } = usePage();
const permission = props.can;

defineProps({
    policy: Object,
    policyUploads: Array,
    assetUrl: String,
});
</script>
<template>
    <Uploads v-bind="$props" v-if="permission.policy_upload"></Uploads>

    <div class="table-responsive mt-2" v-if="permission.policy_upload">
        <table class="table table-bordered text-uppercase" v-if="policyUploads.data.length > 0">
            <tbody>
                <tr>
                    <th colspan="5" class="bg-warning text-white">
                        Policy Uploads
                    </th>
                </tr>
                <tr>
                    <th>Sr No.</th>
                    <th>Type</th>
                    <th>File</th>
                    <th>Action</th>
                </tr>
                <template v-for="policyUpload, index in policyUploads.data" :key="policyUpload.id">
                    <tr>
                        <td>{{ ++index }}</td>
                        <td>{{ policyUpload.type }}</td>
                        <td>
                            <img :src="assetUrl + '/' + policyUpload.upload" alt="" style="width: 70px;">
                        </td>
                        <td>
                            <a :href="assetUrl + '/' + policyUpload.upload" download>
                                <PrimaryButton>
                                    <i class='bx bxs-down-arrow-square mr-1'></i> Download
                                </PrimaryButton>
                            </a>
                        </td>
                    </tr>
                </template>

                <tr>
                    <td>
                        <Paginate :links="policyUploads.links" :scroll="true" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>