<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
defineProps({
    data: Array,
});
</script>

<template>

    <Head title="Dashboard" />
    
    <AuthenticatedLayout>
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xxl-4">
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Revenue</p>
                                        <h4 class="my-1">$4805</h4>
                                        <p class="mb-0 font-13 text-success"><i
                                                class="bx bxs-up-arrow align-middle"></i>$34
                                            from last week</p>
                                    </div>
                                    <div class="widgets-icons bg-light-success text-success ms-auto"><i
                                            class='bx bx-dollar'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Total Customers</p>
                                        <h4 class="my-1">58K</h4>
                                        <p class="mb-0 font-13 text-danger"><i
                                                class="bx bxs-down-arrow align-middle"></i>2.6% from last week</p>
                                    </div>
                                    <div class="widgets-icons bg-light-info text-info ms-auto"><i
                                            class="bx bxs-group"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Store Visitors</p>
                                        <h4 class="my-1">8.6K</h4>
                                        <p class="mb-0 font-13 text-success"><i
                                                class="bx bxs-up-arrow align-middle"></i>3.2% from last week</p>
                                    </div>
                                    <div class="widgets-icons bg-light-danger text-danger ms-auto"><i
                                            class="bx bxs-binoculars"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Bounce Rate</p>
                                        <h4 class="my-1">24.6%</h4>
                                        <p class="mb-0 font-13 text-danger"><i
                                                class="bx bxs-down-arrow align-middle"></i>12.2% from last week</p>
                                    </div>
                                    <div class="widgets-icons bg-light-warning text-warning ms-auto"><i
                                            class="bx bx-line-chart-down"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->

                <div class="row">
                    <div class="col-12 col-lg-8 d-flex">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-0">Sales Overview</h6>
                                    </div>
                                    <div class="dropdown ms-auto">
                                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#"
                                            data-bs-toggle="dropdown"><i
                                                class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Action</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="chart-container-9">
                                    <canvas id="chart1"></canvas>
                                </div>
                            </div>
                            <div
                                class="row row-cols-1 row-cols-md-3 row-cols-xl-3 g-0 row-group text-center border-top">
                                <div class="col">
                                    <div class="p-3">
                                        <h5 class="mb-0">24.15M</h5>
                                        <small class="mb-0">Overall Visitor <span> <i
                                                    class="bx bx-up-arrow-alt align-middle"></i> 2.43%</span></small>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-3">
                                        <h5 class="mb-0">12:38</h5>
                                        <small class="mb-0">Visitor Duration <span> <i
                                                    class="bx bx-up-arrow-alt align-middle"></i> 12.65%</span></small>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-3">
                                        <h5 class="mb-0">639.82</h5>
                                        <small class="mb-0">Pages/Visit <span> <i
                                                    class="bx bx-up-arrow-alt align-middle"></i> 5.62%</span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 d-flex">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-0">Trending Products</h6>
                                    </div>
                                    <div class="dropdown ms-auto">
                                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#"
                                            data-bs-toggle="dropdown"><i
                                                class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Action</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="chart-container-13 mt-3">
                                    <canvas id="chart2"></canvas>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li
                                    class="list-group-item d-flex bg-transparent justify-content-between align-items-center border-top">
                                    T-Shirts <span class="badge bg-danger rounded-pill">10</span>
                                </li>
                                <li
                                    class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                                    Shoes <span class="badge bg-primary rounded-pill">65</span>
                                </li>
                                <li
                                    class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                                    Lingerie <span class="badge bg-warning text-dark rounded-pill">14</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div><!--end row-->


                <div class="row">
                    <div class="col-12 col-lg-4 d-flex">
                        <div class="card radius-10 overflow-hidden w-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-0">Last 30 Days</h6>
                                    </div>
                                    <div class="dropdown ms-auto">
                                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#"
                                            data-bs-toggle="dropdown"><i
                                                class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Action</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="chart-container-4">
                                    <canvas id="chart3"></canvas>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item border-top">
                                    <div class="d-flex align-items-center justify-content-between p-2">
                                        <div class="">
                                            <h4 class="mb-0">58.6K</h4>
                                            <p class="mb-0">Page Views</p>
                                        </div>
                                        <div class="">
                                            <div id="chart4"></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center justify-content-between p-2">
                                        <div class="">
                                            <p class="mb-0">Total Clicks</p>
                                            <h4 class="mb-0">48.2K</h4>
                                        </div>
                                        <div class="">
                                            <div id="chart5"></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center justify-content-between p-2">
                                        <div class="">
                                            <p class="mb-0">Total Comments</p>
                                            <h4 class="mb-0">8,456</h4>
                                        </div>
                                        <div class="">
                                            <div id="chart6"></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center justify-content-between p-2">
                                        <div class="">
                                            <p class="mb-0">Total Returns</p>
                                            <h4 class="mb-0">298</h4>
                                        </div>
                                        <div class="">
                                            <div id="chart7"></div>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 d-flex">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12 col-lg-6">
                                        <div class="card radius-10 shadow-none border mb-0">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="w_chart chart8 mb-1" data-percent="78">
                                                        <span class="w_percent"></span>
                                                    </div>
                                                    <p class="mb-0 text-secondary">New Visits</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="card radius-10 shadow-none border mb-0">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="w_chart chart9 mb-1" data-percent="65">
                                                        <span class="w_percent"></span>
                                                    </div>
                                                    <p class="mb-0 text-secondary">Bounce Rate</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="card radius-10 shadow-none border mb-0">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="w_chart chart10 mb-1" data-percent="75">
                                                        <span class="w_percent"></span>
                                                    </div>
                                                    <p class="mb-0 text-secondary">Server Load</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="card radius-10 shadow-none border mb-0">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="w_chart chart11 mb-1" data-percent="55">
                                                        <span class="w_percent"></span>
                                                    </div>
                                                    <p class="mb-0 text-secondary">Used RAM</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="card radius-10 shadow-none border mb-0">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="w_chart chart12 mb-1" data-percent="68">
                                                        <span class="w_percent"></span>
                                                    </div>
                                                    <p class="mb-0 text-secondary">Web Traffic</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="card radius-10 shadow-none border mb-0">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="w_chart chart13 mb-1" data-percent="85">
                                                        <span class="w_percent"></span>
                                                    </div>
                                                    <p class="mb-0 text-secondary">Page Views</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 d-flex">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-0">Realtime Visitors</h6>
                                    </div>
                                    <div class="dropdown ms-auto">
                                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#"
                                            data-bs-toggle="dropdown"><i
                                                class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;">Action</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div id="geographic-map-2"></div>
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Country</th>
                                                <th>Visits</th>
                                                <th>Clicks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><i class="flag-icon flag-icon-us me-2"></i>United States</td>
                                                <td>8795</td>
                                                <td>145</td>
                                            </tr>
                                            <tr>
                                                <td><i class="flag-icon flag-icon-ca me-2"></i>Japan</td>
                                                <td>9856</td>
                                                <td>356</td>
                                            </tr>
                                            <tr>
                                                <td><i class="flag-icon flag-icon-au me-2"></i>Australia</td>
                                                <td>5748</td>
                                                <td>524</td>
                                            </tr>
                                            <tr>
                                                <td><i class="flag-icon flag-icon-in me-2"></i>India</td>
                                                <td>8547</td>
                                                <td>127</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!--end row-->


                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">Recent Orders</h6>
                            </div>
                            <div class="dropdown ms-auto">
                                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i
                                        class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Photo</th>
                                        <th>Product ID</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Shipping</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Iphone 5</td>
                                        <td><img src="../../../images/products/01.png" class="product-img-2"
                                                alt="product img">
                                        </td>
                                        <td>#9405822</td>
                                        <td><span class="badge bg-light-success text-success w-100">Paid</span></td>
                                        <td>$1250.00</td>
                                        <td>03 Feb 2020</td>
                                        <td>
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: 100%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Earphone GL</td>
                                        <td><img src="../../../images/products/02.png" class="product-img-2"
                                                alt="product img">
                                        </td>
                                        <td>#8304620</td>
                                        <td><span class="badge bg-light-warning text-warning w-100">Pending</span></td>
                                        <td>$1500.00</td>
                                        <td>05 Feb 2020</td>
                                        <td>
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                    style="width: 60%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>HD Hand Camera</td>
                                        <td><img src="../../../images/products/03.png" class="product-img-2"
                                                alt="product img">
                                        </td>
                                        <td>#4736890</td>
                                        <td><span class="badge bg-light-danger text-danger w-100">Failed</span></td>
                                        <td>$1400.00</td>
                                        <td>06 Feb 2020</td>
                                        <td>
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar bg-danger" role="progressbar"
                                                    style="width: 70%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Clasic Shoes</td>
                                        <td><img src="../../../images/products/04.png" class="product-img-2"
                                                alt="product img">
                                        </td>
                                        <td>#8543765</td>
                                        <td><span class="badge bg-light-success text-success w-100">Paid</span></td>
                                        <td>$1200.00</td>
                                        <td>14 Feb 2020</td>
                                        <td>
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: 100%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sitting Chair</td>
                                        <td><img src="../../../images/products/06.png" class="product-img-2"
                                                alt="product img">
                                        </td>
                                        <td>#9629240</td>
                                        <td><span class="badge bg-light-warning text-warning w-100">Pending</span></td>
                                        <td>$1500.00</td>
                                        <td>18 Feb 2020</td>
                                        <td>
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                    style="width: 60%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Hand Watch</td>
                                        <td><img src="../../../images/products/05.png" class="product-img-2"
                                                alt="product img">
                                        </td>
                                        <td>#8506790</td>
                                        <td><span class="badge bg-light-danger text-danger w-100">Failed</span></td>
                                        <td>$1800.00</td>
                                        <td>21 Feb 2020</td>
                                        <td>
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar bg-danger" role="progressbar"
                                                    style="width: 40%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end page wrapper -->
    </AuthenticatedLayout>

</template>