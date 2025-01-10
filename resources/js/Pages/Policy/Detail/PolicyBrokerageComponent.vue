<script>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";

export default {
    props: {
        policy: Object,
        brokerage_amount_list: Array,
        payment_endorsement_list: Array,

        calculations: Array,
        endorsements: Array,
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
                    <td>PKR {{ format_number(calculations.brokerage_amount_final) }} </td>
                </tr>
                <tr>
                    <th>Brokerage Percentage </th>
                    <td>PKR {{ policy.brokerage_percentage }} % </td>
                </tr>
                <tr>
                    <th>Brokerage Received Amount</th>
                    <td>PKR {{ format_number(calculations.brokerage_amount_received_final) }} </td>
                </tr>
                <tr>
                    <th>Brokerage Outstanding Balance</th>
                    <td>PKR {{ format_number(calculations.brokerage_outstanding_final_amount) }} </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-bordered text-uppercase">
            <tr>
                <th colspan="8" class="bg-warning text-white">
                    Brokerage Booked Amount List
                </th>
            </tr>
            <tr>
                <th>SR.</th>
                <th>Policy No</th>
                <th>Brokerage/Commissioned Amount </th>
                <th>Brokerage Percentage </th>
                <th>Brokerage Received Amount </th>
            </tr>
            <tbody>
                <tr>
                    <td>1</td>
                    <td> {{ policy.policy_no }}</td>
                    <td> PKR {{ format_number(policy.brokerage_amount) }} </td>
                    <td> PKR {{ format_number(policy.brokerage_percentage) }} </td>
                    <td> PKR {{ format_number(calculations.brokerage_amount_received_total) }} </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-bordered text-uppercase">
            <tr>
                <th colspan="7" class="bg-warning text-white">
                    Endorsement Amount List
                </th>
            </tr>
            <tr>
                <th>SR.</th>
                <th>Policy No</th>
                <th>Brokerage Amount </th>
                <th>Brokerage Received Amount </th>
            </tr>

            <tbody>
                <template v-for="e, index in payment_endorsement_list" :key="e.id">
                    <tr>
                        <td> {{ ++index }}</td>
                        <td> {{ e.policy_no }}</td>
                        <td> PKR {{ format_number(e.brokerage_amount) }} </td>
                        <td> PKR {{ format_number(e.brokerage_amount_received) }} </td>
                    </tr>
                </template>
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