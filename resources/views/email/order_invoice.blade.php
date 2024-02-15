<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice</title>
    <style>
    body {
        background-color: #f6f6f6;
    }

    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 90%;
        max-width: 800px;
        margin: 0 auto;

    }

    td,
    th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }

    a {
        text-decoration: none;
    }

    div {
        background-color: #ffffff;
        padding: 1.5rem;
        width: 90%;
        max-width: 800px;
        margin: 0 auto;
        height: fit-content;

    }
    </style>
</head>

<body>
    <div>
        <table>
            <tr>
                <td style="border: none; color: #8a858e">
                    Invoice ID: <strong>#{{ $order_info->order_id }}</strong>
                </td>
                <th style="border: none"></th>

                <td style="border: none; text-align: right">
                    <a href="{{ config('app.front_url') }}"
                        style="border: 1px solid #8a858e; padding: 5px; color: #8a858e">aranya.com.bd</a>
                </td>
            </tr>
        </table>
        <hr style="width: 90%;
        max-width: 800px;
        margin: 0 auto;" />
        <table>
            <tr>
                <td style="border: none"></td>
                <th style="border: none; text-align: center">
                    <img src="{{ asset('admin-assets/assets/img/aranya-logo-dark.png') }}" alt="Aranya Logo"
                        width="50px" />
                </th>

                <td style="border: none; text-align: right"></td>
            </tr>
        </table>
        <hr style="width: 90%;
        max-width: 800px;
        margin: 0 auto;" />
        <table>
            <tr>
                <td style="border: none; color: #8a858e">
                    To: <strong style="color: #3092cb">{{ $order_info->user_billing_info->full_name }}</strong>
                </td>
                <th style="border: none"></th>

                <td style="border: none; text-align: right; color: #8a858e">Invoice</td>
            </tr>
            <tr style="background-color: #fff">
                <td style="border: none; color: #8a858e">{{ $order_info->user_billing_info->street_address }},
                    {{ $order_info->user_billing_info->city }}</td>
                <td style="border: none"></td>

                {{-- <td style="border: none; text-align: right; color: #8a858e">
          ID: #{{ $order_info->id }}
                </td> --}}
            </tr>
            <tr>
                <td style="border: none; color: #8a858e">{{ $order_info->user_billing_info->country }}</td>
                <td style="border: none"></td>

                <td style="border: none; text-align: right; color: #8a858e">
                    Order Date: {{ $order_info->order_date }}
                </td>
            </tr>
            <tr style="background-color: #fff">
                <td style="border: none; color: #8a858e">{{ $order_info->user_billing_info->phone }}</td>
                <td style="border: none"></td>

                <td style="border: none; text-align: right; color: #8a858e">
                    Status:
                    <span style="
              background-color: orange;
              padding: 5px;
              border-radius: 10px;
              color: black;
            ">{{ $order_info->payment_status == 1 ? 'Paid' : 'Unpaid'}}</span>
                </td>
            </tr>
        </table>

        <table style="margin-top: 2rem">
            <tr style="background-color: #5b93b4; border: none">
                <th style="border: none; color: #ffffff">SL</th>
                <th style="border: none; color: #ffffff">Product</th>
                <th style="border: none; color: #ffffff">SKU</th>
                <th style="border: none; color: #ffffff">VAT</th>
                <th style="border: none; color: #ffffff">VAT AMOUNT</th>
                <th style="border: none; color: #ffffff">Qty</th>
                <th style="border: none; color: #ffffff">Unit Price</th>
                <th style="border: none; color: #ffffff ;text-align: right;">Amount</th>
            </tr>
            @foreach($order_info->order_details as $key => $value)
            <tr>
                <td style="@if($key%2 != 0) border: none @endif">{{ $key+1 }}</td>
                <td style="@if($key%2 != 0) border: none @endif">{{ $value->product->product_name }}</td>
                <td style="@if($key%2 != 0) border: none @endif">{{ $value->item_sku}}</td>
                <td style="@if($key%2 != 0) border: none @endif">{{ $value->vat_rate}}%</td>
                <td style="@if($key%2 != 0) border: none @endif">{{ $value->charge_vat_amount}}</td>
                <td style="@if($key%2 != 0) border: none @endif">{{ $value->quantity }}</td>
                <td style="@if($key%2 != 0) border: none @endif">{{ $value->charge_selling_price }}</td>
                <td style="@if($key%2 != 0) border: none @endif ;text-align: right">
                    {{ $value->total_charge_selling_price }}</td>
            </tr>
            @endforeach
        </table>
        <table style="margin-top: 1rem;">
            <tr>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <!-- <td style="border: none; color: #8a858e">
          To: <strong style="color: #3092cb">Kabira</strong>
        </td> -->
                <td style="
            border: none;
            color: #8a858e;
            /* background-color: red; */
            text-align: right;
          ">
                    SubTotal : <strong>{{ $order_info->charge_total_price }}</strong>
                </td>

                <!-- <td style="border: none; text-align: right; color: #8a858e">invoice</td> -->
            </tr>
            <tr style="background-color: #fff">
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <!-- <td style="border: none; color: #8a858e">
          To: <strong style="color: #3092cb">Kabira</strong>
        </td> -->
                <td style="
            border: none;
            color: #8a858e;

            text-align: right;
          ">
                    VAT : <strong>{{ $order_info->charge_vat_amount }}</strong>
                </td>

                <!-- <td style="border: none; text-align: right; color: #8a858e">invoice</td> -->
            </tr>
            <tr>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <!-- <td style="border: none; color: #8a858e">
          To: <strong style="color: #3092cb">Kabira</strong>
        </td> -->
                <td style="
            border: none;
            color: #8a858e;
            /* background-color: red; */
            text-align: right;
          ">
                    Shipping Charge : <strong>{{ $order_info->charge_shipping_amount }}</strong>
                </td>

                <!-- <td style="border: none; text-align: right; color: #8a858e">invoice</td> -->
            </tr>
            <tr style="background-color: #fff">
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <th style="border: none"></th>
                <!-- <td style="border: none; color: #8a858e">
          To: <strong style="color: #3092cb">Kabira</strong>
        </td> -->
                <td style="
            border: none;
            color: #8a858e;
            /* background-color: red; */
            text-align: right;
          ">
                    Total Amount :
                    <strong>{{ $order_info->charge_total_price + $order_info->charge_vat_amount + $order_info->charge_shipping_amount }}</strong>
                </td>

                <!-- <td style="border: none; text-align: right; color: #8a858e">invoice</td> -->
            </tr>
        </table>
    </div>
</body>

</html>
