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
    </div>
</template>