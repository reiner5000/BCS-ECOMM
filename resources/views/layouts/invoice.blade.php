<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data->no_invoice }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            /* background: #ECECEC; */
            margin: 0;
            padding: 0;
        }

        .invoice-box {
            /* aspect-ratio: 210/297; */
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, .15); */
            font-size: 14px;
            line-height: 24px;
            background: #FFF;
        }

        h2 {
            text-align: center;
            text-transform: uppercase;
            font-weight: 600;
        }


        table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        table td,
        table th {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            border-bottom: 2px solid #000;
            border-top: 2px solid #000;
            font-weight: bold;
        }

        .total-section {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .total-section p {
            text-align: right;
        }

        .footer-info {
            text-align: left;
            margin-top: 20px;
            font-size: 12px;
        }

        .invoice-logo {
            width: 150px;
        }

        .invoice-header {
            font-size: 42px;
            font-weight: 600;
        }

        .bill-info-row {
            display: flex;
        }

        .row {
            display: flex;
        }

        .w-fit {
            width: fit-content;
        }

        .col {
            display: flex;
            flex-direction: column;
        }

        .bold {
            font-weight: 600;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }

        .w-50 {
            width: 50%;
        }

        .ml-s {
            margin-left: 8px;
        }

        .ml-m {
            margin-left: 16px;
        }

        .text-right {
            text-align: end;
        }

        .mt-m {
            margin-top: 16px;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .invoice-box,
            .invoice-box * {
                visibility: visible;
            }

            .invoice-box {
                position: absolute;
                left: 0;
                top: 0;
                margin: 0;
                box-shadow: none;
                border: initial;
            }
        }
    </style>

</head>

<body>
    <div class="invoice-box">
        <div class="row justify-content-between align-items-center">
            <img class="invoice-logo" src="{{ asset('assets/images/bcs_logo.png') }}" style="filter: invert(100%);" />
            <div class="invoice-header">INVOICE</div>
        </div>

        <div class="bill-info-row mt-m">
            <div class="col w-50">
                <div class="text bold">Bill To</div>
                <div class="row w-fit">
                    <div class="col w-fit">
                        <div class="text row justify-content-between">
                            <div>Customer</div>
                            <div class="bold ml-m">:</div>
                        </div>
                        <div class="text row justify-content-between">
                            <div>Email</div>
                            <div class="bold">:</div>
                        </div>
                        <div class="text row justify-content-between">
                            <div>Address</div>
                            <div class="bold">:</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text bold">{{ $data->customer->name }}</div>
                        <div class="text bold">{{ $data->customer->email }}</div>
                        <div class="text"><b>{{ $data->shipment->nama_penerima ?? '-' }}
                            @if(isset($data->shipment->nama_penerima))
                            ({{ $data->shipment->phone_number }})</b><br />{{ $data->shipment->informasi_tambahan }},
                            {{ $data->shipment->kecamatan }},
                            {{ $data->shipment->kota }},
                            {{ $data->shipment->provinsi }},
                            {{ $data->shipment->kode_pos }},
                            {{ $data->shipment->negara }}
                            @endif
                               </div>
                    </div>
                </div>
            </div>
            <div class= "col w-50">
                <div class="row w-fit">
                    <div class="col w-fit">
                        <div class="text row justify-content-between">
                            <div class="bold">Invoice<font style="color: rgba(0, 0, 0, 0);">_</font>Number</div>
                            <div class="bold ml-m">:</div>
                        </div>
                        <div class="text row justify-content-between">
                            <div class="bold">Date</div>
                            <div class="bold">:</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text">{{ $data->no_invoice }}</div>
                        <div class="text bold">{{ \Carbon\Carbon::parse($data->date)->format('j M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <table class="mt-m">
            <thead>
                <tr>
                    <th>Product Info</th>
                    <th class="text-right">QTY</th>
                    <th class="text-right">UNIT PRICE</th>
                    <th class="text-right">TOTAL PRICE</th>
                </tr>
            </thead>
            <tbody>
                @php($no = 0)
                @php($comp_fee = 0)
                @php($comp_prod = 0)
                @foreach ($data->items as $d)
                    @if ($d->partitur_id == 0)
                        <tr>
                            <td>{{ $d->merchandise->name }}<br>
                            @if($d->size!= ''  && $d->color!= '')
                                Variation: {{ $d->size }} | {{ $d->color }}
                            @elseif($d->size!= '' )
                                Variation: {{ $d->size }}
                            @elseif($d->color!= '' )
                                Variation: {{ $d->color }}
                            @endif
                            </td>
                            <td class="text-right">{{ $d->quantity }}</td>
                            <td class="text-right">Rp {{ number_format($d->total_harga / $d->quantity, 0, ',', '.') }}
                            </td>
                            <td class="text-right">Rp
                                {{ number_format($d->subtotal - $d->competition_fee, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $d->partiturDetail->name }}<br>Choir: {{ $d->choir->name ?? '-' }}<br>For
                                Competition: {{ $d->for_competition == 1 ? 'yes' : 'no' }}
                            </td>
                            <td class="text-right">{{ $d->quantity }}</td>
                            <td class="text-right">Rp {{ number_format($d->total_harga / $d->quantity, 0, ',', '.') }}
                            </td>
                            <td class="text-right">Rp
                                {{ number_format($d->subtotal - $d->competition_fee, 0, ',', '.') }}</td>
                        </tr>
                        @if ($d->competition_fee > 0)
                            @php($comp_fee += $d->competition_fee)
                            @php($comp_prod++)
                        @endif
                    @endif
                    @php($no++)
                @endforeach
            </tbody>
        </table>
        <div class="total-section">
            <div class="bill-info-row">
                <div class="col w-50"></div>
                <div class="col w-50">
                    <div class="row justify-content-between">
                        <div class="col w-fit">
                            <div class="text row justify-content-between">
                                <div class="bold">Total Price ({{ $no }} product<?= $no > 1 ? 's' : '' ?>)
                                    :</div>
                            </div>
                            <div class="text row justify-content-between">
                                <div>Shipment Fee :</div>
                            </div>
                            <div class="text row justify-content-between">
                                <div>Voucher :</div>
                            </div>
                            <div class="text row justify-content-between competition-hidden">
                                <div>Competition fee (1 product<?= $comp_prod > 1 ? 's' : '' ?>) :</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text text-right">Rp
                                {{ number_format($data->total - $data->shipment_fee - $comp_fee + $data->voucher, 0, ',', '.') }}
                            </div>
                            <div class="text text-right">Rp {{ number_format($data->shipment_fee, 0, ',', '.') }}</div>
                            <div class="text text-right">Rp {{ number_format($data->voucher, 0, ',', '.') }}</div>
                            <div class="text text-right">Rp {{ number_format($comp_fee, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="total-section">
            <div class="bill-info-row">
                <div class="col w-50"></div>
                <div class="col w-50">
                    <div class="row justify-content-between">
                        <div class="col w-fit">
                            <div class="text row justify-content-between">
                                <div class="bold">Total Bill</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text text-right">Rp {{ number_format($data->total, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bill-info-row">
            <div class="col w-50">
                <div class="row justify-content-between">
                    <div class="col w-fit">
                        <div class="text row justify-content-between">
                            <div class="">Courier:</div>
                        </div>
                        <div class="text row justify-content-between">
                            <div class="bold">
                                @if ($data->shipment_fee == 0)
                                    -
                                @else
                                    POS Indonesia
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col w-50">
                <div class="row justify-content-between">
                    <div class="col w-fit">
                        <div class="text row justify-content-between">
                            <div class="">Payment Method:</div>
                        </div>
                        <div class="text row justify-content-between">
                            <div class="bold">Midtrans</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-info">
            <div class="text">This invoice is valid and processed by computer.</div>
            <div class="text">Please contact : <br>
            <b>product@bandungchoral.com
            <br>+62 813-9555-1613</b><br>
            if you need assistance.</div>
        </div>
    </div>
</body>
<script>
    window.print();
</script>

</html>
