<html>

<head>
    @include('layouts.import')
    <title>Bandung Choral Society | My Choir</title>
</head>

<body>
    @include('layouts.header')

    <div class="resp-page-section left-flex no-first alamat-page" style="margin-bottom: 60px !important;">

        <div class="section-title">My Choir</div>

        <div class="section-col">

            <div class="section-row button-row">
                <button class="btn btn-black" onclick="javascript:location.href='{{ route('profile') }}'">Back</button>
                @if($data->count() < 10)
                <button class="btn-white btn popup-trigger" target-popup="right-choir">+ Add Choir</button>
                @endif
            </div>

            <div class="alamat-group-container">
                @foreach ($data as $d)
                    <div class="alamat-group">
                        <div class="alamat-title mobile-alamat-title">
                            Conductor : {{ $d->conductor }}
                            @if ($d->is_default == 0)
                                <button class="choir-show"
                                    onclick="changeChoir('{{ $d->name }}','{{ route('change-choir', ['id' => $d->id]) }}')">Set
                                    as Main Choir</button>
                            @else
                                <div class="alamat-tag">Main Choir</div>
                            @endif
                            <div class="mobile-hide" style="width: max-content; display: flex; margin-left: auto; gap: 20px;">
                                <button class=" choir-show" target-popup="edit-choir" choir-id="{{ $d->id }}" choir-name="{{ $d->name }}" choir-conductor="{{ $d->conductor }}" choir-address="{{ $d->address }}">
                                    <i class="fa-solid fa-pencil"></i> Change
                                </button>
                                <button class="alamat-show" onclick="deleteChoir('{{ $d->name }}','{{ route('delete-choir', ['id' => $d->id]) }}')">
                                    <i class="fa-solid fa-trash"></i>Delete
                                </button>
                            </div>
                        </div>
                        <div class="mobile-show" style="width: max-content; margin-left: auto; gap: 20px;">
                            <button class="choir-show" target-popup="edit-choir" choir-id="{{ $d->id }}" choir-name="{{ $d->name }}" choir-conductor="{{ $d->conductor }}" choir-address="{{ $d->address }}">
                                <i class="fa-solid fa-pencil"></i> Change
                            </button>
                            <button class="alamat-show" onclick="deleteChoir('{{ $d->name }}','{{ route('delete-choir', ['id' => $d->id]) }}')">
                                <i class="fa-solid fa-trash"></i>Delete
                            </button>
                        </div>
                        <div class="alamat-penerima">{{ $d->name }}</div>
                        <div class="alamat-desc">{{ $d->address }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('layouts.footer')
</body>

</html>
