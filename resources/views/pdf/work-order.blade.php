<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Work Order - {{$wo->id}}</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        table {
            border-collapse: collapse;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }

        ul {
            list-style: none;
        }

        .top-header {
            display: inline-block;
            width: 100%;
            margin: 0;
            margin-bottom: 110px;
        }

        .top-header .wow-logo {
            height: 8%;
            width: 40%;
            float: left;
            margin-left: -30px;
        }

        .top-header .top-header-list {
            width: 40%;
            float: left;
            padding: 0;
            margin-left: 220px;
            margin-top: -30px
        }

        .top-header-list h1 {
            margin-left: 70px;
            padding: 0;
            font-size: 18px;
            width: 100%;
        }

        .top-header-list ul {
            margin-left: 70px;
            width: 100%;
        }

        .top-header-list li {
            margin: 0 10px;
            width: 300px;
            display: inline-block;
        }

        .top-header-list li p {
            text-align: right;
            float: left;
            margin: 0;
            width: 35%;
            margin-left: -110px;
        }

        .top-header .data-row {
            /* float:left; */
            text-align: center;
            border: 1px solid rgb(128, 127, 127);
            background-color: whitesmoke;
            width: 100px;
            height: 15px;
            margin: 0;
            padding: 0;

        }

        .bottom-header {
            width: 100%;
            display: inline-block;
            margin-top: 20px;
        }

        .bottom-header-contact {
            /* float:left; */
            width: 50%;
            margin: 0;
            margin-left: -30px;
            margin-top: -50px;
        }

        .bottom-header-list {
            /* float:left; */
            width: 70%;
            margin: 0;
            margin-top: -12%;
            margin-left: 425px;
        }

        .bottom-header-list li {
            margin: 0;
        }

        .bottom-header-list li p {
            float: left;
            margin: 0;
            margin-left: -130px;
        }

        .bottom-header .data-row {
            border: 1px solid rgb(128, 127, 127);
            background-color: whitesmoke;
            width: 250px;
            height: 15px;
        }

        .bottom-header-contact p {
            margin: 0 10px;
        }

        .bottom-header-list li p {
            width: 150px;
        }

        .bottom-header {
            display: flex !important;
            justify-content: space-between;
            align-items: end;
        }


        /* Main Body */


        .main-body {
            padding: 0;
            margin: 0;
            width: 100%;
        }



        table li {
            display: flex !important;
            justify-content: end;
            align-items: end;
            margin: 0;
        }


        table .data-row {
            border: 1px solid rgb(128, 127, 127);
            background-color: whitesmoke;
            height: 15px;
            width: 350px;
            padding: 2px 5px;
        }

        th {
            background: rgb(59, 78, 135);
            color: white;
            text-align: left;
            height: 15px;
            padding-left: 20px;
        }

        td {
            border: 1px solid black;
            margin: 0;
            padding: 0;
            padding-left: 5px;
        }

        .first-table {
            width: 700px !important;
            margin: 0;
            margin-top: 10px;
            margin-left: -30px;
        }

        .first-table tr th:first-child {
            width: 300px;
        }

        .first-table tr th:last-child {
            width: 400px;
        }

        .first-table td {
            border: 0;
            margin: 0;
            padding: 0;
        }

        .first-table li:first-child p {
            margin: 0;
        }

        .first-table li p {
            float: left;
            margin-left: -100px;
            width: 150px;
            padding-left: 5px;
        }

        .first-table .data-row {
            width: 300px;
            height: 15px;
            padding: 0;
            margin: 0;
            padding-left: 5px;
            text-align: left;
            margin-left: 120px;
        }

        .first-table tr td:first-child {
            padding-left: 5px;
        }

        .service-table,
        .parts-table {
            width: 750px;
            margin-top: 10px;
            margin-left: -30px;
        }

        .service-table th,
        .service-table td,
        .parts-table th,
        .parts-table td {
            width: 80px;
            height: 15px;
            text-align: center;
        }

        .service-table tr th:first-child,
        .service-table tr td:first-child,
        .parts-table tr th:first-child,
        .parts-table tr td:first-child {
            width: 350px;
            text-align: center;
        }

        .bottom-table-list {
            margin-left: 520px;
        }

        .bottom-table-list li {
            display: flex !important;
            justify-content: end;
            display: inline-block;
        }

        .bottom-table-list .data-row {
            background-color: rgb(226, 222, 222);
            width: 150px;
            height: 15px;
            display: flex !important;
            justify-content: space-between;
            /* float:left; */
            margin: 0;
            padding: 0 5px;
            padding-left: 5px;
        }

        .bottom-table-list .data-row span:last-child {
            margin-left: 80px;
        }

        .bottom-table-list li p {
            width: 100px;
            float: left;
            margin: 0;
            margin-left: -110px;
        }


        footer h5 {
            text-align: center;
            font-size: 14px;
            margin-top: 5rem;
            font-style: italic;
            font-weight: 400;
        }


        .footer-sign div,
        .footer-date div {
            display: flex !important;
            width: 100%;
            align-items: end;
            justify-content: end;

        }

        .footer-date div {
            margin: 10px 0;
        }

        .footer-content p {
            width: 300px;
            text-align: end;
            margin-bottom: 10px;
            /* float:left; */
        }

        .footer-content {
            /* width: 100%; */
            display: inline-block;
        }

        .footer-content>div {
            float: left;
            width: 300px;
        }

        .footer-sign {
            width: 200px;
        }

        .footer-date {
            width: 200px;
            margin-left: 115px;
        }

        .footer-date div:last-child {
            margin: 0;
            margin-top: 10px;
            margin-left: 80px;
        }
    </style>
