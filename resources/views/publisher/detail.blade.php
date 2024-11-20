<html>

<head>
    @include('layouts.import')
    <title>Bandung Choral Society | {{ $partitur->name }}</title>
</head>

<body>
    @include('layouts.header')

    <div class="resp-page-section left-flex no-first penerbit-detail">
        <div class="section-row w-100 mobile-w-full mobile-col">
            <div class="col-35 mobile-w-full">
                <div class="breadcrumb">
                    <b>Publisher</b> / Sheet Music / {{ $partitur->name }}
                </div>
                @php
                    $images = explode(',', $partitur->file_image);
                @endphp

                @if (count($images) > 0)
                    {{-- Gambar utama --}}
                    <img class="prod-img-idx {{ file_exists('public/' . $images[0]) && $images[0] ? '' : 'contain-img-remove' }}" src="{{ file_exists('public/' . $images[0]) && $images[0] ? asset('public/' . $images[0]) : asset('assets/images/favicon.png') }}" />

                    @if (count($images) > 1)
                        {{-- Carousel untuk gambar lainnya jika ada lebih dari satu gambar --}}
                        <div class="prod-img-list owl-carousel">
                            @foreach ($images as $index => $image)
                                @if ($index > 0)
                                    <img {{ file_exists('public/' . $image) && $image ? '' : 'class="contain-img-remove"' }} src="{{ file_exists('public/' . $image) && $image ? asset('public/' . $image) : asset('assets/images/favicon.png') }}" />
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
                <div class="prod-name">{{ $partitur->name }}</div>
                <div class="prod-desc">{{ $partitur->deskripsi }}</div>
                <table class="prod-showcase" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="20%">Preview</th>
                            <th width="50%">Description</th>
                            <th width="15%">Price</th>
                            <th width="15%">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partitur->details as $item)
                            <tr>
                                <td>
                                    <div class="prod-view mobile-col">
                                        @if($item->preview_audio != null && $item->preview_audio != '')
                                        <i class="fa-solid fa-compact-disc audio-show" target-popup="preview_partitur" partitur-file="{{$item->preview_audio}}" partitur-title="{{$item->name}} ({{$partitur->name}})"></i>
                                        @endif
                                        @if($item->preview_partitur != null && $item->preview_partitur != '')
                                        <i class="fa-solid fa-file-lines partitur-show" target-popup="preview_partitur" partitur-file="{{asset('public/'.$item->preview_partitur)}}" partitur-title="{{$item->name}} ({{$partitur->name}})"></i>
                                        @endif
                                        @if($item->preview_video != null && $item->preview_video != '')
                                        <i class="fa-solid fa-video video-show" target-popup="preview_partitur" partitur-file="{{$item->preview_video}}" partitur-title="{{$item->name}} ({{$partitur->name}})"></i>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <div class="prod-detail">
                                        <div class="prod-detail-name">{{ $item->name }}</div>
                                        <div class="prod-detail-type">Available in {{ $item->file_type }} form</div>
                                        <div class="prod-detail-desc">{{ $item->deskripsi }}</div>
                                    </div>
                                </td>

                                <td>
                                    <div class="prod-detail-price">Rp {{ number_format($item->harga,0,',','.') }}</div>
                                </td>

                                <td>
                                    <div class="qty-container">
                                        <div class="qty-group">
                                            <button>-</button>
                                            <input type="number" id="qty-{{ $item->id }}"
                                                value="{{ $item->minimum_order }}" min="0"/>
                                            <input type="hidden" id="min-{{ $item->id }}" value="{{ $item->minimum_order }}">
                                            <button>+</button>
                                        </div>
                                        <div class="qty-hint">min. order {{ $item->minimum_order }}</div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="prod-caution competition-hidden"><i class="fa-solid fa-circle-info"></i> There will be an additional charge for competition use of 50.000</div>

                <div class="button-cart">
                    <?php
                    $id = array();
                    foreach($partitur->details as $detail){
                        array_push($id, $detail->id);
                    }
                    ?>
                    @if($choirCount == 0)
                        @if(Auth::guard('customer')->check())
                            <button id="show-right-cart" target-popup="lengkapi-choir" class="button-add-cart popup-trigger" type="button">Add
                            to cart</button>
                        @else
                            <button class="button-add-cart"
                                onclick="handleLogin()">Add
                                to cart</button>
                        @endif
                    @else
                        <button class="button-add-cart"
                            onclick="handleAddToCart({{json_encode($id)}})">Add
                            to cart</button>
                    @endif
                </div>
            </div>
        </div>

        <div class="section-col" style="margin-bottom: 60px !important;">
            <div class="section-row left-flex max-width align-items-center justify-content-center mt-m">
                <div class="section-title">Recommendation</div>
                <div class="filter-group" onclick="javascript:location.href='{{ route('publisher') }}'">
                    <div class="filter-icon">See More <i class="fa-solid fa-arrow-right-long"></i></div>
                </div>
            </div>
            <div class="koleksi-container">
                @foreach ($rekomendasi as $r)
                    @php($ex = explode(',', $r->file_image))
                    <a class="tab-koleksi-link" href="{{ route('publisher.detail', ['name' => rawurlencode($r->name)]) }}">
                        <img @if ($r->file_image) {{ file_exists('public/' . $ex[0]) && $ex[0] ? '' : 'class=contain-img-remove' }} @else class="contain-img-remove" @endif src="@if ($r->file_image) {{ file_exists('public/' . $ex[0]) && $ex[0] ? asset('public/' . $ex[0]) : asset('assets/images/favicon.png') }} @else {{asset('assets/images/favicon.png')}} @endif" />
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
    // get total qty
    let totalQty = 0;
    let qtyKurang = 0;

    for (let index = 0; index < productId.length; index++) {
        const inputQty = document.getElementById('qty-' + productId[index]);
        const minQty = document.getElementById('min-' + productId[index]);
        const qtyValue = parseInt(inputQty.value);

        if (qtyValue > 0) { // Hanya periksa partitur dengan qty lebih dari 0
            totalQty += qtyValue;

            if (qtyValue < parseInt(minQty.value)) {
                qtyKurang++;
            }
        }
    }

    if (qtyKurang > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Qty Less Than Minimum Order',
            showConfirmButton: false,
            timer: 1500
        });
    } else if (totalQty == 0) {
        Swal.fire({
            icon: 'error',
            title: 'Select Items first',
            showConfirmButton: false,
            timer: 1500
        });
    } else {
        for (let index = 0; index < productId.length; index++) {
            const id = productId[index];
            const qty = parseInt(document.getElementById('qty-' + id).value);

            if (qty > 0) { // Hanya kirim data partitur dengan qty lebih dari 0
                let data = {
                    productId: id,
                    quantity: qty,
                    _token: "{{ csrf_token() }}"
                };

                fetch("{{ route('add.to.cart') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === "Product added to cart successfully!") {
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
                });
            }
        }
    }
}

    
    // function handleAddToCart(productId) {
    //     // get total qty
    //     let totalQty = 0;
    //     let qtyKurang = 0;
    //     for (let index = 0; index < productId.length; index++) {
    //         totalQty += parseInt(document.getElementById('qty-' + productId[index]).value);
    //         const inputQty = document.getElementById('qty-' + productId[index]);
    //         const minQty = document.getElementById('min-' + productId[index]);

    //         if(parseInt(inputQty.value) > 0 && parseInt(inputQty.value) < parseInt(minQty.value)){
    //             qtyKurang++;
    //         }
    //     }
        
    //     if(qtyKurang > 0){
    //         Swal.fire({
    //             icon: 'error',
    //             title: 'Qty Less Than Minimum Order',
    //             showConfirmButton: false,
    //             timer: 1500
    //         });
    //     }else if(totalQty == 0){
    //         // total qty kosong (pilih barang)
    //         Swal.fire({
    //             icon: 'error',
    //             title: 'Select Items first',
    //             showConfirmButton: false,
    //             timer: 1500
    //         });
    //     }else{
    //         // const popuplengkapilogin = document.getElementById('lengkapi-login');
    //         // popuplengkapilogin.classList.add('active');
    //         for (let index = 0; index < productId.length; index++) {
    //             const id = productId[index];
    //             const qty = document.getElementById('qty-' + productId[index]).value;
    //             const min = document.getElementById('min-' + productId[index]).value;
    //             if(qty > 0){
    //                 // Prepare data to be sent to the server
    //                 let data = {
    //                     productId: id,
    //                     quantity: qty,
    //                     // namapartitur: "{{ $partitur ? $partitur->name : '' }}",
    //                     _token: "{{ csrf_token() }}" // Laravel CSRF token
    //                 };
    //                 if(!@json(Auth::guard('customer')->check())){
    //                     const popuplengkapilogin = document.getElementById('lengkapi-login');
    //                     popuplengkapilogin.classList.add('active');
    //                 }else{
    //                     // Make an AJAX request to your Laravel backend
    //                     fetch("{{ route('add.to.cart') }}", {
    //                             method: "POST",
    //                             headers: {
    //                                 'Content-Type': 'application/json',
    //                             },
    //                             body: JSON.stringify(data)
    //                         })
    //                         .then(response => response.json())
    //                         .then(data => {
    //                             console.log('Success:', data);
    //                             if (data.status === 'login_required') {
    //                                 // Show the login modal here
    //                                 const popuplengkapilogin = document.getElementById('lengkapi-login');
    //                                 popuplengkapilogin.classList.add('active');
    //                             } else if (data.message === "Product added to cart successfully!") {
    //                                 // Open the modal or show a success message
    //                                 Swal.fire({
    //                                     icon: 'success',
    //                                     title: data.message,
    //                                     showConfirmButton: false,
    //                                     timer: 1500
    //                                 }).then(() => {
    //                                     window.location.reload();
    //                                 });
    //                                 // document.getElementById("show-right-cart").click();
    //                             }
    //                         })
    //                         .catch((error) => {
    //                             console.error('Error:', error);
    //                             // Handle error (for example, show an error message)
    //                         });
    //                 }
    //             }
    //         }
    //     }
    // }

    function hideLoginPopup() {
        document.getElementById('lengkapi-login').style.display = 'none';
    }
</script>
