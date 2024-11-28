<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

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
  <AuthenticatedLayout>
    <div class="col">
      <div class="modal fade show" id="notesUploadLargeModal" tabindex="-1" aria-hidden="true" style="display: block;"
        v-if="modal">
        <div class="modal fade show" id="notesUploadLargeModal" tabindex="-1" aria-hidden="true"
          style="display: block;">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <form @submit.prevent="edit_mode ? update() : submit()">
                <div class="modal-header">
                  <h5 class="modal-title">Claim Upload</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    @click="close"></button>
                </div>
                <div class="modal-body">

                  <div class="row g-3">

                    <div class="col-md-12">
                      <label for="input13" class="form-label">Uploads</label>
                      <input type="file" class="form-control" id="input13" @change="handleFileChange">
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                          <tr>
                            <th>Sr No.</th>
                            <th>Claim ID</th>
                            <th>File</th>
                          </tr>
                        </thead>
                        <tbody>
                          <template v-for="(upload, index) in claim_uploads" :key="upload.id">
                            <tr>
                              <td>{{ ++index }}</td>
                              <td>{{ upload.id }}</td>
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
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                    @click="close">Close</button>
                  <button type="submit" class="btn btn-primary btn-sm">
                    {{ edit_mode ? 'Save & Update' : 'Save & Submit' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
