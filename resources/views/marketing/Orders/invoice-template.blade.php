<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        /* Set default font size and family for the entire document */
        body {
            font-family: Arial, sans-serif;
        }

        .invoice-header {
            width: 50%;
            float: left;
        }

        .invoice-header h5 {
            margin: 0;
            font-weight: bold;
            font-size: 20px;
        }

        .invoice-header2 {
            width: 50%;
            float: right;
            text-align: right;
        }

        .invoice-header2 img {
            max-width: 150px; /* Adjust the logo size */
            height: auto;
        }

        .clearfix {
            clear: both;
        }
        
        .invoice-details {
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }
        
        .invoice-details h4 {
            margin-bottom: 10px;
        }
        
        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .invoice-details td {
            padding: 10px;
            vertical-align: top;
        }
        
        .invoice-details h5 {
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .invoice-details address {
            line-height: 1.5;
            margin: 0;
        }
        
        .invoice-details span {
            display: block;
            line-height: 1.5;
            font-weight: 500;
        }
        
        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-items th, .invoice-items td {
            border: 1px solid #000;
            padding: 8px;
        }

        .invoice-items thead {
            background-color: #f2f2f2; /* Background color for table header */
        }
        
        .invoice-totalstitle {
            width: 50%;
            float: left;
        }

        .invoice-totalstitle h5 {
            margin: 0;
            font-weight: bold;
        }

        .invoice-totalsdet {
            width: 50%;
            float: right;
            text-align: right;
        }
        
        .invoice-totalsdet1 {
            width: 50%;
            float: left;
        }

        .invoice-totalsdet2 {
            width: 50%;
            float: right;
            text-align: right;
        }
        .invoice-finals1 {
            width: 50%;
            float: left;
        }

        .invoice-finals2 {
            width: 50%;
            float: right;
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- Left header section -->
    <div class="invoice-header">
        <h5>Company Name</h5>
        Address<br>
        <span>Phone no: ***********</span><br>
        <span>Email: ***@***.com</span><br>
        <span>State: Tamil Nadu</span>
    </div>

    <!-- Right header section -->
    <div class="invoice-header2">
        <br><br><br>
        <img src="" alt="Company Logo">
    </div>

    <!-- Clear floats to prevent overlap -->
    <div class="clearfix"></div>
    <hr>

    <!-- Invoice details section -->
    <div class="invoice-details">
        <h4>Tax Invoice</h4>
        <table>
            <tr>
                <!-- Bill To Section -->
                <td style="text-align: left; vertical-align: top; width: 33%;">
                    <h5>Bill To</h5><br>
                    <address style="font-style: normal;">
                        <span style="line-height: 1.5;">
                            {{ $orders[0]['employee_type'] === 'Dealer' ? $orders[0]['employee_name'] : $orders[0]['company_name'] }}<br>
                            <span>{{ $orders[0]['employee_type'] === 'Dealer' ? str_replace('"', "", $orders[0]['employee_address']) : str_replace('"', "", $orders[0]['client_address']) }}</span>
                        </span><br>
                        <span style="display: block; line-height: 1.5; font-weight: 500;">
                            Phone No : {{ $orders[0]['employee_type'] === 'Dealer' ? str_replace('"', "", $orders[0]['employee_mobile']) : str_replace('"', "", $orders[0]['client_mobile']) }}
                        </span>
                    </address>
                </td>
                
                <!-- Ship To Section -->
                <td style="text-align: left; vertical-align: top; width: 33%;">
                    <h5>Ship To</h5><br>
                    <address style="font-style: normal;">
                        <span style="line-height: 1.5;">
                            {{ $orders[0]['employee_type'] === 'Dealer' ? $orders[0]['company_name'] : $orders[0]['company_name'] }}<br>
                            <span>{{ $orders[0]['employee_type'] === 'Dealer' ? str_replace('"', "", $orders[0]['employee_address']) : str_replace('"', "", $orders[0]['client_address']) }}</span>
                        </span><br>
                        <span style="display: block; line-height: 1.5; font-weight: 500;">
                           Phone No : {{ $orders[0]['employee_type'] === 'Dealer' ? str_replace('"', "", $orders[0]['employee_mobile']) : str_replace('"', "", $orders[0]['client_mobile']) }}
                        </span>
                    </address>
                </td>
                
                <!-- Invoice Details Section -->
                <td style="text-align: left; vertical-align: top; width: 33%;">
                    <h5>Invoice Details</h5><br>
                    <span style="display: block; line-height: 1.5; font-weight: 500;">
                        Invoice No: {{ $invoice_no }}
                    </span><br>
                    <span style="display: block; line-height: 1.5; font-weight: 500;">
                        Date: {{ date("d-m-Y") }}
                    </span><br>
                    <span style="display: block; line-height: 1.5; font-weight: 500;">
                        Place Of Supply: Tamil Nadu
                    </span><br>
                    <span style="display: block; line-height: 1.5; font-weight: 500;">
                        Sales Man : {{ $orders[0]['employee_type'] === 'Dealer' ? '----' : $orders[0]['employee_name'] }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
    <hr>
    <div class="invoice-items">

        <table style="width: 100%; font-family: Arial, sans-serif; border: none; border-collapse: collapse;">

            <thead>
                <tr style="background-color: #006666; color: #fff; font-size: x-large;">
                    <th colspan="1" class="table-headclass" style="border: none; text-align: left;color: #fff;"> # </th>
                    <th class="table-headclass" style="border: none; text-align: left; font-size: 15px;color: #fff;"> Item name </th>
                    <th class="table-headclass" style="border: none; text-align: center; font-size: 15px;color: #fff;"> HSN&nbsp;/<br>SAC </th>
                    <th class="table-headclass" style="border: none; text-align: center; font-size: 15px;color: #fff;"> Quantity </th>
                    <th class="table-headclass" style="border: none; text-align: center; font-size: 15px;color: #fff;"> Unit Price </th>
                    <th class="table-headclass" style="border: none; text-align: center; font-size: 15px;color: #fff;"> Discount </th>
                    <th class="table-headclass" style="border: none; text-align: center; font-size: 15px;color: #fff;"> GST </th>
                    <th class="table-headclass" style="border: none; text-align: center; font-size: 15px;color: #fff;"> Amount </th>
                </tr>
            </thead>

            <tbody>
                @php $total = 0; $mid_total = 0; $finaltotal = 0; @endphp
                @for ($i = 0; $i < count($orders); $i++)
                <tr>
                    <td style="border: none; text-align: left;">{{ $i+1 }}</td>
                    <td style="border: none; font-size: 12px; text-align: left;"><strong>{{ $orders[$i]['product_name'] }}</strong></td>
                    <td style="border: none; text-align: center;"></td>
                    <td style="border: none; text-align: center;">{{ $orders[$i]['quantity'] }}</td>
                    <td style="border: none; text-align: center;">{{ $orders[$i]['product_price'] }} INR</td>
                    <td style="border: none; text-align: center;">{{ $orders[$i]['discount_amt'] }} INR ({{ $orders[$i]['discount'] }}%)</td>
                    <td style="border: none; text-align: center;">{{ $orders[$i]['gst_amt'] }} INR ({{ $orders[$i]['gst_per'] }}%)</td>
                    <td style="border: none; text-align: center;">{{ ($orders[$i]['product_price'] * $orders[$i]['quantity']) }} INR</td>
                </tr>
                @php
                    $total += $orders[$i]['product_price'] * $orders[$i]['quantity'];
                    
                    $mid_total += ($orders[$i]['product_price'] * $orders[$i]['quantity']);
                    $finaltotal += (($orders[$i]['product_price'] * $orders[$i]['quantity']) - $orders[$i]['discount_amt']) + $orders[$i]['gst_amt'];
                @endphp
                @endfor
            </tbody>

            <tfoot>
                <tr>
                    <td style="border-left: none; border-right: none;"></td>
                    <td style="border-left: none; border-right: none; font-size: 12px; text-align: left;"><strong>Total</strong></td>
                    <td style="border-left: none; border-right: none;"></td>
                    <td style="border-left: none; border-right: none; text-align: center;">{{ array_sum(array_column($orders, 'quantity')) }}</td>
                    <td style="border-left: none; border-right: none;"></td>
                    <td style="border-left: none; border-right: none; text-align: center;"> {{ array_sum(array_column($orders, 'discount_amt')) }} INR</td>
                    <td style="border-left: none; border-right: none; text-align: center;">{{ array_sum(array_column($orders, 'gst_amt')) }} INR</td>
                    <td style="border-left: none; border-right: none; text-align: center;">{{ $mid_total }} INR</td>
                </tr>
            </tfoot>

        </table>

    </div>
    
    <div class="invoice-totals">
        
        <div class="invoice-totalstitle">
            <h5>Invoice Amount In Words</h5>
            <span>{{ TextOfAmount($mid_total) }}</span><br><br>
            <h5>Terms and Conditions</h5>
            <span>Thanks for doing business with us!</span>

        </div>
        
        <div class="invoice-totalsdet">
            <div style="width: 50%; float: left;">
                <span>Subtotal</span><br>
            </div>
            <div style="width: 50%; float: right; text-align: right;">
                <span>{{ $total }} INR</span><br>
            </div>
            <div style="width: 50%; float: left;">
                <span>Discount</span><br>
            </div>
            <div style="width: 50%; float: right; text-align: right;">
                <span>{{ array_sum(array_column($orders, 'discount_amt')) }} INR</span><br>
            </div>
            <div style="width: 50%; float: left;">
                <span>SGST</span><br>
            </div>
            <div style="width: 50%; float: right; text-align: right;">
                <span>{{ array_sum(array_column($orders, 'gst_amt')) / 2 }} INR</span><br>
            </div>
            <div style="width: 50%; float: left;">
                <span>CGST</span><br>
            </div>
            <div style="width: 50%; float: right; text-align: right;">
                <span>{{ array_sum(array_column($orders, 'gst_amt')) / 2 }} INR</span><br>
            </div>
            <div style="width: 50%; float: left; background-color: #006666; color: #fff;">
                <span>Total Amount</span><br>
            </div>
            <div style="width: 50%; float: right; text-align: right; background-color: #006666; color: #fff;">
                <span>{{ round((int)$finaltotal, 2) }} INR</span><br>
            </div>
        </div>
    
        <div class="clearfix"></div>
        
        <br><br>
        <div class="invoice-finals">
            <div class="invoice-finals1">
                <span>
                    <h4>Pay To:</h4>
                    ****** <br>
                    Bank Account No. : ********<br>
                    Bank IFSC code : *************<br>
                    Account holder's name : *****
                </span>
            </div>
            <div class="invoice-finals2">
                <span>
                   For: Company Name<br><br>
                   Authorized Signatory<br>
                   <img src="" alt="sign">
               </span>
            </div>
        </div>
    
    </div>

</body>
</html>
