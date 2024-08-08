<html>

<head>
    @include('layouts.import')
    <title>Bandung Choral Society | My Address</title>
</head>

<body>
    @include('layouts.header')

    <div class="resp-page-section left-flex no-first alamat-page" style="margin-bottom: 60px !important;">
        <div class="section-title">My Address</div>

        <div class="section-col">
            <div class="section-row button-row">
                <button class="btn btn-black" onclick="javascript:location.href='{{ route('profile') }}'">Back</button>
                @if($shipment->count() < 5)
                <button class="btn-white btn popup-trigger" target-popup="right-alamat">+ Add Address</button>
                @endif
            </div>

            <div class="alamat-group-container">
                @foreach ($shipment as $item)
                    <div class="alamat-group">
                        <div class="alamat-title">
                            {{$item->detail_informasi_tambahan}}
                            @if ($item->is_default == 1)
                                <div class="alamat-tag">Main Address</div>
                            @endif
                            <div style="width: max-content; display: flex; margin-left: auto; gap: 20px;">
                                <button class="target-change-address" target="{{ $item->id }}"><i class="fa-solid fa-check"></i>
                                    Choose</button>
                            </div>
                        </div>
                        <div class="alamat-penerima">{{ $item->nama_penerima }}</div>
                        <div class="alamat-telepon">{{ $item->phone_number }}</div>
                        <div class="alamat-desc">
                            {{ $item->informasi_tambahan }},
                            {{ $item->kecamatan }},
                            {{ $item->kota }},
                            {{ $item->provinsi }},
                            {{ $item->kode_pos }},
                            {{ $item->negara }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('layouts.footer')
</body>

</html>

<script>
    const targetChangeAddress = document.querySelectorAll('.target-change-address');

    targetChangeAddress.forEach((element)=>{
        element.addEventListener('click', (event)=>{
            location.href="{{ route('checkout') }}/"+event.currentTarget.getAttribute('target');
        });
    });
</script>
