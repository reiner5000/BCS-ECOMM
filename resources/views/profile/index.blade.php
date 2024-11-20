<html>

<head>
    @include('layouts.import')
    <title>Bandung Choral Society | My Account</title>
    <style>
        .card-newtitle{
            overflow: auto;
        }

        /* Untuk tampilan tablet dan HP (kurang dari atau sama dengan 1024px) */
        @media (max-width: 1024px) {
            .list-order{
                flex-direction: column;
                align-items: center;
            }
            .card-order{
                width: 100% !important;
                    align-items: center;
            }
            .card-detail-order{
                width: 100% !important;
                    align-items: center;
            }
            .card-detail-o{
                width: 100%;
                display: flex;
                align-items: center;
            }
        }
    </style>
</head>

<body>
    @include('layouts.header')

    <div class="resp-page-section left-flex no-first profile-page" style="margin-bottom: 60px !important;">
        <div class="section-title">My Account</div>
        <div class="trigger-container">
            <div class="tab-trigger active" tab-target="biodata-tab">My Profile</div>
            <div class="tab-trigger" tab-target="riwayat-tab">Order History</div>
        </div>

        <!-- TAB PROFILE -->
        <div class="tab-container w-100 active" id="biodata-tab">
            <div class="section-row mobile-col">
                <div class="min-col">
                    <div class="profile-img-card">
                        @if(Auth::guard('customer')->user()->photo_profile == null)
                            <img class="profile-img-view" src="{{ asset('public/uploads/default.jpg') }}" />
                        @else
                            <img class="profile-img-view" src="{{ asset('public/'.Auth::guard('customer')->user()->photo_profile) }}" />
                        @endif
                        <button id="uploadButton" class="login-button mobile-muted-button">Choose a photo</button>
                        <input type="file" id="fileFoto" name="fileFoto" style="display: none;" accept="image/png,image/jpeg" />
                        <div class="profile-img-hint mobile-hide">Photo size: max. 1MB <br />Photo format: .JPG .JPEG .PNG</div>
                    </div>
                </div>
                <div class="max-col bio-col">
                    <div class="bio-kategori">Personal Data</div>
                    <div class="bio-group">
                        <div class="bio-title">Name</div>
                        <div class="bio-value">{{ Auth::guard('customer')->user()->name }}</div>
                    </div>
                    <div class="bio-group">
                        <div class="bio-title">Gender</div>
                        <div class="bio-value">{{ Auth::guard('customer')->user()->gender ?? '-' }}</div>
                    </div>

                    <div class="bio-kategori">Contact Information</div>
                    <div class="bio-group">
                        <div class="bio-title">Email</div>
                        <div class="bio-value">{{ Auth::guard('customer')->user()->email }}</div>
                    </div>
                    <div class="bio-group">
                        <div class="bio-title">Password</div>
                        <div class="bio-value">***********</div>
                    </div>
                    <div class="bio-group">
                        <div class="bio-title">Phone Number</div>
                        <div class="bio-value">{{ Auth::guard('customer')->user()->phone_number ?? '-' }}</div>
                    </div>

                    <button class="login-button profile-show" target-popup="edit-profile" customer-id="{{ Auth::guard('customer')->user()->id }}"
                    customer-name="{{ Auth::guard('customer')->user()->name }}" customer-gender="{{ Auth::guard('customer')->user()->gender }}" customer-phone="{{ Auth::guard('customer')->user()->phone_number }}" customer-email="{{ Auth::guard('customer')->user()->email }}">Change Data</button>
                </div>

                <div class="max-col alamat-col mobile-mt-xxl">
                    <div class="section-title"
                        style="margin-bottom: -12px !important;margin-top: -10px !important; display: flex; justify-content: space-between; margin-right: 24px; font-size: 24px;">
                        Choir <div style=" width: max-content; display: flex; gap: 20px;">
                            <button class="btn-link" onclick="location.href='{{ route('choir') }}'">View all
                                choirs</button>
                        </div>
                    </div>
                    
                    @if($choir->count() == 0)
                        <div class="alamat-group">
                            <div class="alamat-title">
                                (Conductor)
                            </div>
                            <div class="alamat-penerima">(Choir Name)</div>
                            <div class="alamat-desc">(Address)</div>
                        </div>
                    @else
                        <div class="alamat-group">
                            <div class="alamat-title mobile-alamat-title">
                                Conductor : {{ $choir[0]->conductor }}
                                <div class="alamat-tag">Main Choir</div>
                                <div class="mobile-hide" style="width: max-content; display: flex; margin-left: auto; gap: 20px;">
                                    <button class="choir-show" target-popup="edit-choir" choir-id="{{ $choir[0]->id }}"
                                        choir-name="{{ $choir[0]->name }}" choir-conductor="{{ $choir[0]->conductor }}"
                                        choir-address="{{ $choir[0]->address }}"><i class="fa-solid fa-pencil"></i> Change</button>
                                </div>
                            </div>
                            <div class="mobile-show" style="width: max-content; margin-left: auto; gap: 20px;">
                                <button class="choir-show" target-popup="edit-choir" choir-id="{{ $choir[0]->id }}"
                                    choir-name="{{ $choir[0]->name }}" choir-conductor="{{ $choir[0]->conductor }}"
                                    choir-address="{{ $choir[0]->address }}"><i class="fa-solid fa-pencil"></i> Change</button>
                            </div>
                            <div class="alamat-penerima">{{ $choir[0]->name }}</div>
                            <div class="alamat-desc">{{ $choir[0]->address }}</div>
                        </div>
                    @endif

                    <div class="section-title"
                        style="margin-bottom: -12px !important; display: flex; justify-content: space-between; margin-right: 24px; font-size: 24px;">
                        Address <div style=" width: max-content; display: flex; gap: 20px;">
                            <button class="btn-link" onclick="location.href='{{ route('address') }}'">View all
                                address</button>
                        </div>
                    </div>
                    

                    @if($shipment->count() == 0)
                        <div class="alamat-group">
                            <div class="alamat-title">(Additional Information)</div>
                            <div class="alamat-penerima">(Name)</div>
                            <div class="alamat-telepon">(Phone Number)</div>
                            <div class="alamat-desc">(Address)</div>
                        </div>
                    @else
                        <div class="alamat-group">
                            <div class="alamat-title mobile-alamat-title">{{ $shipment[0]->detail_informasi_tambahan }}
                                <div class="alamat-tag">Main Address</div>
                                <div class="mobile-hide" style="width: max-contentz; display: flex; margin-left: auto; gap: 20px;">
                                    <button class="alamat-show-popup" target-popup="edit-alamat"
                                        alamat-id="{{ $shipment[0]->id }}" alamat-nama="{{ $shipment[0]->nama_penerima }}"
                                        alamat-telp="{{ $shipment[0]->phone_number }}" alamat-negara="{{ $shipment[0]->negara }}"
                                        alamat-provinsi="{{ $shipment[0]->provinsi }}" alamat-kota="{{ $shipment[0]->kota }}"
                                        alamat-kecamatan="{{ $shipment[0]->kecamatan }}"
                                        alamat-kode-pos="{{ $shipment[0]->kode_pos }}"
                                        alamat-informasi-tambahan="{{ $shipment[0]->informasi_tambahan }}"
                                        alamat-detail-informasi-tambahan="{{ $shipment[0]->detail_informasi_tambahan }}"></i><i class="fa-solid fa-pencil"></i> Change</button>
                                </div>
                            </div>

                            <div class="mobile-show" style="width: max-content; margin-left: auto; gap: 20px;">
                                <button class="alamat-show-popup" target-popup="edit-alamat"
                                    alamat-id="{{ $shipment[0]->id }}" alamat-nama="{{ $shipment[0]->nama_penerima }}"
                                    alamat-telp="{{ $shipment[0]->phone_number }}" alamat-negara="{{ $shipment[0]->negara }}"
                                    alamat-provinsi="{{ $shipment[0]->provinsi }}" alamat-kota="{{ $shipment[0]->kota }}"
                                    alamat-kecamatan="{{ $shipment[0]->kecamatan }}"
                                    alamat-kode-pos="{{ $shipment[0]->kode_pos }}"
                                    alamat-informasi-tambahan="{{ $shipment[0]->informasi_tambahan }}"
                                    alamat-detail-informasi-tambahan="{{ $shipment[0]->detail_informasi_tambahan }}"></i><i class="fa-solid fa-pencil"></i> Change</button>
                            </div>

                            <div class="alamat-penerima">{{ $shipment[0]->nama_penerima }}</div>
                            <div class="alamat-telepon">{{ $shipment[0]->phone_number }}</div>
                            <div class="alamat-desc"> {{ $shipment[0]->informasi_tambahan }},
                            {{ $shipment[0]->kecamatan }},
                            {{ $shipment[0]->kota }},
                            {{ $shipment[0]->provinsi }},
                            {{ $shipment[0]->kode_pos }},
                            {{ $shipment[0]->negara }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- TAB RIWAYAT -->
        <div class="tab-container w-100" id="riwayat-tab">
            <div class="section-col w-100">
                @foreach($order->where('payment_id',1) as $o)
                <div class="history-card">
                    <div class="col w-100">
                        <div class="row align-items-center gap-20" style="justify-content: space-between;">
                            <div class="card-newtitle">{{$o->no_invoice}}</div>
                            <div class="card-subtitle"></div>
                            <div class="history-status 
                                @if($o->status == 2) 
                                    success 
                                @elseif($o->status == 1) 
                                    default 
                                @else 
                                    warning 
                                @endif">
                                    @if ($o->shipment_fee == 0)
                                        Online Document
                                    @elseif($o->status == 2) 
                                        Finished 
                                    @elseif($o->status == 1) 
                                        In delivery 
                                    @else 
                                        It's being packaged 
                                    @endif
                            </div>
                            
                        </div>
                        <hr>

                        <div class="row gap-20 list-order">
                            <img class="history-img" src="{{ asset('assets/images/order.svg') }}" />
                            <div class="col w-50 card-order">
                                <div class="card-title"> {{\Carbon\Carbon::parse($o->date)->format('j M Y')}}</div>
                                <div class="card-muted">
                                    @foreach($o->items as $d)
                                    {{$d->quantity}} item x Rp {{number_format($d->total_harga/$d->quantity,0,',','.')}}<br>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col w-50 h-100 justify-content-space-between card-detail-order">
                            <div class="col align-items-end card-detail-o">
                                <div class="card-title">Total Shopping</div>
                                <div class="card-muted">Rp {{number_format(($o->total),0,',','.')}}</div>
                                 <div class="button-red mt-m" style="text-align: center; width: 50%;" onclick="javascript:location.href='{{ route('order', ['id' => $o->id]) }}'">View Order Details</div>
                            </div>
                           
                        </div>
                        </div>
                    </div>

                </div>
                <br>
                @endforeach
            </div>
        </div>
    </div>

    @include('layouts.footer')

    @if(Auth::guard('customer')->user()->gender == '' || Auth::guard('customer')->user()->gender == null || $choir->count() == 0 || $shipment->count() == 0)
    <script>
        const popup_lengkapi = document.getElementById('lengkapi-profile');
        popup_lengkapi.classList.toggle('active');
    </script>
    @endif
    
    <script>
        document.getElementById('uploadButton').addEventListener('click', function() {
            document.getElementById('fileFoto').click(); 
        });

        document.getElementById('fileFoto').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('photo', file);

                fetch('{{route('save-photo')}}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if(data['success'] == true){
                        window.location.reload(); 
                    } else {
                        console.error('Upload failed');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    </script>

<script>
    // Function to get URL query parameters
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Check for 'tab' query parameter
        const tab = getQueryParam('tab');

        if (tab === 'riwayat') {
            // Activate the 'Order History' tab
            document.querySelector('.tab-trigger[tab-target="riwayat-tab"]').classList.add('active');
            document.querySelector('#riwayat-tab').classList.add('active');
            
            // Deactivate the 'My Profile' tab
            document.querySelector('.tab-trigger[tab-target="biodata-tab"]').classList.remove('active');
            document.querySelector('#biodata-tab').classList.remove('active');
        }
    });

    // Event listener for tab clicks
    document.querySelectorAll('.tab-trigger').forEach(trigger => {
        trigger.addEventListener('click', function() {
            const target = this.getAttribute('tab-target');

            // Remove 'active' class from all tabs
            document.querySelectorAll('.tab-trigger').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-container').forEach(container => container.classList.remove('active'));

            // Add 'active' class to the clicked tab and corresponding container
            this.classList.add('active');
            document.getElementById(target).classList.add('active');
        });
    });
</script>
</body>

</html>
