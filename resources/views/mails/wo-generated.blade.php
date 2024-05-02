<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Work Order - Generated</title>
</head>

<body>
    <div>
        <p>Dear <b>{{$work_order->billing_address->wo_addr_customer_name}}</b>,</p>

        <p>We are writing to let you know that your work order has been generated and is currently being processed.
            Your work order number is <b>{{$work_order->id}}</b>.</p>

        <p>
            Please note that the expected return date for your equipment is <b>{{$work_order->wo_return_date}}</b>. Our
            team is committed to delivering high-quality work in a timely manner, and we will do our best to ensure that
            your equipment is returned to you on or before the specified return date.
        </p>

        <p>
            If you have any questions or concerns about your work order, please feel free to contact us at any time. We
            are always here to assist you.
        </p>

        <p>
            Thank you for choosing our services for your equipment servicing needs.
        </p>

        <p>
            Best regards, <br>
            WOW Service Center
        </p>
    </div>
</body>

</html>