<html>

<head>
    @include('layouts.import')
    <title>Bandung Choral Society | Collection</title>
</head>

<body>
    @include('layouts.header')

    <div class="resp-page-section left-flex no-first koleksi" style="margin-bottom: 60px !important;">
        <div class="section-col">
            <div class="section-row left-flex max-width">
                <div class="section-title">COLLECTION</div>
                <div class="filter-group">
                    <div class="filter-icon show-sort-popup" target-popup="sort-popup"><i class="fa-solid fa-sort"></i> Sort By</div>
                    <!-- <div class="filter-icon" id="collection-sort-by"><i class="fa-solid fa-sort"></i> Sort By : {{ $sortText }}</div> -->
                </div>
            </div>
            <div class="koleksi-container">
                @foreach ($collection as $item)
                    <a class="tab-koleksi-link open-koleksi-detail" data-target="{{ $item->name }}">
                        <img {{ file_exists('public/' . $item->cover) && $item->cover ? '' : 'class=contain-img-remove' }} src="{{ file_exists('public/' . $item->cover) && $item->cover ? asset('public/' . $item->cover) : asset('assets/images/favicon.png') }}" />
                        <div class="tab-koleksi-title">{{ $item->name }}</div>
                        <div class="tab-koleksi-sub"></div>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="grid-datatable-page">
            <div class="hint-datatable-page">Showing {{ $collection->firstItem() }} to {{ $collection->lastItem() }} of
                {{ $collection->total() }} items</div>
            <div class="navigation-datatable-page">
                    @php
                    $sort = request('sort'); // Dapatkan parameter sort dari request
                    @endphp
                    
                    @if ($collection->onFirstPage())
                    @else
                        <button onclick="location.href='{{ $collection->previousPageUrl() }}{{ $sort ? '&sort=' . $sort : '' }}'"><i class="fa-solid fa-angle-left"></i></button>
                    @endif

                    @if ($collection->currentPage() > 2)
                        <button onclick="location.href='{{ $collection->url(1) }}{{ $sort ? '&sort=' . $sort : '' }}'">1</button>
                        @if ($collection->currentPage() > 3)
                            <button disabled>...</button>
                        @endif
                    @endif

                    @for ($i = max(1, $collection->currentPage() - 1); $i <= min($collection->lastPage(), $collection->currentPage() + 1); $i++)
                        <button class="{{ ($collection->currentPage() == $i) ? 'active' : '' }}" onclick="location.href='{{ $collection->url($i) }}{{ $sort ? '&sort=' . $sort : '' }}'">{{ $i }}</button>
                    @endfor

                    @if ($collection->currentPage() < $collection->lastPage() - 1)
                        @if ($collection->currentPage() < $collection->lastPage() - 2)
                            <button disabled>...</button>
                        @endif
                        <button onclick="location.href='{{ $collection->url($collection->lastPage()) }}{{ $sort ? '&sort=' . $sort : '' }}'">{{ $collection->lastPage() }}</button>
                    @endif

                    @if ($collection->hasMorePages())
                        <button onclick="location.href='{{ $collection->nextPageUrl() }}{{ $sort ? '&sort=' . $sort : '' }}'"><i class="fa-solid fa-angle-right"></i></button>
                    @endif
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
</script>

</html>
