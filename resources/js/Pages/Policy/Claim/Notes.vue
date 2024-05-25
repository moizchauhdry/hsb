<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const { props } = usePage();


const notes_modal = ref(false);
const edit_mode = ref(false);
const form = useForm({
  policy_id: props.policy.id ?? "",
  status: "", // Note: I'm unsure what `status` should be here.
  note: "",
});

const create = () => {
  notes_modal.value = true;
  edit_mode.value = false;
};

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
</script>
<template>
    <AuthenticatedLayout>
      <div class="col">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#notesLargeModal" @click="create"
          style="margin-inline: 5px; font-size: 10px; width:40px;">
          <i class='bx bxs-note'></i>
        </button>
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
                    <div class="row g-3">
                      <div class="col-md-12">
                        <label for="input13" class="form-label">Note</label>
                        <textarea class="form-control" id="detail" v-model="form.note"></textarea>
                        <InputError :message="form.errors.note" />
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

