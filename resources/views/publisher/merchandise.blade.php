<html>

<head>
    @include('layouts.import')
    <title>Bandung Choral Society | {{ $merchandise->name }}</title>
</head>

<body>
    @include('layouts.header')

    <div class="resp-page-section left-flex no-first penerbit-detail">
        <div class="section-row w-100 mobile-w-full mx-m mobile-col">
            <div class="col-35 mobile-w-full">
                <div class="breadcrumb">
                    <b>Publisher</b> / Merchandise / {{ $merchandise->name }}
                </div>
                @php
                    $images = explode(',', $merchandise->photo);
                @endphp

                @if (count($images) > 0)
                    {{-- Gambar utama --}}
                    <img class="prod-img-idx"
                        src="{{ file_exists('public/' . $images[0]) && $images[0] ? asset('public/' . $images[0]) : asset('assets/images/favicon.png') }}" />

                    @if (count($images) > 1)
                        {{-- Carousel untuk gambar lainnya jika ada lebih dari satu gambar --}}
                        <div class="prod-img-list owl-carousel">
                            @foreach ($images as $index => $image)
                                @if ($index > 0)
                                    {{-- Skip gambar pertama karena sudah ditampilkan sebagai gambar utama --}}
                                    <img
                                        src="{{ file_exists('public/' . $image) && $image ? asset('public/' . $image) : asset('assets/images/favicon.png') }}" />
                                @endif
                            @endforeach
                        </div>
                    @endif
                @else
                    {{-- Gambar placeholder jika tidak ada gambar yang tersedia --}}
                    <img class="prod-img-idx" src="{{ asset('assets/images/placeholder.png') }}" />
                @endif

            </div>
            <div class="col-65 mobile-w-full">
                <div class="prod-name">{{ $merchandise->name }}</div>
                <div class="row flex-end"><b>Rp {{ number_format($merchandise->harga, 0, ',', '.') }}</b></div>
                <div class="prod-desc mt-s">{{ $merchandise->deskripsi }}</div>
                @php
                    $item = $merchandise->details->first();
                @endphp
                {{-- @foreach ($merchandise->details as $item) --}}
                <!-- Bagian Size -->

                @if($merchandise->details->where('size','<>','')->count() > 0)
                <label class="mt-30" for="size"><b>Size</b></label><br>
                <div class="row gap-10 mt-10 mb-l">
                    @foreach ($merchandise->details as $detail)
                        @if($detail->size != '')
                            <input class="radio-custom" type="radio" id="size{{ $detail->id }}" name="size"
                                value="{{ $detail->size }}">
                            <label class="radio-label" for="size{{ $detail->id }}">{{ $detail->size }}</label>
                        @endif
                    @endforeach
                </div>
                @endif

                @if($merchandise->details->where('color','<>','')->count() > 0)
                <label class="mt-30" for="color"><b>Color</b></label><br>
                <div class="row gap-10 mt-10">
                    @foreach ($merchandise->details as $detail)
                        @if($detail->color != '')
                            <input class="radio-custom" type="radio" id="color{{ $detail->id }}" name="color"
                                value="{{ $detail->color }}">
                            <label class="radio-label" for="color{{ $detail->id }}">{{ $detail->color }}</label>
                        @endif
                    @endforeach
                </div>
                @endif

                <div class="section-row max-width align-items-center justify-content-start mt-m">

                    <div class="col mt-m">
                        <label for="color"><b>Quantity</b></label><br>
                        <div class="qty-group">
                            <button>-</button>
                            <input type="number" id="qty-1" value="0" min="0" />
                            <button>+</button>
                        </div>
                        <input type="hidden" id="stock" value="{{$merchandise->stok}}" />
                        <div class="qty-hint">@if($merchandise->stok <= 5) <font style="color:red"> @endif Stock: {{$merchandise->stok}} @if($merchandise->stok <= 5) </font> @endif</div>
                    </div>

                    <div class="button-cart w-fit">
                        <?php
                        $id = [];
                        foreach ($merchandise->details as $detail) {
                            array_push($id, $detail->id);
                        }
                        ?>
                        @if(!Auth::guard('customer')->check())
                            <button class="button-add-cart"
                                onclick="handleLogin()">Add
                                to cart</button>
                        @else
                        <button class="button-add-cart" onclick="handleAddToCart({{ $merchandise->id }})" @if($merchandise->stok<=0) disabled style="background-color:#D3D3D3 !important" @endif>Add
                            to cart</button>
                        @endif
                    </div>

                </div>
                {{-- @endforeach --}}
            </div>
        </div>

        <div class="section-col" style="margin-bottom: 60px !important;">
            <div class="section-row left-flex max-width align-items-center justify-content-center mt-m">
                <div class="section-title">Recommendation</div>
                <div class="filter-group" onclick="javascript:location.href='{{ route('publisher') }}?t=merchandise'">
                    <div class="filter-icon">See More <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </div>
            <div class="koleksi-container">
                @foreach ($rekomendasi as $r)
                    @php($ex = explode(',', $r->photo))
                    <a class="tab-koleksi-link" href="{{ route('merchandise.detail', ['name' => rawurlencode($r->name)]) }}">
                        <img
                            src="@if ($r->photo) {{ file_exists('public/' . $ex[0]) && $ex[0] ? asset('public/' . $ex[0]) : asset('assets/images/favicon.png') }} @else {{ asset('assets/images/favicon.png') }} @endif" />
                        <div class="tab-koleksi-title">{{ $r->name }}</div>
                        <div class="tab-koleksi-sub"></div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @include('layouts.footer')
