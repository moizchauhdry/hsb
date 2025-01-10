<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, Head, usePage } from "@inertiajs/vue3";
import Chart from 'chart.js/auto';
import { onMounted } from "vue";

defineProps({
    data: Array,
});

const main_data = usePage().props.data;
const revenue_data = usePage().props.data.revenue_data;

const gross_premium_amount = usePage().props.data.gross_premium_amount;
const gross_premium_collected = usePage().props.data.gross_premium_collected;
const gross_premium_outstanding = usePage().props.data.gross_premium_outstanding;

onMounted(() => {

    const ctx1 = document.getElementById("chart-1").getContext('2d');
    var chart1 = new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ["Endorsements", "Renewals", "Policies"],
            datasets: [{
                backgroundColor: [
                    (() => {
                        let gradient = ctx1.createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, '#008cff');
                        gradient.addColorStop(1, '#8e54e9');
                        return gradient;
                    })(),
                    (() => {
                        let gradient = ctx1.createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, '#ee0979');
                        gradient.addColorStop(1, '#ff6a00');
                        return gradient;
                    })(),
                    (() => {
                        let gradient = ctx1.createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, '#42e695');
                        gradient.addColorStop(1, '#3bb2b8');
                        return gradient;
                    })()
                ],
                hoverBackgroundColor: [
                    (() => {
                        let gradient = ctx1.createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, '#008cff');
                        gradient.addColorStop(1, '#8e54e9');
                        return gradient;
                    })(),
                    (() => {
                        let gradient = ctx1.createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, '#ee0979');
                        gradient.addColorStop(1, '#ff6a00');
                        return gradient;
                    })(),
                    (() => {
                        let gradient = ctx1.createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, '#42e695');
                        gradient.addColorStop(1, '#3bb2b8');
                        return gradient;
                    })()
                ],
                data: [main_data.policies_count, main_data.renewals_count, main_data.endorsements_count],
                borderWidth: [4, 4, 4]
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: 110,
            plugins: {
                legend: {
                    display: false,
                }
            }
        }
    });

    const ctx3 = document.getElementById('chart-3');
    const chart3 = new Chart(ctx3, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Total Amount (PKR)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)', // Light background color
                borderColor: 'rgba(255, 0, 0, 1)', // Darker red line color
                data: revenue_data,
                fill: true, // Optional: Makes the area under the line filled
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Revenue Overview', // Add the title of the chart
                    font: {
                        size: 20,
                    },
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: '',
                    },
                },
                y: {
                    title: {
                        display: true,
                        text: 'Amount (PKR)',
                    },
                },
            },
        }
    });


    const ctx4 = document.getElementById('chart-4').getContext('2d');

    const gradient1 = ctx4.createLinearGradient(0, 0, 0, 400);
    gradient1.addColorStop(0, 'rgba(0, 0, 255, 1)'); // Darker blue at the top
    gradient1.addColorStop(1, 'rgba(0, 0, 255, 0.5)'); // Darker blue with more opacity at the bottom

    const gradient2 = ctx4.createLinearGradient(0, 0, 0, 400);
    gradient2.addColorStop(0, 'rgba(0, 128, 0, 1)'); // Darker green at the top
    gradient2.addColorStop(1, 'rgba(0, 128, 0, 0.5)'); // Darker green with more opacity at the bottom


    const gradient3 = ctx4.createLinearGradient(0, 0, 0, 400);
    gradient3.addColorStop(0, 'rgba(255, 0, 0, 1)'); // Darker red at the top
    gradient3.addColorStop(1, 'rgba(255, 0, 0, 0.5)'); // Darker red with more opacity at the bottom

    const chart4 = new Chart(ctx4, {
        type: 'bar',
        data: {
            datasets: [
                {
                    label: 'Gross Premium',
                    data: gross_premium_amount, // Array of values
                    backgroundColor: gradient1,
                    stack: 'Stack 0',
                },
                {
                    label: 'Gross Premium Collected',
                    data: gross_premium_collected, // Array of values
                    backgroundColor: gradient2,
                    stack: 'Stack 1',
                },
                {
                    label: 'Gross Premium Outstanding',
                    data: gross_premium_outstanding, // Array of values
                    backgroundColor: gradient3,
                    stack: 'Stack 2',
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Gross Premium Overview', // Main title of the graph
                    font: {
                        size: 20,
                    },
                },
                tooltip: {
                    enabled: true, // Enable tooltips
                },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    formatter: (value) => value, // Show the exact amount on each bar
                    color: '#000',
                    font: {
                        size: 12,
                        weight: 'bold',
                    },
                },
            },
            scales: {
                x: {
                    stacked: true,
                    title: {
                        display: true,
                        text: '',
                    },
                },
                y: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Amount (PKR)',
                    },
                },
            },
        },
        // plugins: [ChartDataLabels], // Include the Chart.js DataLabels plugin
    });

});


const format_number = (number) => {
    return new Intl.NumberFormat('en-US', {
        // minimumFractionDigits: 2,
        // maximumFractionDigits: 2
    }).format(number);
};

