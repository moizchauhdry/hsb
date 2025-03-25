<script setup>
import { ref } from "vue";
import moment from "moment";
import { FwbButton, FwbModal } from 'flowbite-vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
const props = defineProps({
    audits: {
        type: Array,
        required: true,
    },
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

const isShowModal = ref(false);
const selectedAudit = ref(null);

const showModal = (audit) => {
    selectedAudit.value = { ...audit };
    isShowModal.value = true;
};

const closeModal = () => {
    isShowModal.value = false;
};
</script>

<template>
    <div>
        <h2 class="mb-3">Policies Audit Logs</h2>

        <div v-if="!audits || audits.length === 0" class="text-gray-500 text-center py-4">
            No audit logs found.
        </div>

        <div v-else class="table-responsive">
            <table class="table table-bordered table-sm text-uppercase">
                <thead class="table-light">
                    <tr>
                        <th class="px-2">SR #</th>
                        <th class="px-2">Event</th>
                        <!-- <th class="px-2">Policy</th> -->
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
                        <!-- <td class="px-2">{{ audit.auditable_id }} - {{ audit.auditable_type }}</td> -->
                        <td class="px-2">{{ audit.user_id || "Unknown" }}</td>
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
                            <PrimaryButton color="blue" size="sm" @click="showModal(audit)">Details</PrimaryButton>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

        <!-- Modal -->
        <div class="modal fade" :class="{ 'show d-block': isShowModal }" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Audit Log Details</h5>
                        <button type="button" class="btn-close" @click="closeModal"></button>
                    </div>
                    <div class="modal-body" v-if="selectedAudit">
                        <p><strong>IP Address:</strong> {{ selectedAudit?.ip_address || "N/A" }}</p>
                        <p><strong>User Agent:</strong> {{ selectedAudit?.user_agent || "N/A" }}</p>
                        <p><strong>URL:</strong> {{ selectedAudit?.url }}</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
</template>

<style scoped>
.table-responsive {
    overflow-y: auto;
}
</style>
