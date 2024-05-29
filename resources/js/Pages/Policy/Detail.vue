<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import Uploads from "./Uploads.vue";
import AdditionalNotes from "./AdditionalNotes.vue";
import Claim from "./Claim.vue";
import ClaimNote from "./Claim/Notes.vue";
import ClaimUpload from "./Claim/Upload.vue";
import ClaimEdit from "./Claim/Edit.vue";

const { props } = usePage();

const isOpen = ref(new Array(props.policyNotes.length).fill(false)); // Array to store isOpen states

defineProps({
  policy: Object,
  policyNotes: Object,
  policyClaims: Object,
  assetUrl: Object,
  // Add a new prop for single-open behavior
  desiredSingleOpenBehavior: {
    type: Boolean,
    default: false, // Set default to false (optional)
  },
});

const toggleAccordion = (index) => {
  // Toggle the isOpen state for the clicked item only
  isOpen.value[index] = !isOpen.value[index];

  // Optionally close other accordion items (if single-open behavior is desired)
  if (props.desiredSingleOpenBehavior) {
    isOpen.value.fill(false, 0, index); // Close previous items
    isOpen.value.fill(false, index + 1); // Close subsequent items
  }
};

const claim_note_ref = ref(null);
const claim_upload_ref = ref(null);
const claim_edit_ref = ref(null);

const claimNote = (id) => {
    claim_note_ref.value.claimNote(id)
};

const claimUpload = (id) => {
    claim_upload_ref.value.claimUpload(id)
};

const claimEdit = (id) => {
    claim_edit_ref.value.claimEdit(id)
};


</script>

