<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import Swal from 'sweetalert2';
import DangerButton from "@/Components/DangerButton.vue";

// const { props } = usePage();

const modal = ref(false);
const edit_mode = ref(false);
const claim_uploads = ref([]);
const asset_url = usePage().props.asset_url;

const handleFileChange = (event) => {
  const selected_file = event.target.files[0];
  form.file = selected_file;
};

const form = useForm({
  policy_id: "",
  policy_claim_id: "",
  file: "",
});

const submit = () => {
  form.post(route("claim.store.claim-upload"), {
    preserveScroll: true,
    onSuccess: (response) => {
      var data = response.props.flash.data;
      fetchClaimUploads(data.policy_claim_id, data.policy_id);
      form.uploads = ""
    },
    onError: () => { },
    onFinish: () => { },
  });
};

const close = () => {
  modal.value = false;
  form.reset();
};

const claimUpload = (id, policy_id) => {
  modal.value = true;
  edit_mode.value = false;

  form.policy_claim_id = id;
  form.policy_id = policy_id;

  fetchClaimUploads(id, policy_id)
};

const fetchClaimUploads = (id, policy_id) => {
  axios.get(`/claims/fetch/claim-uploads/${id}/${policy_id}`)
    .then(({ data }) => {
      console.log(data);
      claim_uploads.value = data.claim_uploads;
    });
};

const deleteClaimUpload = (uploadId) => {
    Swal.fire({
        title: "Are you sure?",
        text: "This file will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("claim.uploads.destroy", uploadId), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire("Deleted!", "File deleted successfully.", "success");
                    fetchClaimUploads(form.policy_claim_id, form.policy_id);
                },
                onError: (errors) => {
                    console.error(errors);
                    Swal.fire("Error!", "Could not delete the file.", "error");
                },
                onFinish: () => form.reset(),
            });
        }
    });
};

defineExpose({ claimUpload: (id, policy_id) => claimUpload(id, policy_id) });

</script>

<template>
  <Modal :show="modal" @close="close">
    <form @submit.prevent="edit_mode ? update() : submit()">
      <div class="p-6">

        <h2 class="text-lg font-medium text-gray-900 flex justify-between items-center">
          <span>{{ edit_mode ? 'Edit' : 'Add' }} Upload</span>
          <span class="text-sm text-gray-500">
            <span v-if="form.policy_claim_id" class="mr-2">CLM #{{ form.policy_claim_id }}</span>
            <span>POL #{{ form.policy_id }}</span>
          </span>
        </h2>

        <p class="mt-1 text-sm text-gray-600">
          <hr>
        </p>

        <div class="mt-6">

          <div class="row g-3">
            <div class="col-md-12">
              <label for="input13" class="form-label">Uploads</label>
              <input type="file" class="form-control" id="input13" @change="handleFileChange">
            </div>
          </div>

          <div class="card mt-3" style="height: 300px;overflow-y: auto;">
            <div class="card-body">
              <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                    <tr>
                      <th>Sr #</th>
                      <th>Upload #</th>
                      <th>Policy/Claim ID</th>
                      <th>File</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="(upload, index) in claim_uploads" :key="upload.id">
                      <tr>
                        <td>{{ ++index }}</td>
                        <td>{{ upload.id }}</td>
                        <td>{{ upload.policy_id }}/{{ upload.policy_claim_id }}</td>
                        <td>
                          <img :src="'/storage/' + upload.file_url" alt="" style="height: 100px;width: 100px;">
                        </td>
                        <td>
                            <DangerButton @click="deleteClaimUpload(upload.id)">
                                <i class='bx bxs-trash-alt  mr-2'></i> DELETE
                            </DangerButton>
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>

        <div class="mt-6 flex justify-end">
          <SecondaryButton @click="close">Close</SecondaryButton>
          <SuccessButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            {{ edit_mode ? 'Save & Update' : 'Save & Submit' }}</SuccessButton>
        </div>
      </div>
    </form>
  </Modal>
</template>
