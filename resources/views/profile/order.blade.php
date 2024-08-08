<html>

<head>
    @include('layouts.import')
    <title>Bandung Choral Society | My Order</title>
</head>

<body>
    @include('layouts.header')

    <div class="resp-page-section left-flex no-first alamat-page" style="margin-bottom: 60px !important;">


        <div class="section-col">

            <div class="section-row button-row">
                <div class="card-newtitle">Order {{ $data->no_invoice }}</div>
                <button class="btn btn-black" onclick="javascript:location.href='{{ route('profile') }}'">Back</button>
            </div>

            <div class="section-col w-100">
                <div class="history-card">
                    <div class="col w-100">
                        <div class="row align-items-center gap-20">
                            <div class="card-smalltitle fw-600">Invoice Number <div>:</div></div>
                            <div class="card-smalltitle fw-600"
                                style="color:#4196DF !important;text;text-decoration: underline;"><a
                                    href='{{ route('invoice', ['id' => $data->id]) }}' target="_blank"
                                    style="color:#4196DF !important;">{{ $data->no_invoice }}</a>
                            </div>
                        </div>

                        <div class="row align-items-center gap-20">
                            <div class="card-smalltitle fw-600">Receipt Number <div>:</div></div>
                            <div class="card-smalltitle fw-600">{{ $data->no_resi ?? '-'}}</div>
                        </div>

                        <div class="row align-items-center gap-20">
                            <div class="card-smalltitle fw-600">Purchase Date <div>:</div></div>
                            <div class="card-smalltitle fw-600">{{ \Carbon\Carbon::parse($data->date)->format('j M Y') }}</div>
                        </div>
                        @php($comp_fee = 0)
                        @foreach ($data->items as $d)
                            <br>
                            @if ($d->partitur_id == 0)
                                <div class="row gap-20 border-bottom-mobile">
                                    @php($ex = explode(',', $d->merchandise->photo))
                                    <img class="history-img"
                                        src="{{ file_exists('public/' . $ex[0]) && $ex[0] != '' ? asset('public/' . $ex[0]) : asset('assets/images/favicon.png') }}" />
                                    <div class="col">
                                        <div class="card-newtitle">{{ $d->merchandise->name }}</div>
                                        <div class="card-muted">
                                            {{ $d->quantity }} Item x Rp
                                            {{ number_format($d->total_harga / $d->quantity, 0, ',', '.') }}
                                            <br>
                                            @if($d->size!= ''  && $d->color!= '')
                                                {{ $d->size }} | {{ $d->color }}
                                            @elseif($d->size!= '' )
                                                {{ $d->size }}
                                            @elseif($d->color!= '' )
                                                {{ $d->color }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row gap-20">
                                    @php($ex = explode(',', $d->partiturDetail->partitur->file_image))
                                    <img class="history-img"
                                        src="{{ file_exists('public/' . $ex[0]) && $ex[0] != '' ? asset('public/' . $ex[0]) : asset('assets/images/favicon.png') }}" />
                                    <div class="col w-100">
                                        <div class="card-title w-100">{{ $d->partiturDetail->name }}</div>
                                        <div class="card-muted">
                                            {{ $d->quantity }} Item x Rp
                                            {{ number_format($d->total_harga / $d->quantity, 0, ',', '.') }}
                                            <br>
                                            {{ ucfirst($d->partiturDetail->file_type) }}
                                            @if ($d->for_competition == 1)
                                                @php($comp_fee += $d->competition_fee)
                                                <br>
                                                <span class="competition-hidden">For Competition</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($d->partiturDetail->file_type == 'softcopy')
                                    <div class="col mobile-hide">
                                        <?php
                                        $startDate = \Carbon\Carbon::parse($data->date);
                                        $endDate = $startDate->copy()->addDays(31);
                                        
                                        $remainingDays = \Carbon\Carbon::now()->diffInDays($endDate, false);
                                        $remain = max($remainingDays, 0);
                                        ?>
                                        <a class="order-detail-mobile" href="{{ $remain == 0 ? '#' : route('download-pdf', ['id' => $d->id]) }}"
                                            {{ $remain == 0 ? '' : 'download' }}>
                                            <div class="login-button btn-white custom-button-or"
                                                {{ $remain == 0 ? 'disabled' : '' }}>
                                                Download Document
                                            </div>
                                        </a>
                                        <div class="card-smalltitle">* <?php echo 'Remaining download time is ' . max($remain, 0) . ' days'; ?></div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col mobile-show w-100 mt-m border-bottom-mobile">
                                        <?php
                                        $startDate = \Carbon\Carbon::parse($data->date);
                                        $endDate = $startDate->copy()->addDays(31);
                                        
                                        $remainingDays = \Carbon\Carbon::now()->diffInDays($endDate, false);
                                        $remain = max($remainingDays, 0);
                                        ?>
                                        <a class="order-detail-mobilemt-m" href="{{ $remain == 0 ? '' : asset('public/' . $d->partiturDetail->partitur_ori) }}"
                                            download>
                                            <div class="login-button btn-white w-100 mt-m"
                                                {{ $remain == 0 ? 'disabled' : '' }}>
                                                Download Document
                                            </div>
                                        </a>
                                        <div class="card-subtitle">* <?php echo 'Remaining download time is ' . max($remain, 0) . ' days'; ?></div>
                                    </div>
                            @endif
                            <br>
                        @endforeach
                    </div>
                    <div class="col h-100 justify-content-space-between">
                        <div class="col align-items-end">
                            <div
                                class="history-status @if ($data->status == 2) success @elseif($data->status == 1) default @else warning @endif">
                                @if ($data->status == 2)
                                    Finished
                                @elseif($data->status == 1)
                                    In delivery
                                @else
                                    It's being packaged
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="invoice-container">
                                <div class="invoice-row">
                                    <span>Total Price</span>
                                    <span class="right-align">Rp
                                        {{ number_format($data->total - $data->shipment_fee - $comp_fee + $data->voucher, 0, ',', '.') }}</span>
                                </div>
                                <div class="invoice-row">
                                    <span>Shipping Costs</span>
                                    <span class="right-align">Rp
                                        {{ number_format($data->shipment_fee, 0, ',', '.') }}</span>
                                </div>
                                @if ($comp_fee > 0)
                                    <div class="invoice-row competition-hidden">
                                        <span>Competition Fees</span>
                                        <span class="right-align">Rp {{ number_format($comp_fee, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                @if ($data->voucher > 0)
                                    <div class="invoice-row">
                                        <span>Voucher Discount</span>
                                        <span class="right-align" style="color:red">-Rp
                                            {{ number_format($data->voucher, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="invoice-row">
                                    <hr>
                                </div>
                                <div class="invoice-row invoice-total">
                                    <span>Total</span>
                                    <span class="right-align">Rp
                                        {{ number_format($data->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer')
    <style>
        .history-card {
            height: auto !important;
        }

        .alamat-page .section-col {
            gap: 0 !important;
        }
    </style>
</body>

</html>