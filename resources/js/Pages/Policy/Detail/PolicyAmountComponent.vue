<script>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";

export default {
    props: {
        policy: Object,
        policy_amount_list: Array,
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
    <div class="table-responsive" v-if="permission.policy_amount">
        <table class="table table-bordered text-uppercase">
            <tbody>
                <tr>
                    <th colspan="2" class="bg-warning text-white">
                        Policy Amount
                    </th>
                </tr>

                <tr>
                    <th>Sum insured </th>
                    <td> PKR {{ format_number(policy.sum_insured) }} </td>
                </tr>

                <tr>
                    <th>Net Premium </th>
                    <td> PKR {{ format_number(policy.net_premium) }} </td>
                </tr>

                <tr>
                    <th>Gross Premium </th>
                    <td> PKR {{ format_number(policy.gross_premium) }} </td>
                </tr>

                <tr>
                    <th>GP Collected</th>
                    <td> PKR {{ format_number(policy.gp_collected) }} </td>
                </tr>

                <tr>
                    <th>Gross Premium Outstanding</th>
                    <td> PKR {{ format_number(policy.gross_premium - policy.gp_collected) }} </td>
                </tr>

            </tbody>
        </table>

        <table class="table table-bordered text-uppercase">
            <tr>
                <th colspan="7" class="bg-warning text-white">
                    Policy Amount List
                </th>
            </tr>
            <tr>
                <th>SR.</th>
                <th>Policy No</th>
                <th>Net Premium </th>
                <th>Gross Premium </th>
                <th>Gross Premium Received </th>
                <th>Receipt Amount</th>
                <th>Receipt Date</th>
            </tr>

            <tbody>
                <template v-for="amount, index in policy_amount_list" :key="amount.id">

                    <tr>
                        <td> {{ ++index }}</td>
                        <td> {{ amount.policy_no }}</td>
                        <td> PKR {{ format_number(amount.net_premium) }} </td>
                        <td> PKR {{ format_number(amount.gross_premium) }} </td>
                        <td> PKR {{ format_number(amount.gross_premium_received) }} </td>
                        <td> PKR {{ format_number(amount.receipt_amount) }} </td>
                        <td> {{ amount.receipt_at }} </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>