<!-- START FOOTER SECTION -->
<div class="footer">
    <div class="footer-row">
        <div class="footer-col min-gap">
            <img class="footer-logo" src="{{ asset('assets/images/bcs_logo.png') }}" />
            <div class="row gap-10">
                <div class="footer-col"><b>Follow Us</b></div>
                <a class="footer-link" href="https://www.instagram.com/BandungChoral/">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a class="footer-link" href="https://www.facebook.com/Bandungchoral">
                    <i class="fa-brands fa-square-facebook"></i>
                </a>
                <a class="footer-link" href="https://twitter.com/bandungchoral">
                    <i class="fa-brands fa-x-twitter"></i>
                </a>
            </div>
        </div>
        <div class="footer-desc">Founded in July 2000 by Mr. Tommyanto Kandisaputra, the vision is to build strong choral life in Indonesia. By organizing many events : ateliers, concerts, seminars, workshops, choral music camps, collaborations, choral clinics, competitions and symposium. Through some projects, singers and conductors gain experience, by building skills, knowledge, and attitudes in workshops, rehearsals, and concerts.</div>
        <div class="footer-col">
            <div class="footer-title">Customer Service</div>
            <a class="footer-link" onclick="javascript:location.href='{{ route('homepage') }}'">Home</a>
            <a class="footer-link" onclick="javascript:location.href='{{ route('publisher') }}'">Publisher</a>
            <a class="footer-link" onclick="javascript:location.href='{{ route('composer') }}'">Composer</a>
            <a class="footer-link" onclick="javascript:location.href='{{ route('collection') }}'">Collection</a>
            <a class="footer-link" onclick="javascript:location.href='https://www.bandungchoral.com/contact-us'">Contact Us</a>
        </div>
        <div class="footer-col">
            <div class="footer-title">Buy by Category</div>

            @foreach($footer_category as $cat)
            <div class="footer-kategori">{{$cat->name}}</div>
                @foreach($cat->details->take(3) as $det)
                <a class="footer-link" href="{{route('publisher')}}?c={{$det->name}}"><i class="little-dot fa fa-circle" aria-hidden="true"></i> {{$det->name}}</a>
                @endforeach
            @endforeach
        </div>
        <div class="footer-col">
            <div class="footer-title"><img src="{{ asset('assets/logos/207674.svg') }}" />
            </div>

            <a class="footer-link">Bank Transfer</a>
            <a class="footer-link">E-Wallets</a>
            <a class="footer-link">QRIS</a>
            <a class="footer-link">ShopeePay</a>
            <a class="footer-link">Credit Card</a>
            <a class="footer-link">Over The Counter</a>
            <a class="footer-link">Cardless Credit</a>
            <a class="footer-link">Kredivo</a>
        </div>
    </div>
    <hr />
    <div class="footer-cr"><i class="fa-regular fa-copyright"></i> 2024 Bandung Choral Society. All right reserverd.
    </div>
</div>
<!-- END FOOTER SECTION -->

@include('layouts.script')

@include('layouts.popup-cart')
@include('layouts.popup-nav')

@include('layouts.popup-alamat')
@include('layouts.popup-alamat-edit')
@include('layouts.popup-choir')
@include('layouts.popup-choir-edit')
@include('layouts.popup-profile-edit')
@include('layouts.popup-lengkapi')
@include('layouts.popup-lengkapi-choir')
@include('layouts.popup-tanyalogin')
@include('layouts.popup-preview-partitur')
@include('layouts.popup-lengkapi-barang')