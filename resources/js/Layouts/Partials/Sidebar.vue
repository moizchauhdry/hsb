<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    closeable: {
        type: Boolean,
        default: true,
    },
});

const permission = usePage().props.can;

const emit = defineEmits(['toggle']);

const toggle = () => {
    if (props.closeable) {
        emit('toggle');
    }
};

const isSubmenuVisible = ref({
    dashboard: false,
});

const toggleList = (menu) => {
    isSubmenuVisible.value[menu] = !isSubmenuVisible.value[menu];
};

watch(
    () => {
        if (route().current('dashboard')) {
            isSubmenuVisible.value.dashboard = true;
        }
        else if (route().current('user.index') || route().current('role.index')) {
            isSubmenuVisible.value.user = true;
        }
        else if (route().current('insurance.index')) {
            isSubmenuVisible.value.insurance = true;
        }
        else if (route().current('agency.index')) {
            isSubmenuVisible.value.agency = true;
        }
        else if (route().current('businessClass.index')) {
            isSubmenuVisible.value.businessClass = true;
        }
        else if (route().current('policy.index') || route().current('policy.detail')) {
            isSubmenuVisible.value.policy = true;
        }
        else if (route().current('report.index')) {
            isSubmenuVisible.value.report = true;
        }
    }
);
</script>

