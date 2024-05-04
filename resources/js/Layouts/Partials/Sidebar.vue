<template>
    <div class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div>
                <img src="../../../images/logo-icon.png" class="logo-icon" alt="logo icon">
            </div>
            <div>
                <h4 class="logo-text">HSB Portal</h4>
            </div>
            <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
            </div>
        </div>
        <!--navigation-->
        <ul class="metismenu" id="menu">
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-home-alt'></i>
                    </div>
                    <div class="menu-title">Dashboard</div>
                </a>
                <ul>
                    <li> <a href="#"><i class='bx bx-radio-circle'></i>Analytics</a>
                    </li>
                    <li> <a href="#"><i class='bx bx-radio-circle'></i>Reports</a>
                    </li>
                </ul>
            </li>
            <li class="menu-label">Application</li>
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-category"></i>
                    </div>
                    <div class="menu-title">User Management</div>
                </a>
                <ul>
                    <li> 
                        <a href="#"><i class='bx bx-radio-circle'></i>List Users</a>
                    </li>
                    <li> 
                        <a href="#"><i class='bx bx-radio-circle'></i>Role & Permissions</a>
                    </li>
                </ul>
            </li>
                       
        </ul>
        <!--end navigation-->
    </div>
</template>