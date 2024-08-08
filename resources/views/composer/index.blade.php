<html>

<head>
    @include('layouts.import')
    <title>Bandung Choral Society | Composer</title>
</head>

<body>
    @include('layouts.header')

    <div class="resp-page-section left-flex no-first" style="margin-bottom: 60px !important;">
        <div class="section-row left-flex max-width">
            <div class="section-title">COMPOSER</div>
            <div class="filter-group">
                <div class="filter-icon show-sort-popup" target-popup="sort-popup"><i class="fa-solid fa-sort"></i> Sort By</div>
                <!-- <div class="filter-icon show-filter-popup" target-popup="filter-popup"><i class="fa-solid fa-filter"></i> Filter By</div> -->
            </div>
        </div>
        <div class="komposer-container">
            @foreach ($composers as $composer)
                <div class="komposer-card open-komposer-detail" data-target="{{ $composer->name }}">
                    <img {{ file_exists('public/' . $composer->photo_profile) && $composer->photo_profile ? '' : 'class=contain-img-remove' }} src="{{ file_exists('public/' . $composer->photo_profile) && $composer->photo_profile ? asset('public/' . $composer->photo_profile) : asset('assets/images/favicon.png') }}" />
                    <div class="komposer-name mt-s">{{ $composer->name }}</div>
                    <div class="komposer-region mb-s">{{ $composer->asal_negara }}</div>
                </div>
            @endforeach
        </div>
        
        <div class="grid-datatable-page">
            <div class="hint-datatable-page">Showing {{ $composers->firstItem() }} to {{ $composers->lastItem() }} of
                {{ $composers->total() }} items</div>
            <div class="navigation-datatable-page">
            @php
            $sort = request('sort'); // Dapatkan parameter sort dari request
            @endphp

            @if ($composers->onFirstPage())
            @else
                <button onclick="location.href='{{ $composers->previousPageUrl() }}{{ $sort ? '&sort=' . $sort : '' }}'"><i class="fa-solid fa-angle-left"></i></button>
            @endif

            @if ($composers->currentPage() > 2)
                <button onclick="location.href='{{ $composers->url(1) }}{{ $sort ? '&sort=' . $sort : '' }}'">1</button>
                @if ($composers->currentPage() > 3)
                    <button disabled>...</button>
                @endif
            @endif

            @for ($i = max(1, $composers->currentPage() - 1); $i <= min($composers->lastPage(), $composers->currentPage() + 1); $i++)
                <button class="{{ ($composers->currentPage() == $i) ? 'active' : '' }}" onclick="location.href='{{ $composers->url($i) }}{{ $sort ? '&sort=' . $sort : '' }}'">{{ $i }}</button>
            @endfor

            @if ($composers->currentPage() < $composers->lastPage() - 1)
                @if ($composers->currentPage() < $composers->lastPage() - 2)
                    <button disabled>...</button>
                @endif
                <button onclick="location.href='{{ $composers->url($composers->lastPage()) }}{{ $sort ? '&sort=' . $sort : '' }}'">{{ $composers->lastPage() }}</button>
            @endif

            @if ($composers->hasMorePages())
                <button onclick="location.href='{{ $composers->nextPageUrl() }}{{ $sort ? '&sort=' . $sort : '' }}'"><i class="fa-solid fa-angle-right"></i></button>
            @endif

            </div>
        </div>
    </div>

    <div class="popup-filter" id="sort-popup">
        <div class="popup-filter-card">
            <div class="col-group">
                <div class="row justify-space-between w-100">
                    <div class="filter-icon mb-20"><i class="fa-solid fa-sort"></i> Sort By</div>
                    <div class="button-red special-filter show-sort-popup" target-popup="sort-popup">Close</div>
                </div>
                <div class="filter-group w-100" filter-target="fg-1" onclick="sort('latest')">
                    <div class="filter-name w-100">Latest</div>
                </div>
                <div class="filter-group w-100" filter-target="fg-1" onclick="sort('country')">
                    <div class="filter-name w-100">Country</div>
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

                popupCard.style.top = `${topPosition+260}px`;
                popupCard.style.right = `0px`;
            }

            updatePopupPosition();

            window.addEventListener('scroll', updatePopupPosition);
        });
    });
</script>

</html>
