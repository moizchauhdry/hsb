<script>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";

export default {
    props: {
        policy: Object,
        brokerage_amount_list: Array,
        sum_brokerage_amount_received: Array,
    },
    data() {
        return {
            permission: null,
        };
    },
    created() {
        const { props } = usePage();
        this.permission = props.can;
    },
    methods: {
        format_number(number) {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(number);
        }
    }
}
</script>

<template>
    <div class="table-responsive" v-if="permission.policy_brokerage_amount">
        <table class="table table-bordered text-uppercase">
            <tbody>
                <tr>
                    <th colspan="2" class="bg-warning text-white">
                        Brokerage Amount
                    </th>
                </tr>
                <tr>
                    <th>Brokerage/Commissioned Amount</th>
                    <td>PKR {{ format_number(policy.brokerage_amount) }} </td>
                </tr>
                <tr>
                    <th>Brokerage Percentage </th>
                    <td>PKR {{ policy.brokerage_percentage }} % </td>
                </tr>
                <tr>
                    <th>Brokerage Received Amount</th>
                    <td>PKR {{ format_number(sum_brokerage_amount_received) }} </td>
                </tr>
                <tr>
                    <th>Brokerage Outstanding Balance</th>
                    <td>PKR {{ format_number(policy.brokerage_amount - sum_brokerage_amount_received) }} </td>
                </tr>
                <!-- <tr>
                    <th>Brokerage Paid Date</th>
                    <td>{{ policy.brokerage_paid_date }}</td>
                </tr> -->
                <!-- <tr>
                    <th>Brokerage Status</th>
                    <td>{{ policy.brokerage_status }}</td>
                </tr> -->
            </tbody>
        </table>

        <table class="table table-bordered text-uppercase">
            <tr>
                <th colspan="8" class="bg-warning text-white">
                    Brokerage Amount List
                </th>
            </tr>
            <tr>
                <th>SR.</th>
                <th>Policy No</th>
                <th>Net Premium </th>
                <th>Gross Premium </th>
                <th>Gross Premium Received </th>
                <th>Brokerage Amount Received </th>
                <th>Receipt Amount</th>
                <th>Receipt Date</th>
            </tr>

            <tbody>
                <template v-for="amount, index in brokerage_amount_list" :key="amount.id">

                    <tr>
                        <td> {{ ++index }}</td>
                        <td> {{ amount.policy_no }}</td>
                        <td> PKR {{ format_number(amount.net_premium) }} </td>
                        <td> PKR {{ format_number(amount.gross_premium) }} </td>
                        <td> PKR {{ format_number(amount.gross_premium_received) }} </td>
                        <td> PKR {{ format_number(amount.brokerage_amount_received) }} </td>
                        <td> PKR {{ format_number(amount.receipt_amount) }} </td>
                        <td> {{ amount.receipt_at }} </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>