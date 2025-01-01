<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import moment from 'moment';

// const { props } = usePage();

const modal = ref(false);
const edit_mode = ref(false);
const claim_notes = ref([]);

const form = useForm({
  policy_id: "",
  policy_claim_id: "",
  note: "",
});

const submit = () => {
  form.post(route("claim.store.claim-note"), {
    preserveScroll: true,
    onSuccess: (response) => {
      var data = response.props.flash.data;
      fetchClaimNotes(data.policy_claim_id, data.policy_id);
      form.note = ""
    },
    onError: () => { },
    onFinish: () => { },
  });
};

const close = () => {
  modal.value = false;
};

const claimNote = (id, policy_id) => {
  modal.value = true;
  edit_mode.value = false;

  form.policy_id = policy_id;
  form.policy_claim_id = id;
  form.note = "";

  fetchClaimNotes(id, policy_id);
};

const fetchClaimNotes = (id, policy_id) => {
  axios.get(`/claims/fetch/claim-notes/${id}/${policy_id}`)
    .then(({ data }) => {
      claim_notes.value = data.claim_notes;
    });
};

const getDateFormat = (date) => {
    let parsedDate = moment(date);
    let formattedDate = parsedDate.format('DD-MM-YYYY');
    return formattedDate;
}

defineExpose({ claimNote: (id, policy_id) => claimNote(id, policy_id) });
</script>

<template>
  <Modal :show="modal" @close="close">
    <form @submit.prevent="edit_mode ? update() : submit()">
      <div class="p-6">

        <h2 class="text-lg font-medium text-gray-900 flex justify-between items-center">
          <span>{{ edit_mode ? 'Edit' : 'Add' }} Note</span>
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
              <label for="input13" class="form-label">Description</label>
              <textarea class="form-control" id="detail" v-model="form.note" rows="5"></textarea>
              <InputError :message="form.errors.note" />
            </div>
          </div>

          <div class="card mt-3" style="height: 300px;overflow-y: auto;">
            <div class="card-body">
              <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                    <tr>
                      <th>Note #</th>
                      <th>Claim No</th>
                      <th>Description</th>
                      <th>Created At</th>
                      <th>Created By</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="(claim_note, index) in claim_notes" :key="claim_note.id">
                      <tr>
                        <td>{{ claim_note.id }}</td>
                        <td>{{ claim_note.claim_no }}</td>
                        <td>{{ claim_note.note }}</td>
                        <td>{{ getDateFormat(claim_note.created_at) }}</td>
                        <td>{{ claim_note.user_name }}</td>
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
