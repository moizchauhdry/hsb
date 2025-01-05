<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Export</title>
</head>

<body>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th colspan="10" style="text-align: center;"> @php echo Str::upper($slug) @endphp REPORT </th>
            </tr>
            <tr style="text-align: center">
                <th>Sr. No.</th>
                <th>Policy Type</th>
                <th>Client Name</th>
                <th>Policy Number</th>
                <th>Insurer Name</th>
                <th>Agency</th>
                <th>Class of Business</th>
                <th>Sum Insured</th>
                <th>Net Premium</th>
                <th>Gross Premium</th>

                @if ($slug == "gross")
                <th class="px-2">Gross Premium Collected</th>
                <th class="px-2">Gross Premium Outstanding</th>
                @endif

                @if ($slug == "commission")
                <th class="px-2">Brokerage Commission Amount</th>
                <th class="px-2">Brokerage Received Amount</th>
                <th class="px-2">Brokerage Outstanding Amount</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($policies as $policy)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $policy->policy_type }}</td>
                <td>{{ $policy->client->name ?? "" }}</td>
                <td>{{ $policy->policy_no }}</td>
                <td>{{ $policy->insurance->name ?? "" }}</td>
                <td>{{ $policy->agency->name ?? "" }}</td>
                <td>{{ $policy->business_class->class_name ?? "" }}</td>

                <td class="px-2">{{ format_number($policy->sum_insured) }}</td>
                <td class="px-2">{{ format_number($policy->net_premium) }}</td>
                <td class="px-2">{{ format_number($policy->gross_premium) }}</td>

                @if ($slug == "gross")
                <td class="px-2">{{ format_number($policy->gross_premium_collected)}}</td>
                <td class="px-2">{{format_number($policy->gross_premium_outstanding) }}</td>
                @endif

                @if ($slug == "commission")
                <td class="px-2">{{ format_number($policy->brokerage_amount) }}</td>
                <td class="px-2">{{format_number($policy->brokerage_received_amount) }}</td>
                <td class="px-2">{{ format_number($policy->brokerage_amount_outstanding) }}</td>
                @endif
            </tr>
            @endforeach
            <tr style="font-size: 14px">
                <td colspan="7" style="text-align: right">Grand Total</td>
                <td>{{format_number($grand_total['sum_insured'])}}</td>
                <td>{{format_number($grand_total['net_premium'])}}</td>
                <td>{{format_number($grand_total['gross_premium'])}}</td>

                @if ($slug == "gross")
                <td class="px-2">{{ format_number($grand_total['gross_premium_collected'])}}</td>
                <td class="px-2">{{format_number($grand_total['gross_premium_outstanding']) }}</td>
                @endif

                @if ($slug == "commission")
                <td class="px-2">{{ format_number($grand_total['brokerage_amount']) }}</td>
                <td class="px-2">{{format_number($grand_total['brokerage_received_amount']) }}</td>
                <td class="px-2">{{ format_number($grand_total['brokerage_amount_outstanding']) }}</td>
                @endif
            </tr>
        </tbody>
    </table>
</body>

</html>