</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">

                <div class="row">
                    <div class="col-md-4">
                        <div class="col-md-12 d-flex">
                            <div class="card radius-10 overflow-hidden w-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div>
                                            <h6 class="mb-0">Policies</h6>
                                        </div>
                                    </div>
                                    <div class="chart-container-9">
                                        <div class="piechart-legend">
                                            <h2 class="mb-1">{{ format_number(data.policies_count + data.renewals_count)
                                                }}</h2>
                                            <h6 class="mb-0">Total Policies</h6>
                                        </div>
                                        <canvas id="chart-1"></canvas>
                                    </div>
                                </div>
                                <table class="table mb-0">
                                    <tbody>
                                        <tr>
                                            <td><i class='bx bxs-square-rounded me-2 text-success'></i>Policies</td>
                                            <td>
                                                <!-- <div>{{ data.policies_count }}</div> -->
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ format_number(data.policies_count) }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><i class='bx bxs-square-rounded me-2 text-danger'></i>Renewed</td>
                                            <td>
                                                <!-- <div>{{ data.renewals_count }}</div> -->
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ format_number(data.renewals_count) }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><i class='bx bxs-square-rounded me-2 text-primary'></i>Endorsements</td>
                                            <td>
                                                <!-- <div>{{ data.endorsements_count }}</div> -->
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ format_number(data.endorsements_count) }}</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-xxl-6 col-xl-12">
                                <div class="card radius-10">
                                    <Link :href="route('client.group.index')">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-secondary">Total Clients</p>
                                                <h4 class="my-1">Clients: {{ format_number(data.client_groups_count)
                                                    }}
                                                    <i class="bx bx-caret-right"></i> Subsidiaries: {{
                                                        format_number(data.clients_count) }}
                                                </h4>
                                            </div>
                                            <div class="widgets-icons bg-light-success text-success ms-auto"><i
                                                    class="bx bx-poll"></i>
                                            </div>
                                        </div>
                                    </div>
                                    </Link>
                                </div>
                            </div>
                            <div class="col-xxl-6 col-xl-8">
                                <div class="card radius-10">
                                    <Link :href="route('cob.index')">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-secondary">Classes of Business</p>
                                                <h4 class="my-1">Groups: {{ format_number(data.cob_groups_count) }}
                                                    <i class="bx bx-caret-right"></i> Classes: {{
                                                        format_number(data.cobs_count) }}
                                                </h4>
                                            </div>
                                            <div class="widgets-icons bg-light-danger text-danger ms-auto"><i
                                                    class="bx bx-layer"></i>
                                            </div>
                                        </div>
                                    </div>
                                    </Link>
                                </div>
                            </div>

                            <div class="col-xxl-4 col-xl-4">
                                <Link :href="route('claim.index')">
                                    <div class="card radius-10">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <p class="mb-0 text-secondary">Total Claims</p>
                                                    <h4 class="my-1">{{ format_number(data.policy_claim_count) }}</h4>
                                                </div>
                                                <div class="widgets-icons bg-light-info text-info ms-auto"><i
                                                        class='bx bx-list-ul'></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                            <div class="col-xxl-4 col-xl-6">
                                <div class="card radius-10">
                                    <Link :href="route('report.index','commission')">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <p class="mb-0 text-secondary">Revenue</p>
                                                    <h4 class="my-1">PKR {{ format_number(data.total_revenue) }}</h4>
                                                </div>
                                                <div class="widgets-icons bg-light-info text-info ms-auto"><i
                                                        class='bx bx-list-ul'></i>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-xl-6">
                                <div class="card radius-10">
                                    <Link :href="route('report.index','commission')">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <p class="mb-0 text-secondary">Net Commission Collected</p>
                                                    <h4 class="my-1">PKR {{
                                                        format_number(data.total_commission_collected)
                                                        }}
                                                    </h4>
                                                </div>
                                                <div class="widgets-icons bg-light-info text-info ms-auto"><i
                                                        class='bx bx-list-ul'></i>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-xl-6">
                                <div class="card radius-10">
                                    <Link :href="route('report.index','gross')">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <p class="mb-0 text-secondary">Gross Premium Outstanding</p>
                                                    <h4 class="my-1">PKR {{ format_number(data.gp_collected_outstanding)
                                                        }}
                                                    </h4>
                                                </div>
                                                <div class="widgets-icons bg-light-info text-info ms-auto"><i
                                                        class='bx bx-list-ul'></i>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-xl-6">
                                <div class="card radius-10">
                                    <Link :href="route('report.index','sales')">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <p class="mb-0 text-secondary">Sum Insured</p>
                                                    <h4 class="my-1">PKR {{ format_number(data.total_sum_insured) }}
                                                    </h4>
                                                </div>
                                                <div class="widgets-icons bg-light-info text-info ms-auto"><i
                                                        class='bx bx-list-ul'></i>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-6 col-xl-12">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div style="height: 400px;">
                                    <canvas id="chart-3"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-xl-12">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div style="height: 400px;">
                                    <canvas id="chart-4"></canvas>
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