<html>

<head>
    @include('layouts.import')
    <title>Bandung Choral Society | {{ $composer->name }}</title>
</head>

<body>
    @include('layouts.header')

    <div class="resp-page-section left-flex no-first komposer-detail" style="margin-bottom: 60px !important;">
        <div class="section-row">
            <img {{ file_exists('public/' . $composer->photo_profile) && $composer->photo_profile ? '' : 'class=contain-img-remove' }} src="{{ file_exists('public/' . $composer->photo_profile) && $composer->photo_profile ? asset('public/' . $composer->photo_profile) : asset('assets/images/favicon.png') }}" alt="{{ asset('public/' . $composer->photo_profile) }}" />
            <div class="section-col mobile-left-s">
                <div class="komposer-name mobile-komposer-name">{{ $composer->name }}</div>
                <div class="komposer-region">{{ $composer->asal_negara }}</div>
                <div class="komposer-story">{{ $composer->profile_desc }}</div>
                <div class="komposer-medsos">
                    @if($composer->instagram != '')
                    <a class="link" href="{{ $composer->instagram }}">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    @endif
                    @if($composer->facebook != '')
                    <a class="link" href="{{ $composer->facebook }}">
                        <i class="fa-brands fa-square-facebook"></i>
                    </a>
                    @endif
                    @if($composer->twitter != '')
                    <a class="link" href="{{ $composer->twitter }}">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="section-col mt-20">
            <div class="section-row left-flex max-width align-items-center justify-content-center">
                <div class="section-title">SHEET MUSIC</div>
                <div class="filter-group">
                    <div class="filter-icon show-sort-popup" target-popup="sort-popup"><i class="fa-solid fa-sort"></i> Sort By<br/></div>
                    <div class="filter-icon show-filter-popup" target-popup="filter-popup"><i class="fa-solid fa-filter"></i> Filter By</div>
                </div>
            </div>
            <div class="koleksi-container">
                @foreach ($composer->partiturs as $partitur)
                    @php
                        $images = explode(',', $partitur->file_image);
                        $mainImage = $images[0]; // Ambil gambar utama
                    @endphp
                    <a class="tab-koleksi-link" href="{{ route('publisher.detail', ['name' => rawurlencode($partitur->name)]) }}">
                        <img {{ file_exists('public/' . $mainImage) && $mainImage ? '' : 'class=contain-img-remove' }} src="{{ file_exists('public/' . $mainImage) && $mainImage ? asset('public/' . $mainImage) : asset('assets/images/favicon.png') }}" alt="{{ $partitur->name }}" />
                        <div class="tab-koleksi-title">{{ $partitur->name }}</div>
                        <div class="tab-koleksi-sub"></div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @include('layouts.footer')
</body>

    <div class="popup-filter" id="sort-popup">
        <div class="popup-filter-card">
            <div class="col-group">
                <div class="row justify-space-between w-100">
                    <div class="filter-icon mb-20"><i class="fa-solid fa-sort"></i> Sort By</div>
                    <div class="popup-quit special-filter show-sort-popup" target-popup="sort-popup"><i class="bi bi-x-square-fill"></i></div>
                </div>
                    <div class="filter-group w-100" filter-target="fg-1" onclick="sort('bestseller')">
                        <div class="filter-name w-100">Bestseller</div>
                    </div>
                    <div class="filter-group w-100" filter-target="fg-1" onclick="sort('latest')">
                        <div class="filter-name w-100">Latest</div>
                    </div>
                    <div class="filter-group w-100" filter-target="fg-1" onclick="sort('asc')">
                        <div class="filter-name w-100">A to Z</div>
                    </div>
                    <div class="filter-group w-100" filter-target="fg-1" onclick="sort('desc')">
                        <div class="filter-name w-100">Z to A</div>
                    </div>
                </div>
            </div>
    </div>

    <div class="popup-filter" id="filter-popup">
        <div class="popup-filter-card">
            <div class="col-group">
                <div class="row justify-space-between w-100">
                    <div class="filter-icon mb-20"><i class="fa-solid fa-filter"></i> Filter By</div>
                    <div class="popup-quit special-filter show-filter-popup" target-popup="filter-popup"><i class="bi bi-x-square-fill"></i></div>
                </div>
                <br>
                @foreach($category as $c)
                @php($no = $loop->iteration)
                <div class="filter-group w-100" filter-target="fg-{{$loop->iteration}}">
                    <div class="filter-name w-100">{{$c->name}}</div>
                    <div class="filter-icon"><i class="fa-solid fa-chevron-up"></i></div>
                </div>
                @php($isActive = count(array_intersect($activeFilters, $c->details->pluck('id')->toArray())) > 0)
                <div id="fg-{{$no}}" class="filter-list {{ $isActive ? 'active' : '' }}">
                    @foreach($c->details as $d)
                        <label class="custom-checkbox">
                            <input type="checkbox" id="checkbox{{$d->id}}"
                                name="details{{$d->id}}" onclick="updateFilters(this, {{$d->id}})" {{ in_array($d->id, $activeFilters) ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            {{$d->name}}
                        </label>
                    @endforeach
                </div>
                @endforeach
                
                
            </div>
        </div>
    </div>

<script>
    const sortTrigger = document.querySelectorAll('.show-sort-popup');

    sortTrigger.forEach((element)=>{
        element.addEventListener('click', (event)=>{
            const popup = document.getElementById(element.getAttribute('target-popup'));

            popup.classList.toggle('active');

            const updatePopupPosition = () => {
                const targetPopupId = element.getAttribute('target-popup');
                const targetPopup = document.getElementById(targetPopupId);

                const buttonRect = element.getBoundingClientRect();
                const popupCard = targetPopup.querySelector('.popup-filter-card');

                const topPosition = buttonRect.top - popupCard.offsetHeight;
                const leftPosition = buttonRect.left;

                popupCard.style.top = `${topPosition+260}px`;
                popupCard.style.right = `0px`;
            }

            updatePopupPosition();

            window.addEventListener('scroll', updatePopupPosition);
        });
    });

    const filterTrigger = document.querySelectorAll('.show-filter-popup');

    filterTrigger.forEach((element)=>{
        element.addEventListener('click', (event)=>{
            const popup = document.getElementById(element.getAttribute('target-popup'));

            popup.classList.toggle('active');

            const updatePopupPosition = () => {
                const targetPopupId = element.getAttribute('target-popup');
                const targetPopup = document.getElementById(targetPopupId);

                const buttonRect = element.getBoundingClientRect();
                const popupCard = targetPopup.querySelector('.popup-filter-card');

                const topPosition = buttonRect.top - popupCard.offsetHeight;
                const leftPosition = buttonRect.left;

                popupCard.style.top = `${topPosition+188}px`;
                popupCard.style.right = `0px`;
            }

            updatePopupPosition();

            window.addEventListener('scroll', updatePopupPosition);
        });
    });
</script>


</html>
