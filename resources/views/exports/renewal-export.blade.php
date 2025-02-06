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
                <th colspan="10" style="text-align: center;"> Renewal Export </th>
            </tr>
            <tr style="text-align: center">
                <th>Sr.</th>
                <th style="min-width: 250px">Policy No</th>
                <th style="min-width: 200px">Client Name</th>
                <th style="min-width: 200px">Agency Name</th>
                <th style="min-width: 120px">COB Name</th>
                <th style="min-width: 120px;">Expiry Date</th>
                <th style="min-width: 80px;">Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($policies as $policy)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $policy->policy_no }}</td>
                <td>{{ $policy->client_name }}</td>
                <td>{{ $policy->agency_name }}</td>
                <td>{{ $policy->cob_name }}</td>
                <td>{{ $policy->expiry_date }}</td>
                <td>{{ $policy->policy_type }} {{ $policy->policy_lead_type }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>