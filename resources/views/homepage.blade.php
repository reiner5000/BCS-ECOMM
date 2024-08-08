<html>

<head>
    @include('layouts.import')
    <title>Bandung Choral Society | Home</title>
</head>

<body>
    @include('layouts.header')

    <!-- START HOME BANNER -->
    <div class="home-section">
        <div class="owl-carousel home-carousel">
            @foreach ($banners as $banner)
                <div class="carousel-item">
                    <a href="{{ $banner->link }}">
                        <img src="{{ file_exists('public/'.$banner->cover) && $banner->cover ? asset('public/' . $banner->cover) : asset('assets/images/favicon.png') }}" alt="Banner Image" />
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <!-- END HOME BANNER -->

    <!-- START TERBARU SECTION -->
    <div id="terbaru" class="resp-page-section mt-50">
        <div class="section-title">New Release</div>
        <div class="section-sub">Introducing our newest music score. All the best new choral music.</div>
        <div class="section-row mt-30 flex-wrap align-items-center col-gap-30">
            @foreach($partitur as $p)
                <div class="tall-card">
                    <img class="card-banner {{ (file_exists(asset('public/'.$p->file_image_first))) ? 'contain-img-remove' : '' }}" src="{{ file_exists('public/'.$p->file_image_first) && $p->file_image_first != '' ? asset('public/'.$p->file_image_first) : asset('assets/images/favicon.png') }}" />
                    <div class="card-title">{{$p->name}}</div>
                    <div class="card-more" onclick="window.location.href='{{ route('publisher.detail', ['name' => rawurlencode($p->name)]) }}'">View</div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- END TERBARU SECTION -->

    <!-- START KATEGORI SECTION -->
    <div id="kategori" class="resp-page-section left-flex">
        <div class="section-title">Category</div>
        <div class="label-container">
            @foreach ($categorys as $cat)
                <a class="label-link" href="{{route('publisher')}}?c={{$cat->name}}">{{ $cat->name }} <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
            @endforeach
        </div>
    </div>
    <!-- END KATEGORI SECTION -->

    <!-- START KATEGORI SECTION -->
    <div id="koleksi-populer" class="resp-page-section left-flex" style="margin-bottom: 80px;">
        <div class="section-row left-flex max-width">
            <div class="section-title">Popular Collection</div>
            <div class="filter-group" onclick="javascript:location.href='{{ route('collection') }}'">
                <div class="filter-icon"><span class="mobile-hide">See more</span> <i class="fa-solid fa-arrow-right-long"></i></div>
            </div>
        </div>
        <div class="koleksi-container justify-content-center">
            @foreach ($topCollection as $tp)
                <a class="tab-koleksi-link open-koleksi-detail" data-target="{{ $tp->name }}">
                    <img {{ file_exists('public/' . $tp->cover) && $tp->cover ? '' : 'class=contain-img-remove' }} src="{{ file_exists('public/' . $tp->cover) && $tp->cover ? asset('public/' . $tp->cover) : asset('assets/images/favicon.png') }}" />
                    <div class="koleksi-title">{{ $tp->name }}</div>
                    <div class="tab-koleksi-sub"></div>
                </a>
            @endforeach
        </div>
    </div>
    <!-- END KATEGORI SECTION -->

    @include('layouts.footer')
</body>

</html>
