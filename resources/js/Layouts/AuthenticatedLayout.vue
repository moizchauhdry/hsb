<script setup>
import { ref } from "vue";
import Sidebar from "@/Layouts/Partials/Sidebar.vue";
import Header from "@/Layouts/Partials/Header.vue";

const toggleSidebar = () => {
    const wrapper = document.querySelector('.wrapper');
    if (wrapper) {
        wrapper.classList.toggle('toggled');
    }
};

</script>

<template>
    <!--wrapper-->
    <div class="wrapper">

        <Sidebar @toggle="toggleSidebar"></Sidebar>
        <Header @toggle="toggleSidebar"></Header>

        <!-- Page Content -->
        <main>
            <slot />
        </main>

        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <!-- <footer class="page-footer">
            <p class="mb-0">Copyright © 2024. All right reserved.</p>
        </footer> -->
    </div>
    <!--end wrapper-->
</template>