<template>
    <Head title="Policies" />
    <AuthenticatedLayout>
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Policies </div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;">
                                        <i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Policies Detail</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-lg-flex align-items-center mb-4 gap-3">
                            <div class="ms-auto d-flex" style="width: 310px;">
                                <Uploads v-bind="$props"></Uploads>
                                <AdditionalNotes v-bind="$props"></AdditionalNotes>
                                <Claim v-bind="$props"></Claim>
                            </div>
                        </div>
                        <div class="table-responsive" style="overflow-x: hidden;">
                            <div class="container-fluid">
                                <div class="border mb-4" id="partner">
                                    <div style="background-color: #037DE2">
                                        <h5  style="text-align: center" class="w-auto title">Policy Info</h5>
                                    </div>
                
                                    <div class="row">
                                        <table class="table table-bordered" style="margin-left: 18px;">
                                            <tr>
                                                <th>ID</th>
                                                <td>{{ policy.id }}</td>
                                                <th>Client</th>
                                                <td>{{ policy.client_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Insurance</th>
                                                <td>{{ policy.insurance_id }}</td>

                                                <th>Co Insurance</th>
                                                <td>{{ policy.co_insurance }}</td>
                                            </tr>
                                            <tr>
                                                <th>Insurance type</th>
                                                <td v-if="policy.takeful_type == 0">Direct 100%</td>
                                                <td v-if="policy.takeful_type == 1">Our lead</td>
                                                <th>Policy No.</th>
                                                <td>{{ policy.policy_no }}</td>
                                            </tr>
                                            <tr>
                                                <th>Agency</th>
                                                <td>{{ policy.agency_id }}</td>
                                            
                                                <th>Agency code</th>
                                                <td>{{ policy.agency_code }}</td>
                                            </tr>
                                            <tr>
                                                <th>Class business</th>
                                                <td>{{ policy.class_of_business_id }}</td>
                                            
                                                <th>Orignal Endorsment</th>
                                                <td>{{ policy.orignal_endorsment }}</td>
                                            </tr>
                                            <tr>
                                                <th>Date of insurance</th>
                                                <td>{{ policy.date_of_insurance }}</td>
                                        
                                                <th>Policy start period</th>
                                                <td>{{ policy.policy_start_period }}</td>
                                            </tr>
                                            <tr>
                                                <th>Policy end period</th>
                                                <td>{{ policy.policy_end_period }}</td>

                                                <th> Cover Note No </th>
                                                <td> {{ policy.cover_note_no }} </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                           
                                <div class="border mb-4" id="partner">
                                    <div style="background-color: #037DE2">
                                        <h5  style="text-align: center" class="w-auto title">Policy Amount</h5>
                                    </div>
                                    <div class="row">
                                        <table class="table table-bordered" style="margin-left: 18px;">
                                            <tr>
                                                <th>Sum insured </th>
                                                <td> {{ policy.sum_insured }} </td>
                                        
                                                <th>Gross Premium </th>
                                                <td> {{ policy.gross_premium }} </td>
                                            </tr>
                                            <tr>
                                                <th>Net Premium </th>
                                                <td> {{ policy.net_premium }} </td>
                                              
                                                <th>User </th>
                                                <td> {{ policy.user_id }} </td>
                                       
                                            </tr>
                                            <tr>
                                                <th>Installment Plan </th>
                                                <td> {{ policy.installment_plan }} </td>
                                            
                                                
                                                <th>Brokerage Amount </th>
                                                <td> {{ policy.brokerage_amount }} </td>
                                            </tr>
                                            <tr>
                                                <th>Tax</th>
                                                <td> {{ policy.tax }} </td>
                                        
                                                <th>Percentage</th>
                                                <td> {{ policy.percentage }} </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="border mb-4" id="partner">
                                    <div style="background-color: #037DE2">
                                        <h5  style="text-align: center" class="w-auto title">Notes</h5>
                                    </div>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>
                                                
                                                        
                                                    <div class="accordion accordion-flush" id="accordionExample">
                                                        <template v-for="(policyNote, index) in policyNotes" :key="index">
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" :id="'heading-' + policyNote.id">
                                                            <button class="accordion-button" type="button" @click="toggleAccordion(index)" :aria-expanded="isOpen[index]" :aria-controls="'collapse-' + policyNote.id">
                                                                {{ policyNote.additional_notes }}
                                                            </button>
                                                            </h2>
                                                            <div :id="'collapse-' + policyNote.id" class="accordion-collapse" :class="{ 'show': isOpen[index] }" :aria-labelledby="'heading-' + policyNote.id" data-bs-parent="#accordionExample2">
                                                            <div class="accordion-body">
                                                                <p>{{ policyNote.additional_notes }}</p>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        </template>
                                                    </div>
                                                        
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
            
                                <div class="border mb-4" id="partner">
                                    <div style="background-color: #037DE2">
                                        <h5  style="text-align: center" class="w-auto title">Policy Claims</h5>
                                    </div>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <div class="ms-auto">
                                                <ClaimNote v-bind="$props" ref="claim_note_ref"></ClaimNote>
                                                <ClaimUpload v-bind="$props" ref="claim_upload_ref"></ClaimUpload>
                                                <ClaimEdit v-bind="$props" ref="claim_edit_ref"></ClaimEdit>
                                            </div>
                                            <table class="table mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Sr No.</th>
                                                        <th>ID</th>
                                                        <th>Progress</th>
                                                        <th>Settled</th>
                                                        <th>Detail</th>
                                                        <th>Status</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template v-for="(policyClaim, index) in JSON.parse(JSON.stringify(props.policyClaims))" :key="index">
                                                        <tr>
                                                            <td>{{ ++index }}</td>
                                                            <td>{{ policyClaim.id }}</td>
                                                            <td>{{ policyClaim.progress }}</td>
                                                            <td>{{ policyClaim.settled }}</td>
                                                            <td>{{ policyClaim.detail }}</td>
                                                            <td><span class="badge bg-primary">{{ policyClaim.status }}</span></td>
                                                            <td>
                                                                <div class="d-lg-flex align-items-center mb-4 gap-3">
                                                                    <div class="ms-auto d-flex">
                                                                        <button type="button" @click="claimNote(policyClaim.id)" title="Note"
                                                                            data-bs-toggle="modal" data-bs-target="#notesLargeModal"
                                                                            class="btn btn-primary btn-sm" style="margin-inline: 5px; font-size: 10px; width:40px;"> <i class='bx bxs-note'></i></button>

                                                                        <button type="button" @click="claimUpload(policyClaim.id)" title="Uploads"
                                                                            data-bs-toggle="modal" data-bs-target="#notesUploadLargeModal"
                                                                            class="btn btn-primary btn-sm" style="margin-inline: 5px; font-size: 10px; width:40px;">  <i class='bx bx-cloud-upload'></i></button>

                                                                        <button type="button" @click="claimEdit(policyClaim.id)" title="Edit"
                                                                            data-bs-toggle="modal" data-bs-target="#EditLargeModal"
                                                                            class="btn btn-primary btn-sm" style="font-size: 10px; width:40px;">  <i class='bx bx-edit'></i></button>

                                                                    </div>
                                                                    
                                                                </div>
                                                            </td>
                                                        </tr> 
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        
                                <div class="border mb-4" id="partner">
                                    <div style="background-color: #037DE2">
                                        <h5  style="text-align: center" class="w-auto title">Uploads</h5>
                                    </div>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Sr No.</th>
                                                        <th>Type</th>
                                                        <th>File</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template v-for="(policyUpload, index) in JSON.parse(JSON.stringify(props.policyUploads))" :key="index">
                                                        <tr>
                                                            <td>{{ ++index }}</td>
                                                            <td>{{ policyUpload.type }}</td>
                                                            <td><img :src="props.assetUrl + '/' + policyUpload.upload" alt="" style="height: 100px;width: 100px;"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>
</template>

<style> table, th, td { padding: 3px !important; font-size: 14px !important; } #lc-table td { border: 1px solid rgb(194, 189, 189) !important; text-align: left !important; } #lc-table th { border: 1px solid rgb(194, 189, 189) !important; text-align: left !important; } table { width: 100%; border-collapse: collapse; } fieldset legend { color: black; font-weight: 500; font-size: 18px; } fieldset.border { border: 2px solid #037DE2 !important; border-radius: 5px !important; }
    fieldset.member-border { border: 2px solid blue !important; border-radius: 5px !important;} .mb-4 { margin-bottom: 20px } .custom-image-preview { width: 100px; height: 100px } .table-bordered>:not(caption)>* {
    border-width: 0;
} h5.w-auto.title {
    text-align: center;
    color: #fff;
    height: 34px;
    padding: 5px;
    font-size: 18px;
} </style>