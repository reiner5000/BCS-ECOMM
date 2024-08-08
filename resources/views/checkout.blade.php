<html>

<head>
    @include('layouts.import')
    <title>Bandung Choral Society | Checkout</title>
</head>

<body>
    @include('layouts.header')
    


    <div class="resp-page-section left-flex no-first checkout-page" style="margin-bottom: 80px;">
        <div class="section-row" style="width: 100%; justify-content: space-between;">
            <div class="section-title">Checkout</div>
            <button class="btn-link" style="margin-top: 46px;" onclick="history.go(-1)">Back to basket</button>
        </div>

        <div class="section-row w-100 mobile-col">
            <div class="section-col w-50 mobile-w-full">
                @if ($shipment)
                    <div class="alamat-group">
                        <div class="alamat-title">
                            @if ($shipment->is_default == 1)
                                <div class="alamat-tag">Main Address</div>
                            @endif
                            <div style="width: max-contentz; display: flex; margin-left: auto; gap: 10px;">
                                <button class="alamat-change"><i class="fa-solid fa-arrows-rotate"></i>Change</button>
                                <button class="alamat-show-popup" target-popup="edit-alamat"
                                    alamat-id="{{ $shipment->id }}" alamat-nama="{{ $shipment->nama_penerima }}"
                                    alamat-telp="{{ $shipment->phone_number }}" alamat-negara="{{ $shipment->negara }}"
                                    alamat-provinsi="{{ $shipment->provinsi }}" alamat-kota="{{ $shipment->kota }}"
                                    alamat-kecamatan="{{ $shipment->kecamatan }}"
                                    alamat-kode-pos="{{ $shipment->kode_pos }}"
                                    alamat-informasi-tambahan="{{ $shipment->informasi_tambahan }}"
                                    alamat-detail-informasi-tambahan="{{ $shipment->detail_informasi_tambahan }}"></i><i
                                        class="fa-solid fa-pencil"></i> Edit</button>
                            </div>
                        </div>
                        <div class="alamat-penerima">{{ $shipment->nama_penerima }}</div>
                        <div class="alamat-telepon">{{ $shipment->phone_number }}</div>
                        <div class="alamat-title mt-20">{{ $shipment->detail_informasi_tambahan }}</div>
                        <div class="alamat-desc"> {{ $shipment->informasi_tambahan }},
                            {{ $shipment->kecamatan }},
                            {{ $shipment->kota }},
                            {{ $shipment->provinsi }},
                            {{ $shipment->kode_pos }},
                            {{ $shipment->negara }}
                        </div>
                    </div>
                @else
                    <div class="alamat-group">
                        <div class="alamat-penerima" style="text-align: center;">You don't have an address yet</div>
                        <div class="alamat-telepon" style="text-align: center;">Please add your address first</div>
                        <div class="alamat-desc" style="display: flex;justify-content: center;align-items: center; ">
                            <button class="btn-white btn popup-trigger" target-popup="right-alamat">Add Address</button>
                        </div>
                    </div>
                @endif

                <div class="cart-list">
                    @php
                        $totalMaster = 0;
                        $totalCompetition = 0;
                        $productCompetition = 0;
                    @endphp
                    @foreach ($cartItems as $cartItem)
                        @php
                            $subtotal = $cartItem->harga * $cartItem->total_quantity;
                            $totalMaster += $subtotal;
                        @endphp
                        <div class="cart-detail">
                            <div class="cart-detail-img">
                                <img src="{{ file_exists('public/' . $cartItem->file_image) && $cartItem->file_image ? asset('public/' . $cartItem->file_image) : asset('assets/images/favicon.png') }}"
                                    alt="{{ $cartItem->file_image }}" />
                            </div>
                            <div class="cart-detail-desc" style="margin-top: -16px;">
                                <div class="modal-row mt-10">
                                    <div class="cart-detail-title">{{ $cartItem->name }}</div>
                                </div>
                                <div class="modal-row" style="margin-top: 0; margin-bottom: 10px;">
                                    <div class="cart-detail-voicetype">
                                        {{ ucfirst($cartItem->file_type) }}</div>
                                </div>
                                <div class="cart-detail-price">Rp
                                    {{ number_format($cartItem->harga * $cartItem->total_quantity, 0, ',', '.') }}
                                </div>
                                <div class="cart-detail-price">{{ $cartItem->total_quantity }} Pc(s)</div>
                                @if ($cartItem->competition == 1 && $cartItem->merchandise_id == 0)
                                    <div class="cart-detail-voicetype">For Competition</div>
                                    <div class="cart-detail-price">Rp
                                        {{ number_format(50000, 0, ',', '.') }}</div>
                                @endif
                                <?php
                                if ($cartItem->competition == 1 && $cartItem->merchandise_id == 0) {
                                    $productCompetition++;
                                    $totalCompetition += $cartItem->competition == 1 ? 50000 : 0;
                                }
                                ?>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="section-col w-50 mobile-w-full">
                <form action="{{ route('checkout.pay') }}" method="POST">
                    @csrf
                    @foreach ($cartItems as $cartItem)
                        {{-- @dd($cartItem); --}}
                        <input type="hidden" name="size[]" value="{{ $cartItem->size }}">
                        <input type="hidden" name="color[]" value="{{ $cartItem->color }}">
                        <input type="hidden" name="competition_status[]" value="{{ $cartItem->competition }}">
                        <input type="hidden" name="total_quantity[]" value="{{ $cartItem->total_quantity }}">
                        <input type="hidden" name="harga[]" value="{{ $cartItem->harga }}">
                        <input type="hidden" name="competition[]" value="{{ $cartItem->competition }}">
                        <input type="hidden" name="competition_fee[]" value="50000">
                        <input type="hidden" name="choir_id[]" value="{{ $cartItem->choir_id }}">
                        <input type="hidden" name="partiturdet_id[]" value="{{ $cartItem->partiturdet_id }}">
                        <input type="hidden" name="merchandise_id[]" value="{{ $cartItem->merchandise_id }}">
                        <input type="hidden" name="cartItems[]" value="{{ $cartItem->id }}">
                        <input type="hidden" name="total"
                            value="{{ (int) $totalMaster + (int) $shippingCost + (int) $totalCompetition }}">
                    @endforeach
                    <button type="submit" class="login-button btn-white w-100"
                        @if (!$shipment) disabled style="" @endif @if ($cartItems->count() == 0) disabled style="" @endif>Pay</button>
                    <input class="login-with-button w-100" name="voucher_name" placeholder="Enter Voucher Code"
                        onkeyup="checkVoucher()"></button>

                    <div class="ringkasan-belanja">
                        <div class="ringkasan-title">Summary</div>
                        @if ($cartItems->isNotEmpty())
                            {{-- Ambil item pertama dari koleksi --}}
                            @php
                                $firstCartItem = $cartItems->first();
                            @endphp

                            <div class="ringkasan-row">
                                <div class="ringkasan-value">Total ({{ count($cartItems) }}
                                    Product<?= count($cartItems) > 1 ? 's' : '' ?>)</div>
                                <div class="ringkasan-value">Rp
                                    {{ number_format($totalMaster, 0, ',', '.') }}
                                    <input type="hidden" value="{{ $totalMaster }}" name="subtotal">
                                </div>
                            </div>
                            @if ($shippingCost != 0)
                                @php($shippingCost += 5000)
                                <div class="ringkasan-row">
                                    <div class="ringkasan-value">Shipping costs</div>
                                    <div class="ringkasan-value">Rp {{ number_format($shippingCost, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endif
                            <input type="hidden" value="{{ $shippingCost }}" name="shippingcost">
                            @if ($productCompetition > 0)
                                <div class="ringkasan-row">
                                    <div class="ringkasan-value">Competition Fees ({{ $productCompetition }}
                                        Product<?= $productCompetition > 1 ? 's' : '' ?>)
                                    </div>
                                    <div class="ringkasan-value">Rp
                                        {{ number_format($totalCompetition, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endif
                            <input type="hidden" value="{{ $totalCompetition }}" name="competition">
                            <div class="ringkasan-row voucher" style="display:none;">
                                <div class="ringkasan-value">Voucher Discount</div>
                                <div class="ringkasan-value" style="color:red">-Rp <span class="disc_value">0</span>
                                </div>
                            </div>
                            <input type="hidden" value="0" name="discount" id="discount">
                            <div class="ringkasan-row" style="border-bottom:1px solid grey;"></div>
                            <div class="ringkasan-row">
                                <div class="cart-detail-title">Total</div>
                                <div class="cart-detail-title">Rp <span class="total">
                                        {{ number_format($totalMaster + $shippingCost + $totalCompetition, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <input type="hidden" value="{{ $totalMaster + $shippingCost + $totalCompetition }}"
                                name="total_old" id="total_old">
                            <input type="hidden" value="{{ $totalMaster + $shippingCost + $totalCompetition }}"
                                name="total" id="total">
                        @endif
                        <input type="hidden" value="{{ $shippingId }}" name="shipping_id">
                </form>
            </div>

            <div class="ringkasan-info"> <i class="fa-solid fa-circle-info"></i> Payment can only be made via
                Midtrans</div>
            <div class="ringkasan-info competition-hidden"><i class="fa-solid fa-circle-info"></i> Additional fee will be charged to
                the buyer if the use of product is for competition activities</div>
        </div>
    </div>
    </div>
   
    @include('layouts.footer')

    @if($cartItems->count() == 0)
    <script>
        const popup_lengkapi_barang = document.getElementById('lengkapi-barang');
        popup_lengkapi_barang.classList.toggle('active');
    </script>
    @endif
    
</body>
<script>
    function checkVoucher() {
        var input = document.querySelector('.login-with-button').value;
        fetch('{{ route('check-voucher') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    voucher: input
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    $('.voucher').show();
                } else {
                    $('.voucher').hide();
                }
                $('#discount').val(data.disc);
                $('.disc_value').html(new Intl.NumberFormat('id-ID').format(data.disc));

                let total_akhir = parseInt($('#total_old').val()) - parseInt(data.disc);
                $('#total').val(total_akhir);

                $('.total').html(new Intl.NumberFormat('id-ID').format(total_akhir));
            })
            .catch(error => console.error('Error:', error));
    }

    const changeAddress = document.querySelector('.alamat-change');
    changeAddress.addEventListener('click', (event)=>{
        location.href="{{ route('change-shipping') }}";
    }); 
</script>

</html>