</body>

</html>

<script>
    function handleLogin(){
        const popuplengkapilogin = document.getElementById('lengkapi-login');
        popuplengkapilogin.classList.add('active');
    }

    function handleAddToCart(productId) {
        // Ambil size dan color yang dipilih
        const size = document.querySelector('input[name="size"]:checked') ? document.querySelector(
            'input[name="size"]:checked').value : null;
        const color = document.querySelector('input[name="color"]:checked') ? document.querySelector(
            'input[name="color"]:checked').value : null;

        // Ambil qty dan min dari produk tersebut
        const qty = parseInt(document.getElementById('qty-1').value);
        // const min = parseInt(document.getElementById('min-1').value);

        // Cek jika qty lebih besar dari 0 dan memenuhi min order, dan pastikan size dan color terpilih
        if(<?=$merchandise->details->where('color','<>','')->count()?> > 0 || <?=$merchandise->details->where('size','<>','')->count()?> > 0){
            if(<?=$merchandise->details->where('color','<>','')->count()?> > 0 && <?=$merchandise->details->where('size','<>','')->count()?> == 0){
                if(!color){
                    Swal.fire({
                        icon: 'error',
                        title: 'Please select color.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }else{
                if (qty > 0 && qty <= parseInt($('#stock').val())) {
                    // Siapkan data untuk dikirim ke server
                    let data = {
                        productId: productId,
                        quantity: qty,
                        size: size,
                        color: color,
                        merchandise: true,
                        _token: "{{ csrf_token() }}" // Sesuaikan dengan cara Anda mengirim CSRF token
                    };

                    // Proses pengiriman data ke server
                    fetch("{{ route('add.to.cart') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Success:', data);
                            if (data.status === 'login_required') {
                                // Tampilkan modal login jika perlu
                                const popuplengkapilogin = document.getElementById('lengkapi-login');
                                popuplengkapilogin.classList.add('active');
                            } else if (data.message === "Product added to cart successfully!") {
                                // Tampilkan pesan sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            // Tampilkan pesan error jika terjadi
                        });
                } else {
                    // Tampilkan pesan error jika kuantitas kurang dari minimum atau size dan color tidak dipilih
                    let message =
                        'Please make sure you have selected size and color, and the quantity meets the minimum order requirement.';
                    if (qty > parseInt($('#stock').val())) {
                        message = 'Insufficient Stock';
                    }else if(qty <= 0){
                        message = 'Please add at least one item to your cart.';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            }
            }else if(<?=$merchandise->details->where('color','<>','')->count()?> == 0 && <?=$merchandise->details->where('size','<>','')->count()?> > 0){
                if(!size){
                    Swal.fire({
                        icon: 'error',
                        title: 'Please select size.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }else{
                if (qty > 0 && qty <= parseInt($('#stock').val())) {
                    // Siapkan data untuk dikirim ke server
                    let data = {
                        productId: productId,
                        quantity: qty,
                        size: size,
                        color: color,
                        merchandise: true,
                        _token: "{{ csrf_token() }}" // Sesuaikan dengan cara Anda mengirim CSRF token
                    };

                    // Proses pengiriman data ke server
                    fetch("{{ route('add.to.cart') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Success:', data);
                            if (data.status === 'login_required') {
                                // Tampilkan modal login jika perlu
                                const popuplengkapilogin = document.getElementById('lengkapi-login');
                                popuplengkapilogin.classList.add('active');
                            } else if (data.message === "Product added to cart successfully!") {
                                // Tampilkan pesan sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            // Tampilkan pesan error jika terjadi
                        });
                } else {
                    // Tampilkan pesan error jika kuantitas kurang dari minimum atau size dan color tidak dipilih
                    let message =
                        'Please make sure you have selected size and color, and the quantity meets the minimum order requirement.';
                    if (qty > parseInt($('#stock').val())) {
                        message = 'Insufficient Stock';
                    }else if(qty <= 0){
                        message = 'Please add at least one item to your cart.';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            }
            }else if(<?=$merchandise->details->where('color','<>','')->count()?> > 0 && <?=$merchandise->details->where('size','<>','')->count()?> > 0){
                if (!size || !color) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Please select both size and color.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }else{
                if (qty > 0 && qty <= parseInt($('#stock').val())) {
                    // Siapkan data untuk dikirim ke server
                    let data = {
                        productId: productId,
                        quantity: qty,
                        size: size,
                        color: color,
                        merchandise: true,
                        _token: "{{ csrf_token() }}" // Sesuaikan dengan cara Anda mengirim CSRF token
                    };

                    // Proses pengiriman data ke server
                    fetch("{{ route('add.to.cart') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Success:', data);
                            if (data.status === 'login_required') {
                                // Tampilkan modal login jika perlu
                                const popuplengkapilogin = document.getElementById('lengkapi-login');
                                popuplengkapilogin.classList.add('active');
                            } else if (data.message === "Product added to cart successfully!") {
                                // Tampilkan pesan sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            // Tampilkan pesan error jika terjadi
                        });
                } else {
                    // Tampilkan pesan error jika kuantitas kurang dari minimum atau size dan color tidak dipilih
                    let message =
                        'Please make sure you have selected size and color, and the quantity meets the minimum order requirement.';
                    if (qty > parseInt($('#stock').val())) {
                        message = 'Insufficient Stock';
                    }else if(qty <= 0){
                        message = 'Please add at least one item to your cart.';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            }
            }
            
        }else{
            if (qty > 0 && qty <= parseInt($('#stock').val())) {
                // Siapkan data untuk dikirim ke server
                let data = {
                    productId: productId,
                    quantity: qty,
                    size: size,
                    color: color,
                    merchandise: true,
                    _token: "{{ csrf_token() }}" // Sesuaikan dengan cara Anda mengirim CSRF token
                };

                // Proses pengiriman data ke server
                fetch("{{ route('add.to.cart') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                        if (data.status === 'login_required') {
                            // Tampilkan modal login jika perlu
                            const popuplengkapilogin = document.getElementById('lengkapi-login');
                            popuplengkapilogin.classList.add('active');
                        } else if (data.message === "Product added to cart successfully!") {
                            // Tampilkan pesan sukses
                            Swal.fire({
                                icon: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        // Tampilkan pesan error jika terjadi
                    });
            } else {
                // Tampilkan pesan error jika kuantitas kurang dari minimum atau size dan color tidak dipilih
                let message =
                    'Please make sure you have selected size and color, and the quantity meets the minimum order requirement.';
                if (qty > parseInt($('#stock').val())) {
                    message = 'Insufficient Stock';
                }else if(qty <= 0){
                    message = 'Please add at least one item to your cart.';
                }

                Swal.fire({
                    icon: 'error',
                    title: message,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }

        
    }


    function hideLoginPopup() {
        document.getElementById('lengkapi-login').style.display = 'none';
    }
</script>
