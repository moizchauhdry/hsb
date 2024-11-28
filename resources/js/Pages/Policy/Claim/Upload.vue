<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";

// const { props } = usePage();

const modal = ref(false);
const edit_mode = ref(false);
const claim_uploads = ref([]);
const asset_url = usePage().props.asset_url;

const handleFileChange = (event) => {
  const selectedFiles = event.target.files;
  form.uploads = selectedFiles;
};

const form = useForm({
  policy_id: "",
  policy_claim_id: "",
  uploads: "",
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

defineExpose({ claimUpload: (id, policy_id) => claimUpload(id, policy_id) });

</script>

<template>
  <Modal :show="modal" @close="close">
    <form @submit.prevent="edit_mode ? update() : submit()">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">{{ edit_mode ? 'Edit' : 'Add' }} Upload</h2>

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
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="(upload, index) in claim_uploads" :key="upload.id">
                      <tr>
                        <td>{{ ++index }}</td>
                        <td>{{ upload.id }}</td>
                        <td>{{ upload.policy_id }}/{{ upload.policy_claim_id }}</td>
                        <td>
                          <img :src="asset_url + '/' + upload.file_url" alt="" style="height: 100px;width: 100px;">
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
