<script setup>
import { ref } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import Paginate from "@/Components/Paginate.vue";
import Uploads from "../Partial/Uploads.vue";
import DangerButton from "@/Components/DangerButton.vue";

const { props } = usePage();
const permission = props.can;
const form = useForm({});

defineProps({
    policy: Object,
    policyUploads: Array,
    assetUrl: String,
});
const deleteUpload = (id) =>{
    form.delete(route("policy.uploads.destroy", id),{
        preserveScroll:true,
        onSuccess: () =>alert("File Deleted Successfull"),
        onError: (errors) => console.error(errors),
    })
}
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
                            <DangerButton @click="deleteUpload(policyUpload.id)">
                                <i class='bx bxs-trash-alt  mr-2'></i> DELETE
                            </DangerButton>
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

    <div v-else>
        <h5 class="text-center">You do not have permission to view this tab.</h5>
    </div>
</template>
