<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import { computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    closeable: {
        type: Boolean,
        default: true,
    },
});

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
            <li :class="{ 'mm-active': route().current('dashboard') }">
                <a href="#" class="has-arrow" @click="toggleList('dashboard')">
                    <div class="parent-icon"><i class='bx bx-home-alt'></i>
                    </div>
                    <div class="menu-title">Dashboard</div>
                </a>
                <ul :class="{ 'hidden': !isSubmenuVisible.dashboard }">
                    <li :class="{ 'mm-active': route().current('dashboard') }">
                        <Link :href="route('dashboard')"><i class='bx bx-radio-circle'></i>Analytics</Link>
                    </li>
                </ul>
            </li>

            <li class="menu-label">Application</li>
            <li :class="{ 'mm-active': route().current('user.index') || route().current('role.index') }">
                <a href="#" class="has-arrow" @click="toggleList('user')">
                    <div class="parent-icon"><i class="bx bx-category"></i>
                    </div>
                    <div class="menu-title">User Management</div>
                </a>
                <ul :class="{ 'hidden': !isSubmenuVisible.user }">
                    <li :class="{ 'mm-active': route().current('user.index') }">
                        <Link :href="route('user.index')"><i class='bx bx-radio-circle'></i>List Users</Link>
                    </li>
                    <li :class="{ 'mm-active': route().current('role.index') }">
                        <Link :href="route('role.index')"><i class='bx bx-radio-circle'></i>Role & Permissions</Link>
                    </li>
                </ul>
            </li>
            <li :class="{ 'mm-active': route().current('policy.index') || route().current('policy.detail') }">
                <a href="#" class="has-arrow" @click="toggleList('policy')">
                    <div class="parent-icon"><i class="bx bx-category"></i>
                    </div>
                    <div class="menu-title">Policies</div>
                </a>
                <ul :class="{ 'hidden': !isSubmenuVisible.policy }">
                    <li :class="{ 'mm-active': route().current('policy.index') }">
                        <Link :href="route('policy.index')"><i class='bx bx-radio-circle'></i>List</Link>
                    </li>
                </ul>
            </li>

            <li class="menu-label">Policy</li>
            <li :class="{ 'mm-active': route().current('insurance.index') }">
                <a href="#" class="has-arrow" @click="toggleList('insurance')">
                    <div class="parent-icon"><i class="bx bx-category"></i>
                    </div>
                    <div class="menu-title">Insurer</div>
                </a>
                <ul :class="{ 'hidden': !isSubmenuVisible.insurance }">
                    <li :class="{ 'mm-active': route().current('insurance.index') }">
                        <Link :href="route('insurance.index')"><i class='bx bx-radio-circle'></i>List</Link>
                    </li>
                </ul>
            </li>

            <li :class="{ 'mm-active': route().current('agency.index') }">
                <a href="#" class="has-arrow" @click="toggleList('agency')">
                    <div class="parent-icon"><i class="bx bx-category"></i>
                    </div>
                    <div class="menu-title">Agency</div>
                </a>
                <ul :class="{ 'hidden': !isSubmenuVisible.agency }">
                    <li :class="{ 'mm-active': route().current('agency.index') }">
                        <Link :href="route('agency.index')"><i class='bx bx-radio-circle'></i>List</Link>
                    </li>
                </ul>
            </li>

            <li :class="{ 'mm-active': route().current('businessClass.index') }">
                <a href="#" class="has-arrow" @click="toggleList('businessClass')">
                    <div class="parent-icon"><i class="bx bx-category"></i>
                    </div>
                    <div class="menu-title">Business Class</div>
                </a>
                <ul :class="{ 'hidden': !isSubmenuVisible.businessClass }">
                    <li :class="{ 'mm-active': route().current('businessClass.index') }">
                        <Link :href="route('businessClass.index')"><i class='bx bx-radio-circle'></i>List
                        </Link>
                    </li>
                </ul>
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