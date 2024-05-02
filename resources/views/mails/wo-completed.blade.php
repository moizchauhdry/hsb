<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Work Order - Completed</title>
</head>

<body>
    <div>
        <p>Dear <b>{{$work_order->billing_address->wo_addr_customer_name}}</b>,</p>

        <p>
            We are pleased to inform you that your work order with order number <b>{{$work_order->id}}</b> has been
            completed.
        </p>

        <p>
            The following issue <b>{{$work_order->wo_title}}</b> has been fixed and your equipment is ready for
            pick up.
        </p>

        <p>
            We have thoroughly tested your equipment to ensure that it is in optimal working condition. If you notice
            any issues with your equipment or have any concerns, please do not hesitate to contact us immediately.
        </p>

        <p>
            We appreciate your business and are committed to providing you with the highest quality of service. If you
            have any further equipment servicing needs in the future, please do not hesitate to contact us.
        </p>

        <p>
            Thank you for choosing our services, and we look forward to serving you in the future.
        </p>

        <p>
            Best regards, <br>
            WOW Service Center
        </p>
    </div>
</body>

</html>