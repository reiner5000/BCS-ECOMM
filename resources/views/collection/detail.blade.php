<html>

<head>
    @include('layouts.import')
    <title>Bandung Choral Society | {{ $collection->name }}</title>
</head>

<body>
    @include('layouts.header')

    <div class="resp-page-section left-flex no-first koleksi-detail" style="margin-bottom: 60px !important;">
        <div class="koleksi-header">
            <div class="koleksi-content">
                <div class="koleksi-name">{{ $collection->name }}</div>
                <div class="koleksi-desc">{{ $collection->short_description }}</div>
            </div>
            <img src="{{ asset('public/' . $collection->cover) }}">
        </div>

        <div class="section-col mobile-margin-top-1">
            <div class="section-row left-flex max-width">
                <div class="section-title">SHEET MUSIC</div>
                <div class="filter-group">
                    <div class="filter-icon show-sort-popup" target-popup="sort-popup"><i class="fa-solid fa-sort"></i> Sort By</div>
                    <div class="filter-icon show-filter-popup" target-popup="filter-popup"><i class="fa-solid fa-filter"></i> Filter By</div>
                </div>
            </div>
            <div class="koleksi-container">
                @foreach ($collection->partiturs as $partitur)
                    @php
                        $images = explode(',', $partitur->file_image); // Pisahkan string menjadi array
                        $firstImage = $images[0] ?? asset('assets/images/favicon.png'); // Ambil gambar pertama, atau gunakan gambar default
                    @endphp
                    <a class="tab-koleksi-link" href="{{ route('publisher.detail', ['name' => rawurlencode($partitur->name)]) }}?id={{ $partitur->id }}">
                        <img {{ file_exists('public/' . $firstImage) && $firstImage ? '' : 'class=contain-img-remove' }} src="{{ file_exists('public/' . $firstImage) && $firstImage ? asset('public/' . $firstImage) : asset('assets/images/favicon.png') }}" alt="{{ $partitur->name }}" />
                        <div class="tab-koleksi-title">{{ $partitur->name }}</div>
                        <div class="tab-koleksi-sub"></div>
                    </a>
                @endforeach
            </div>

        </div>
    </div>

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

    @include('layouts.footer')
</body>

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

                popupCard.style.top = `${topPosition+220}px`;
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

                popupCard.style.top = `${topPosition+148}px`;
                popupCard.style.right = `0px`;
            }

            updatePopupPosition();

            window.addEventListener('scroll', updatePopupPosition);
        });
    });
</script>

</html>
