<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const { props } = usePage();


const policyClaimNotes = ref([]);
const notes_modal = ref(false);
const edit_mode = ref(false);
const form = useForm({
  policy_id: props.policy.id ?? "",
  policy_claim_id: "",
  note: "",
});


const submit = () => {
  form.post(route("policy.claimNote"), {
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
  notes_modal.value = false;
  form.reset();
};

const claimNote = (id) => {
  notes_modal.value = true;
  edit_mode.value = false;

  form.policy_id = props.policy.id ?? "";
  form.policy_claim_id = id;
  form.note = '';

  axios.get(`/policy/get/claim/note/${props.policy.id}`)
    .then(({ data }) => {
      console.log(data);
      policyClaimNotes.value = data.policyClaimNotes;
    });
};

defineExpose({ claimNote: (id) => claimNote(id) });
</script>
<template>
    <AuthenticatedLayout>
      <div class="col">
        <div class="modal fade show" id="notesLargeModal" tabindex="-1" aria-hidden="true" style="display: block;" v-if="notes_modal">
          <div class="modal fade show" id="notesLargeModal" tabindex="-1" aria-hidden="true" style="display: block;">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <form @submit.prevent="edit_mode ? update() : submit()">
                  <div class="modal-header">
                    <h5 class="modal-title">Claim Notes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="closeModal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" v-model="form.policy_id">
                    <input type="hidden" v-model="form.policy_claim_id">
                    <div class="row g-3">
                      <div class="col-md-12">
                        <label for="input13" class="form-label">Note</label>
                        <textarea class="form-control" id="detail" v-model="form.note"></textarea>
                        <InputError :message="form.errors.note" />
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
                              <th>Notes</th>
                            </tr>
                          </thead>
                          <tbody>
                            <template v-for="(policyClaimNote, index) in policyClaimNotes" :key="policyClaimNote.id">
                              <tr>
                                <td>{{ ++index }}</td>
                                <td>{{ policyClaimNote.id }}</td>
                                <td>{{ policyClaimNote.note }}</td>
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

