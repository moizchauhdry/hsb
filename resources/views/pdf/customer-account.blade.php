<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer Account</title>
</head>

<style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    body {
        font-size: 16px;
        padding: 50px 20px;
    }

    .customer-info .first-page {
        font-size: 16px;
    }

    .customer-info .second-page {
        font-size: 12px;
    }

    .customer-info .second-page b {
        color: #1F3763;
    }

    .customer-info .second-page ul {
        padding-left: 10px;
        margin: 10px 0;
    }

    .customer-info .second-page p {
        margin: 5px 0;
    }

    /* Header  */
    .customer-info header {
        display: inline-block;
    }

    .customer-info header .wow-headings {
        width: 65%;
        float: left;
    }

    .customer-info header .qr-codes {
        display: inline-block;
        width: 33%;
        float: left;
    }

    .customer-info .wow-logo {
        width: 100%;
        height: 10%;
    }

    .customer-info .wow-headings h1 {
        font-size: 25px;
        margin: 0;
        padding: 0;
    }

    .customer-info .qr-code-1,
    .customer-info .qr-code-2 {
        width: 41%;
        height: 12%;
        float: left;
        margin-left: 30px;
    }

    .customer-info .qr-code-2 {
        margin-left: 150px;
    }

    .customer-info .qr-code-1 img,
    .customer-info .qr-code-2 img {
        width: 100%;
        height: 100%;
    }

    /* Main  */
    .customer-info main {
        /* display: block; */
        margin-top: 150px;
    }

    .customer-info table,
    .customer-info table tr {
        width: 750px !important;
        border-collapse: collapse;
    }

    .customer-info table td {
        border: 1px solid #BDD6EE;
        width: 100%;
        height: 25px;
        text-align: left;
        padding-left: 5px;
    }

    .customer-info table td:first-child {
        width: 175px !important;
    }

    .customer-info .first-table td:last-child {
        text-align: center;
        width: 100% !important;
    }

    .quantity {
        width: 100px !important;
    }

    .product {
        width: 170px !important;
    }

    .border-center {
        border-left: none !important;
        border-right: none !important;
    }

    .border-left {
        border-left: none !important;
    }

    .border-right {
        border-right: none !important;
    }

    /* Section Signature  */

    .customer-info .section-signature p {
        margin: 30px 0;
    }

    .customer-info .section-signature ul {
        margin: 30px 0;
        padding-left: 20px;
    }

    .customer-info .section-signature div {
        margin: 80px 0;
    }

    .customer-info .section-signature span:last-child {
        margin-left: 100px;
    }
</style>