<template>
    <div class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div>
                <img src="../../../images/logo-icon.png" class="logo-icon" alt="logo icon">
            </div>
            <div>
                <h4 class="logo-text">HSB Portal</h4>
            </div>
            <div class="toggle-icon ms-auto" @click="toggle">
                <i class='bx bx-arrow-back'></i>
            </div>
        </div>
        <!--navigation-->
        <ul class="metismenu" id="menu">

            <template v-if="permission.analytics">
                <li :class="{ 'mm-active': route().current('dashboard') }">
                    <a href="#" class="has-arrow" @click="toggleList('dashboard')">
                        <div class="parent-icon"><i class='bx bx-home-alt'></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                    <ul :class="{ 'hidden': !isSubmenuVisible.dashboard }">
                        <li :class="{ 'mm-active': route().current('dashboard') }" v-if="permission.analytics">
                            <Link :href="route('dashboard')"><i class='bx bx-radio-circle'></i>Analytics</Link>
                        </li>
                    </ul>
                </li>
            </template>

            <li class="menu-label" v-if="permission.policy_list">Policies</li>
            <template v-if="permission.user_list || permission.client_list">
                <li :class="{ 'mm-active': route().current('user.index', 'users') || route().current('role.index') }">
                    <a href="#" class="has-arrow" @click="toggleList('user')">
                        <div class="parent-icon"><i class="bx bx-user"></i>
                        </div>
                        <div class="menu-title">Manage Users</div>
                    </a>
                    <ul :class="{ 'hidden': !isSubmenuVisible.user }">
                        <li :class="{ 'mm-active': route().current('user.index', 'users') }"
                            v-if="permission.user_list">
                            <Link :href="route('user.index', 'users')"><i class='bx bx-radio-circle'></i>List Users
                            </Link>
                        </li>
                        <li :class="{ 'mm-active': route().current('user.index', 'clients') }"
                            v-if="permission.client_list">
                            <Link :href="route('user.index', 'clients')"><i class='bx bx-radio-circle'></i>List Clients
                            </Link>
                        </li>
                    </ul>
                </li>
            </template>

            <template v-if="permission.policy_list">
                <li :class="{ 'mm-active': route().current('policy.index') || route().current('policy.detail') }">
                    <a href="#" class="has-arrow" @click="toggleList('policy')">
                        <div class="parent-icon"><i class="bx bx-poll"></i>
                        </div>
                        <div class="menu-title">Manage Policies</div>
                    </a>
                    <ul :class="{ 'hidden': !isSubmenuVisible.policy }">
                        <li :class="{ 'mm-active': route().current('policy.index') }" v-if="permission.policy_list">
                            <Link :href="route('policy.index')"><i class='bx bx-radio-circle'></i>List</Link>
                        </li>
                    </ul>
                </li>
            </template>

            <template v-if="permission.insurer_list">
                <li :class="{ 'mm-active': route().current('insurance.index') }">
                    <a href="#" class="has-arrow" @click="toggleList('insurance')">
                        <div class="parent-icon"><i class="bx bx-category"></i>
                        </div>
                        <div class="menu-title">Manage Insurers</div>
                    </a>
                    <ul :class="{ 'hidden': !isSubmenuVisible.insurance }">
                        <li :class="{ 'mm-active': route().current('insurance.index') }" v-if="permission.insurer_list">
                            <Link :href="route('insurance.index')"><i class='bx bx-radio-circle'></i>List</Link>
                        </li>
                    </ul>
                </li>
            </template>

            <template v-if="permission.agency_list">
                <li :class="{ 'mm-active': route().current('agency.index') }">
                    <a href="#" class="has-arrow" @click="toggleList('agency')">
                        <div class="parent-icon"><i class="bx bx-list-ul"></i>
                        </div>
                        <div class="menu-title">Manage Agencies</div>
                    </a>
                    <ul :class="{ 'hidden': !isSubmenuVisible.agency }">
                        <li :class="{ 'mm-active': route().current('agency.index') }" v-if="permission.agency_list">
                            <Link :href="route('agency.index')"><i class='bx bx-radio-circle'></i>List</Link>
                        </li>
                    </ul>
                </li>
            </template>

            <template v-if="permission.cob_list">
                <li :class="{ 'mm-active': route().current('businessClass.index') }">
                    <a href="#" class="has-arrow" @click="toggleList('businessClass')">
                        <div class="parent-icon"><i class="bx bx-layer"></i>
                        </div>
                        <div class="menu-title">Class of Business</div>
                    </a>
                    <ul :class="{ 'hidden': !isSubmenuVisible.businessClass }">
                        <li :class="{ 'mm-active': route().current('businessClass.index') }" v-if="permission.cob_list">
                            <Link :href="route('businessClass.index')"><i class='bx bx-radio-circle'></i>List
                            </Link>
                        </li>
                    </ul>
                </li>
            </template>

            <li class="menu-label">Reports</li>
            <li :class="{ 'mm-active': route().current('report.index') }">
                <a href="javascript:;" class="has-arrow" @click="toggleList('report')">
                    <div class="parent-icon"><i class="bx bx-line-chart"></i>
                    </div>
                    <div class="menu-title">Manage Reports</div>
                </a>
                <ul :class="{ 'hidden': !isSubmenuVisible.report }">
                    <li :class="{ 'mm-active': route().current('report.index', 'sales') }"
                        v-if="permission.sales_report">
                        <Link :href="route('report.index', 'sales')"><i class='bx bx-radio-circle'></i>Sales Report
                        </Link>
                    </li>
                    <li :class="{ 'mm-active': route().current('report.index', 'renewal') }"
                        v-if="permission.renewal_report">
                        <Link :href="route('report.index', 'renewal')"><i class='bx bx-radio-circle'></i>Renewal
                        Report</Link>
                    </li>
                    <li :class="{ 'mm-active': route().current('report.index', 'outstanding') }"
                        v-if="permission.outstanding_report">
                        <Link :href="route('report.index', 'outstanding')"><i class='bx bx-radio-circle'></i>Outstanding
                        Report
                        </Link>
                    </li>
                    <li :class="{ 'mm-active': route().current('report.index', 'commission-recovery') }"
                        v-if="permission.commission_recovery_report">
                        <Link :href="route('report.index', 'commission-recovery')"><i
                            class='bx bx-radio-circle'></i>Commission
                        Recovery Report</Link>
                    </li>
                    <li :class="{ 'mm-active': route().current('report.index', 'commission-outstanding-recovery') }"
                        v-if="permission.commission_outstanding_recovery">
                        <Link :href="route('report.index', 'commission-outstanding-recovery')"><i
                            class='bx bx-radio-circle'></i>Commission Outstanding Recovery</Link>
                    </li>

                    <!-- <li :class="{ 'mm-active': route().current('report.index','claims') }">
                            <Link :href="route('report.index','claims')"><i class='bx bx-radio-circle'></i>Claims Report</Link>
                        </li> -->
                    <!-- <li :class="{ 'mm-active': route().current('report.index','claims') }">
                            <Link :href="route('report.index','claims')"><i class='bx bx-radio-circle'></i>Commission Report</Link>
                        </li> -->
                </ul>
            </li>

            <li class="menu-label" v-if="permission.excel_import">Settings</li>
            <li :class="{ 'mm-active': route().current('role.index') }">
                <a :href="route('role.index')" v-if="permission.role_list">
                    <div class="parent-icon">
                        <i class="bx bx-category"></i>
                    </div>
                    <div class="menu-title">Roles & Permissions</div>
                </a>
            </li>
            <li :class="{ 'mm-active': route().current('error-logs.index') }">
                <a :href="route('error-logs.index')" v-if="permission.excel_import">
                    <div class="parent-icon">
                        <i class="bx bx-import"></i>
                    </div>
                    <div class="menu-title">Excel Import</div>
                </a>
            </li>
        </ul>
        <!--end navigation-->
    </div>
</template>

<style>
.sidebar-wrapper {
    overflow-y: hidden;
    transition: overflow-y 0.3s ease;
}

.sidebar-wrapper:hover {
    overflow-y: auto;
}

/* width */
::-webkit-scrollbar {
    width: 4px;
    height: 4px;
}

/* Track */
::-webkit-scrollbar-track {
    background: transparent
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
    /* Add radius */

}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: rgb(204, 201, 201);
}
</style>