<script>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";

export default {
    props: {
        policy: Object,
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
                    <td>PKR {{ format_number(policy.brokerage_received_amount) }} </td>
                </tr>
                <tr>
                    <th>Brokerage Outstanding Balance</th>
                    <td>PKR {{ format_number(policy.brokerage_amount - policy.brokerage_received_amount) }} </td>
                </tr>
                <tr>
                    <th>Brokerage Paid Date</th>
                    <td>{{ policy.brokerage_paid_date }}</td>
                </tr>
                <!-- <tr>
                    <th>Brokerage Status</th>
                    <td>{{ policy.brokerage_status }}</td>
                </tr> -->
            </tbody>
        </table>
    </div>
</template>