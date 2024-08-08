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
                        <div class="alamat-title mobile-alamat-title">
                            {{$item->detail_informasi_tambahan}}
                            @if ($item->is_default == 1)
                                <div class="alamat-tag">Main Address</div>
                            @else
                                <button class="alamat-show"
                                    onclick="changeShipment('{{ $item->name }}','{{ route('change-shipment', ['id' => $item->id]) }}')">Set
                                    as Main Address</button>
                            @endif
                            <div class="mobile-hide" style="width: max-content; margin-left: auto; gap: 20px;">
                                <button class="alamat-show-popup" target-popup="edit-alamat"
                                    alamat-id="{{ $item->id }}" alamat-nama="{{ $item->nama_penerima }}"
                                    alamat-telp="{{ $item->phone_number }}" alamat-negara="{{ $item->negara }}"
                                    alamat-provinsi="{{ $item->provinsi }}" alamat-kota="{{ $item->kota }}"
                                    alamat-kecamatan="{{ $item->kecamatan }}" alamat-kode-pos="{{ $item->kode_pos }}"
                                    alamat-informasi-tambahan="{{ $item->informasi_tambahan }}"
                                    alamat-detail-informasi-tambahan="{{ $item->detail_informasi_tambahan }}"><i
                                        class="fa-solid fa-pencil"></i>
                                    Change</button>
                                    <button class="alamat-show"
                                        onclick="deleteAddress('{{ $item->name }}','{{ route('delete-shipment', ['id' => $item->id]) }}')"><i
                                            class="fa-solid fa-trash"></i> Delete</button>
                            </div>
                        </div>
                        <div class="mobile-show" style="width: max-content; margin-left: auto; gap: 20px;">
                                <button class="alamat-show-popup" target-popup="edit-alamat"
                                    alamat-id="{{ $item->id }}" alamat-nama="{{ $item->nama_penerima }}"
                                    alamat-telp="{{ $item->phone_number }}" alamat-negara="{{ $item->negara }}"
                                    alamat-provinsi="{{ $item->provinsi }}" alamat-kota="{{ $item->kota }}"
                                    alamat-kecamatan="{{ $item->kecamatan }}" alamat-kode-pos="{{ $item->kode_pos }}"
                                    alamat-informasi-tambahan="{{ $item->informasi_tambahan }}"
                                    alamat-detail-informasi-tambahan="{{ $item->detail_informasi_tambahan }}"><i
                                        class="fa-solid fa-pencil"></i>
                                    Change</button>
                                    <button class="alamat-show"
                                        onclick="deleteAddress('{{ $item->name }}','{{ route('delete-shipment', ['id' => $item->id]) }}')"><i
                                            class="fa-solid fa-trash"></i> Delete</button>
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
