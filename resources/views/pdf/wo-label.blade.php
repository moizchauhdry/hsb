<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Work Order - Label</title>
</head>

<body>
    <table id="section-to-print">
        <tr style="text-align: center; font-size:12px">
            <td>
                <img src="https://m.wowentertainmentforlife.ca/images/logo.png" alt="" style="width: 200px">
            </td>
        </tr>

        <tr style="text-align: center">
            <td>
                <h5>WORK ORDER #</h5>
                <h1>{{$wo->id}}</h1>
            </td>
        </tr>
        <tr style="text-align: center;">
            <td style="font-size: 14px; width: 12px">
                <b>Customer Name:</b> <br> <span>{{$wo->billing_address->wo_addr_customer_name}}</span> <br> <br>
                <b>Job Title:</b> <br> <span>{{$wo->wo_title}}</span> <br> <br>
                <b>Order Date:</b> <span>{{Carbon\Carbon::parse($wo->created_at)->format('Y-m-d')}}</span> <br>
                <b>Return Date:</b> <span>{{$wo->wo_return_date}}</span>
            </td>
        </tr>
    </table>
</body>

</html>