<body class="customer-info">

    <header>
        <div class="wow-headings">
            <img class="wow-logo" src="https://m.wowentertainmentforlife.ca/images/logo.png" alt="wow-logo" />
            <h1>Customer Account Setup Form For EV</h1>
        </div>
        <div class="qr-codes">
            <div class="qr-code-1"> <img
                    src="https://qrcg-free-editor.qr-code-generator.com/main/assets/images/websiteQRCode_noFrame.png"
                    alt="qr-code-1"></div>
            <div class="qr-code-2"> <img
                    src="https://qrcg-free-editor.qr-code-generator.com/main/assets/images/websiteQRCode_noFrame.png"
                    alt="qr-code-2"></div>
        </div>
    </header>

    <main>

        <section class="first-page">

            <section>
                <table class='first-table'>
                    <tr>
                        <td><b>Customer Information</b></td>
                        <td>Dated:</td>
                    </tr>
                    <tr>
                        <td>Full Name</td>
                        <td></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td>Phone Number</td>
                        <td class='border-center'>Home:</td>
                        <td class='border-left'>Cellular:</td>
                    </tr>
                </table>
                <table class='first-table'>
                    <tr>
                        <td>Address</td>
                        <td></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td>City</td>
                        <td class='border-center'>Province:</td>
                        <td class='border-left'>Postal Code:</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class='border-center'></td>
                        <td class='border-left'></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td>Product Information</td>
                        <td class='product'>Product</td>
                        <td class='quantity'>Quantity</td>
                        <td>Model/Type/Description</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class='product'>Electric Bike:</td>
                        <td class='quantity'></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class='product'>Electric Scooter:</td>
                        <td class='quantity'></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class='product'>Electric Kids Car:</td>
                        <td class='quantity'></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class='product'>Electric Hoverboard:</td>
                        <td class='quantity'></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class='product'>Electric Motorcycle:</td>
                        <td class='quantity'></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class='product'>Accessories:</td>
                        <td class='quantity'></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class='product'>Other:</td>
                        <td class='quantity'></td>
                        <td></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td>Service Purchased</td>
                        <td>Repair/Assembly/Service:</td>

                    </tr>
                    <tr>
                        <td></td>
                        <td>Delivery and Shipping:</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Technical Support:</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Extended Warranty:</td>
                    </tr>

                </table>

                <table>
                    <tr>
                        <td>Paid</td>
                        <td class='product'>Cash</td>
                        <td class='product'>CC</td>
                        <td>Paybright/Flexiti/NP #</td>
                    </tr>
                    <tr>
                        <td>Product Warranty</td>
                        <td class='product border-center'>Battery & Motor: </td>
                        <td class='product border-center'>Frame:</td>
                        <td class='border-left'>Electronics:</td>
                    </tr>

                </table>

                <table class='last-table'>
                    <tr>
                        <td>Comments</td>
                        <td></td>
                    </tr>
                </table>

            </section>

            <section class='section-signature'>
                <p>NOTE: There is <b>No Return/Exchange/Replacement</b> on any product. All sales are <b> FINAL.</b></p>
                <ul>
                    <li>I have read and fully understand all the terms & conditions related to the sales, after sale,
                        servicing,
                        shipping/handling, liability, safety & warranty of the product(s).</li>
                </ul>
                <div>
                    <span>Signature: _________________________________</span>
                    <span>Dated: _________________________________</span>
                </div>
            </section>

        </section>


        <section class="second-page">
            <b>Limited Warranty Terms</b>
            <p>All WOW Electric Rides (referred to as WER) products, including E-Bikes, E-Scooters, E-Motorcycles,
                Hoverboards, and E-Kids Cars (referred to as PRODUCT),
                and their individual Covered Components are protected against all manufacturing defects in material or
                workmanship for a period of _____ years for the
                frame and _____ years for the motor/battery (the “Warranty Period”). This Limited Warranty is only
                applicable to Canadian purchases and is subject to the
                following terms:</p>
            <ul>
                <li>The Limited Warranty is only applicable to the original owner who purchased the product from WER's
                    online or physical storefront. The Warranty Period
                    begins upon shipping of the product and ends immediately upon the earlier of the end of the Warranty
                    Period, any sale, or transfer to another person. Under
                    no circumstances shall the Limited Warranty apply to any subsequent owner or other transferee.</li>
                <li>The Limited Warranty is expressly limited to the replacement of a defective lithium-ion battery (the
                    “Battery”), frame, forks, stem, handlebar, headset, seat
                    post, saddle, brakes, lights, bottom bracket, crankset, pedals, rims, wheel hub, freewheel,
                    cassette, derailleur, shifter, motor, throttle, controller, wiring
                    harness, LCD display, kickstand, reflectors, and hardware (each a “Covered Component”).</li>
                <li>The Covered Components are warranted to be free of defects in materials and/or workmanship during
                    the Warranty Period.
                    If WER determines a Covered Component is defective, WER will, as your sole and exclusive remedy and
                    at WER’s sole discretion:
                    <br>
                    (a) repair the defective Covered Component free of charge with new or refurbished parts; or
                    <br>
                    (b) replace the defective Covered Component with a new Covered Component.
                    <br>
                    Determining whether damage or defect to a WER product or covered component is protected by this
                    limited warranty shall be in the sole discretion of
                    WER.
                </li>
            </ul>
            <b>This Limited Warranty Does Not Cover</b>
            <ul>
                <li>Normal wear and tear of any covered component is not covered by the warranty.</li>
                <li>Consumables or normal wear and tear parts, including but not limited to tires, tubes, brake pads,
                    cables and housing, grips, chain, and spokes, are not
                    covered by the warranty.</li>
                <li>Any damage or defects to covered components resulting from failure to follow instructions in the
                    owner's manual, acts of God, accident, misuse, neglect,
                    abuse, commercial use, alterations, modification, improper assembly, installation of parts or
                    accessories not originally intended or compatible with the
                    product as sold, operator error, water damage, extreme riding, stunt riding, or improper follow-up
                    maintenance, are not covered by the warranty.</li>
                <li>WER will not be liable and/or responsible for any damages/faults/issues caused by improper assembly
                    of the product or if the assembly is done by other
                    than the WER professional installer/mechanic.</li>
                <li>WER will not be liable and/or responsible for any damage, failure, or loss caused by any
                    unauthorized service or use of unauthorized parts.</li>
                <li>The battery is not warranted from damage resulting from power surges, use of an improper charger,
                    improper maintenance or other such misuse,
                    normal wear, or water damage</li>
                <li>Power supply, adapters, remotes, or any attachments are not covered under warranty.</li>
                <li>Any third-party products, accessories, and aftermarket products sold or purchased separately are not
                    covered by the warranty.</li>
                <li> Please note that this is not an exhaustive list of all terms and conditions. Customers should refer
                    to the full terms and conditions for further information.</li>
            </ul>

            <b>Limitation of Liability and Safety:</b>

            <ul>
                <li>It is the Buyer’s responsibility to check and comply with any <b>local laws</b> and
                    <b>restrictions</b> before using any WER product on the street and in public
                </li>
                <li>Some WER products are designed to be used strictly in <b>off-road</b> riding and are not street
                    legal. They should not be used on streets or public roads. It is
                    buyers’ responsibility to check and adhere to the riding restrictions and use the WER product
                    accordingly.</li>
                <li>WER products must be used with protective gear for personal safety, such as helmets, guards, gloves,
                    goggles, vests, etc.</li>
                <li>The maximum liability to you on products purchased from our stores or website shall be limited to
                    the purchase price of the products you paid.</li>
                <li>We will not be liable for any property damage, personal injury, fire-related incidents, loss of use,
                    interruption of business, loss of profits, or other
                    consequential, indirect, incidental, punitive, or special damages arising from use, malfunction, or
                    due to defective equipment. This applies in all cases,
                    however caused, whether for breach of warranty, contract, tort (including negligence), strict
                    liability, or other reasons.</li>
                <li>By purchasing equipment, the buyer agrees that IN NO EVENT WILL WOW TECHNOLOGIES CORPORATION BE
                    LIABLE FOR ANY SPECIAL, INDIRECT,
                    INCIDENTAL, OR CONSEQUENTIAL DAMAGES EVEN IF THE COMPANY HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH
                    DAMAGES.</li>
                <li>The buyer acknowledges that the use of electric vehicles involves inherent risks, including but not
                    limited to accidents, injuries, and death. The buyer
                    assumes all risks associated with the use of WER products and releases WOW TECHNOLOGIES CORPORATION
                    and its affiliates from all liability for any
                    harm or injury arising from such use.</li>
                <li>The buyer agrees to indemnify and hold WOW TECHNOLOGIES CORPORATION harmless from and against all
                    claims, damages, liabilities, costs, and
                    expenses arising from or related to the buyer's use of WER products.</li>
                <li>The buyer agrees to use WER products only for their intended purpose and in accordance with the
                    manufacturer's instructions and warnings.</li>
            </ul>

            <b>Shipping & Handling</b>
            <p>This Limited Warranty does not cover damage to Covered Components incurred during shipping, but WER may
                replace such damaged Covered Components if
                you follow these steps:</p>

            <ul>
                <li>Notify WER of any Covered Components damaged during shipping within 7 days of your original receipt.
                </li>
                <li>Provide WER with a dated picture of the damaged Covered Component.</li>
                <li>Return all original packaging and paperwork included with the product at your sole cost, unless WER
                    agrees in writing to pay for your shipping costs.</li>
                <li>Note any immediately recognizable damage on the shipper’s Bill of Lading prior to signing off on the
                    shipment.</li>
                <li>Shipping damage claims are time-sensitive, and it is your responsibility to inspect the product for
                    damage upon receipt and report it immediately.</li>
                <li>If you choose to use your own independent shipping method, such as a freight forwarder or other
                    similar service, WER will not replace any Covered
                    Components damaged during such shipping.</li>
            </ul>

            <p>Note that any Covered Components that are damaged during shipping must be reported to WER within 7 days
                of your original receipt to be eligible for
                replacement. Additionally, any immediately recognizable damage to the product or its packaging should be
                noted on the shipper’s Bill of Lading prior to
                signing off on the shipment.</p>
        </section>

    </main>


</body>

</html>