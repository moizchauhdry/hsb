<script setup>
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import Paginate from "@/Components/Paginate.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import moment from "moment";
import SuccessButton from "@/Components/SuccessButton.vue";

const modal = ref(false);
const selectedAudit = ref(null);
const props = usePage().props;
const audits = ref(props.audits?.data || []);

watch(() => props.audits, (newVal) => {
    audits.value = newVal?.data || [];
    console.log("Audits updated:", audits.value);
});

const getFormattedDate = (date) => moment(date).format("DD-MM-YYYY HH:mm");

const parsedOldValues = (audit) => {
    if (!audit || !audit.old_values) return {};
    try {
        return JSON.parse(audit.old_values);
    } catch (e) {
        console.error("Invalid JSON format in old_values:", audit.old_values);
        return {};
    }
};

const parsedNewValues = (audit) => {
    if (!audit || !audit.new_values) return {};
    try {
        return JSON.parse(audit.new_values);
    } catch (e) {
        console.error("Invalid JSON format in new_values:", audit.new_values);
        return {};
    }
};

const formatKey = (key) => key.replace(/_/g, " ").toUpperCase();
const formatValue = (value) => (typeof value === "object" ? JSON.stringify(value, null, 2) : value);

const showModal = (audit) => {
    selectedAudit.value = { ...audit };
    modal.value = true;
};

const closeModal = () => {
    modal.value = false;
};
</script>

<template>

    <Head title="Audit Logs" />
    <AuthenticatedLayout>
        <div class="page-wrapper">
            <div class="page-content">
                <div class="page-breadcrumb d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Manage Audit Logs</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active text-capitalize" aria-current="page">
                                    Audit Logs
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Audit Logs</h5>
                    </div>

                    <div class="card-body">
                        <div v-if="audits.length === 0" class="text-gray-500 text-center py-4">
                            No audit logs found.
                        </div>

                        <div v-else class="table-responsive">
                            <table class="table table-bordered table-sm text-uppercase">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-2">SR #</th>
                                        <th class="px-2">Event</th>
                                        <th class="px-2">Auditable Type</th>
                                        <th class="px-2">Auditable ID</th>
                                        <th class="px-2">User</th>
                                        <th class="px-2">Old Values</th>
                                        <th class="px-2">New Values</th>
                                        <th class="px-2">Date</th>
                                        <th class="px-2">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(audit, index) in audits" :key="audit.id">
                                        <td class="px-2">{{ index + 1 }}</td>
                                        <td class="px-2">{{ audit.event }}</td>
                                        <td class="px-2">{{ audit.auditable_type }}</td>
                                        <td class="px-2">{{ audit.auditable_id }}</td>
                                        <td class="px-2">{{ audit.user_id || "Unknown" }}</td>

                                        <!-- Old Values -->
                                        <td class="px-2">
                                            <div v-if="Object.keys(parsedOldValues(audit)).length">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr v-for="(value, key) in parsedOldValues(audit)" :key="key">
                                                            <th class="bg-light text-dark"
                                                                style="width: 200px; text-align: left; padding: 8px;">
                                                                {{ formatKey(key) }}
                                                            </th>
                                                            <td style="padding: 8px;">
                                                                {{ formatValue(value) }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>

                                        <!-- New Values (User Agent Removed) -->
                                        <td class="px-2">
                                            <div v-if="Object.keys(parsedNewValues(audit)).length">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr v-for="(value, key) in parsedNewValues(audit)" :key="key"
                                                            v-if="key !== 'user_agent'">
                                                            <th class="bg-light text-dark"
                                                                style="width: 200px; text-align: left; padding: 8px;">
                                                                {{ formatKey(key) }}
                                                            </th>
                                                            <td style="padding: 8px;">
                                                                {{ formatValue(value) }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>

                                        <td class="px-2">{{ getFormattedDate(audit.created_at) }}</td>
                                        <td>
                                            <PrimaryButton color="blue" size="sm" @click="showModal(audit)">Details
                                            </PrimaryButton>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <span>
                            <h6>Showing {{ props.audits.from }} to {{ props.audits.to }} of {{ props.audits.total }}
                                entries</h6>
                        </span>
                        <Paginate :links="props.audits.links" />
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" :class="{ 'show d-block': modal }" id="exampleLargeModal" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Audit Details</h5>
                        <button type="button" class="btn-close" @click="closeModal"></button>
                    </div>
                    <div class="modal-body" v-if="selectedAudit">
                        <p><strong>IP Address:</strong> {{ selectedAudit?.ip_address || "N/A" }}</p>
                        <p><strong>User Agent:</strong> {{ selectedAudit?.user_agent || "N/A" }}</p>
                        <p><strong>URL:</strong>{{ selectedAudit?.url }}</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
