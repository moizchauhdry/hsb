<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const { props } = usePage();

const policyClaimUploads = ref([]);
const notes_upload_modal = ref(false);
const edit_mode = ref(false);
const handleFileChange = (event) => {
    // Access the selected files from the event
    const selectedFiles = event.target.files;
    // Update the data property with the selected files
    form.uploads = selectedFiles;
};
const form = useForm({
  policy_id: props.policy.id ?? "",
  policy_claim_id: '',
  uploads: null,
});

const create = () => {
  notes_upload_modal.value = true;
  edit_mode.value = false;
};

const submit = () => {
  form.post(route("policy.claimUpload"), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => error(),
    onFinish: () => { },
  });
};

const error = () => {
  // alert('error');
};

const closeModal = () => {
  notes_upload_modal.value = false;
  form.reset();
};

const claimUpload = (id) => {
  notes_upload_modal.value = true;
  edit_mode.value = false;

  form.policy_id = props.policy.id ?? "";
  form.policy_claim_id = id;
  form.uploads = '';

  axios.get(`/policy/get/claim/upload/${props.policy.id}`)
    .then(({ data }) => {
      console.log(data);
      policyClaimUploads.value = data.policyClaimUploads;
    });
};

defineExpose({ claimUpload: (id) => claimUpload(id) });



</script>
<template>
    <AuthenticatedLayout>
      <div class="col">
        <div class="modal fade show" id="notesUploadLargeModal" tabindex="-1" aria-hidden="true" style="display: block;" v-if="notes_upload_modal">
          <div class="modal fade show" id="notesUploadLargeModal" tabindex="-1" aria-hidden="true" style="display: block;">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <form @submit.prevent="edit_mode ? update() : submit()">
                  <div class="modal-header">
                    <h5 class="modal-title">Claim Upload</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="closeModal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" v-model="form.policy_id">
                    <input type="hidden" v-model="form.policy_claim_id">
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
                            <template v-for="(policyClaimUpload, index) in policyClaimUploads" :key="policyClaimUpload.id">
                              <tr>
                                <td>{{ ++index }}</td>
                                <td>{{ policyClaimUpload.id }}</td>
                                <td><img :src="props.assetUrl + '/' + policyClaimUpload.file_url" alt="" style="height: 100px;width: 100px;"></td>
                              </tr>
                            </template>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" @click="closeModal">Close</button>
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

