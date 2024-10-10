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
                <th>Gross Premium</th>
                <th>Net Premium</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($policies as $policy)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $policy->orignal_endorsment }}</td>
                <td>{{ $policy->client->name ?? "" }}</td>
                <td>{{ $policy->policy_no }}</td>
                <td>{{ $policy->insurance->name ?? "" }}</td>
                <td>{{ $policy->agency->name ?? "" }}</td>
                <td>{{ $policy->business_class->class_name ?? "" }}</td>
                <td>{{ $policy->sum_insured }}</td>
                <td>{{ $policy->gross_premium }}</td>
                <td>{{ $policy->net_premium }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>