</head>

<body>
    <div class="main">

        <header>
            <div class="top-header">
                <img class="wow-logo" src="https://m.wowentertainmentforlife.ca/images/logo.png" alt="wow-logo" />
                <div class="top-header-list">
                    <h1>WORK ORDER</h1>
                    <ul>
                        <li>
                            <p>W.O. # :</p>
                            <div class="data-row">{{$wo->id}}</div>
                        </li>
                        <li>
                            <p>W.O. Date :</p>
                            <div class="data-row">{{$wo->wo_date}}</div>
                        </li>
                        <li>
                            <p>Return Date :</p>
                            <div class="data-row">{{$wo->wo_return_date}}</div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="bottom-header">
                <div class='bottom-header-contact'>
                    <p>3516, 26 Street, Bay #3, NE</p>
                    <p>[Calgary, AB T1Y 4T7]</p>
                    <p><b>Phone:</b> 587.800.9485 </p>
                    <p><b>Email:</b> service@wowerides.ca </p>
                    <p><b>Website:</b> wowerides.ca</p>
                </div>
                <ul class="bottom-header-list">
                    <li>
                        <p>Customer Name: </p>
                        <div class="data-row">{{$wo->billing_address->wo_addr_customer_name}}</div>
                    </li>
                    <li>
                        <p>Category: </p>
                        <div class="data-row">{{$wo->wo_bike_category}}</div>
                    </li>
                    <li>
                        <p>Model:</p>
                        <div class="data-row">{{$wo->wo_bike_model}} {{$wo->wo_bike_color}}</div>
                    </li>
                </ul>
            </div>
        </header>

        <section class="main-body">

            <!-- JOB Table  -->
            <table class="first-table">
                <tr>
                    <th>JOB</th>
                    <th>BILL TO</th>
                </tr>
                <tr>
                    <td>{{$wo->wo_title}}</td>
                    <td>
                        <li>
                            <p>Customer Name</p>
                            <div class="data-row">{{$wo->billing_address->wo_addr_customer_name ?? '-'}}</div>
                        </li>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <li>
                            <p>[Street Address]</p>
                            <div class="data-row">{{$wo->billing_address->wo_addr_str_address ?? '-'}}</div>
                        </li>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <li>
                            <p>[City, ST ZIP]</p>
                            <div class="data-row">
                                {{$wo->billing_address->wo_addr_city ?? '-'}},
                                {{$wo->billing_address->wo_addr_state ?? '-'}},
                                {{$wo->billing_address->wo_addr_zipcode ?? '-'}}
                            </div>
                        </li>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <li>
                            <p>[Phone]</p>
                            <div class="data-row">{{$wo->billing_address->wo_addr_phone ?? '-'}}</div>
                        </li>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <li>
                            <p>Email Address</p>
                            <div class="data-row">{{$wo->billing_address->wo_addr_email ?? '-'}}</div>
                        </li>
                    </td>
                </tr>
            </table>

            @if ($wo->items->where('wo_item_category_id',1)->count() > 0)
            <!-- SERVICE and LABOR  Table -->
            <table class="service-table">
                <tr>
                    <th>SERVICE and LABOR</th>
                    <th>Hours</th>
                    <th>Rate ($/hr)</th>
                    <th>Line Total</th>
                </tr>
                @foreach ($wo->items->where('wo_item_category_id',1) as $item)
                <tr>
                    <td>{{$item->wo_item_name}}</td>
                    <td>{{$item->wo_item_hours}}</td>
                    <td>{{$item->wo_item_rate}}</td>
                    <td>{{$item->wo_item_line_total}}</td>
                </tr>
                @endforeach
            </table>

            <ul class="bottom-table-list">
                <li>
                    <p>SUBTOTAL</p>
                    <div class="data-row"><span>$</span>
                        <span>
                            {{calculation($wo->id,1)['subtotal']}}
                        </span>
                    </div>
                </li>
                <li>
                    <p>DISCOUNT</p>
                    <div class="data-row"><span>$</span>
                        <span>
                            {{calculation($wo->id,1)['discount']}}
                        </span>
                    </div>
                </li>
                <li>
                    <p>TAX RATE</p>
                    <div class="data-row"><span>$</span>
                        <span>
                            {{calculation($wo->id,1)['tax_rate']}}%
                        </span>
                    </div>
                </li>
                <li>
                    <p>TAX AMOUNT</p>
                    <div class="data-row"><span>$</span>
                        <span>
                            {{calculation($wo->id,1)['tax_amount']}}
                        </span>
                    </div>
                </li>
                <li>
                    <p>TOTAL</p>
                    <div class="data-row"><span>$</span>
                        <span>
                            {{calculation($wo->id,1)['total']}}
                        </span>
                    </div>
                </li>
            </ul>
            @endif

            @if ($wo->items->where('wo_item_category_id',2)->count() > 0)
            <!-- PARTS and MATERIALS Table -->
            <table class="parts-table">
                <tr>
                    <th>PARTS and MATERIALS</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Line Total</th>
                </tr>
                @foreach ($wo->items->where('wo_item_category_id',2) as $item)
                <tr>
                    <td>{{$item->wo_item_name}}</td>
                    <td>{{$item->wo_item_hours}}</td>
                    <td>{{$item->wo_item_rate}}</td>
                    <td>
                        {{number_format((float)$wo->wo_item_line_total, 2, '.', '')}}
                    </td>
                </tr>
                @endforeach
            </table>

            <ul class="bottom-table-list">
                <li>
                    <p>SUBTOTAL</p>
                    <div class="data-row"><span>$</span>
                        <span>
                            {{calculation($wo->id,2)['subtotal']}}
                        </span>
                    </div>
                </li>
                <li>
                    <p>DISCOUNT</p>
                    <div class="data-row"><span>$</span>
                        <span>
                            {{calculation($wo->id,2)['discount']}}
                        </span>
                    </div>
                </li>
                <li>
                    <p>TAX RATE</p>
                    <div class="data-row"><span>$</span>
                        <span>
                            {{calculation($wo->id,2)['tax_rate']}}%
                        </span>
                    </div>
                </li>
                <li>
                    <p>TAX AMOUNT</p>
                    <div class="data-row"><span>$</span>
                        <span>
                            {{calculation($wo->id,2)['tax_amount']}}
                        </span>
                    </div>
                </li>
                <li>
                    <p>TOTAL</p>
                    <div class="data-row"><span>$</span>
                        <span>
                            {{calculation($wo->id,2)['total']}}
                        </span>
                    </div>
                </li>
                <li>
                    <p>GRAND TOTAL</p>
                    <div class="data-row"><span>$</span>
                        <span>
                            {{format_number($wo->wo_grand_total)}}
                        </span>
                    </div>
                </li>
            </ul>
            @endif

        </section>

        
        <section>
            @if ($wo->wo_notes)
            <div>
                <b>Notes:</b> {!!$wo->wo_notes!!}
            </div>
            @endif
        </section>

        <footer>
            <div class="footer-content">
                <div class="footer-sign">
                    <div>
                        <p>Customer Signature: __________________________</p>
                    </div>
                </div>
                <div class="footer-date">
                    <div>
                        <p>Completed Date: _____________________________</p>
                    </div>
                    <div>
                        <p>Date: _____________________________</p>
                    </div>
                </div>
            </div>
            <h5>Thank You For Your Business!</h5>
        </footer>
    </div>
</body>

</html>