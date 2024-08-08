<!-- START UPPER NAV BAR -->
<nav class="upper-nav-bar">
    <img class="upper-nav-logo" src="{{ asset('assets/images/bcs_logo.png') }}" />

    <div class="upper-nav-links mobile-hide">
        <a class="upper-nav-link {{ request()->routeIs('homepage') ? 'active' : '' }}" onclick="javascript:location.href='{{ route('homepage') }}'">Home</a>
        <a class="upper-nav-link {{ request()->routeIs('publisher') || request()->routeIs('publisher-detail') ? 'active' : '' }}" onclick="javascript:location.href='{{ route('publisher') }}'">Publisher</a>
        <a class="upper-nav-link {{ request()->routeIs('composer') || request()->routeIs('composer-detail') ? 'active' : '' }}" onclick="javascript:location.href='{{ route('composer') }}'">Composer</a>
        <a class="upper-nav-link {{ request()->routeIs('collection') || request()->routeIs('collection-detail') ? 'active' : '' }}" onclick="javascript:location.href='{{ route('collection') }}'">Collection</a>
        <a class="upper-nav-link" href="https://www.bandungchoral.com/contact-us" target="_blank">Contact Us</a>
    </div>

    <script>
    function sort(parameter) {
        let currentUrl = window.location.href;
        let newUrl = new URL(currentUrl);
        let searchParams = newUrl.searchParams;
        searchParams.set('sort', parameter);

        window.location.href = newUrl.toString();
    }

    function disableButton() {
        let form = document.getElementById('alamatForm');
        let inputs = form.querySelectorAll('.required, select.required');
        let allFilled = true;
        let firstEmptyInput = null;

        inputs.forEach(input => {
            let hint = input.closest('.col-form-group').querySelector('.input-hint');
            if (input.value == '') {
                hint.classList.add('active');
                if (firstEmptyInput === null) {
                    firstEmptyInput = input;
                }
                allFilled = false;
            } else {
                hint.classList.remove('active');
            }
        });

        if (allFilled) {
            document.getElementById('submitBtn').disabled = true;
            form.submit();
        } else if (firstEmptyInput !== null) {
            firstEmptyInput.focus();
        }
    }

    function disableButton2() {
        let form = document.getElementById('choirForm');
        let inputs = form.querySelectorAll('.required');
        let allFilled = true;
        let firstEmptyInput = null;

        inputs.forEach(input => {
            let hint = input.nextElementSibling;
            if (input.value.trim() === '') {
                hint.classList.add('active');
                if (firstEmptyInput === null) {
                    firstEmptyInput = input;
                }
                allFilled = false;
            } else {
                hint.classList.remove('active');
            }
        });

        if (allFilled) {
            document.getElementById('submitBtn2').disabled = true;
            form.submit();
        } else if (firstEmptyInput !== null) {
            firstEmptyInput.focus();
        }
    }

    function formatHarga(harga) {
        // Pastikan harga adalah tipe Number, konversi jika perlu
        harga = Number(harga);
        if (isNaN(harga)) return 'Invalid number'; // Handle jika harga bukan angka

        // Konversi angka ke string dan pisahkan bagian utama dan desimal jika ada
        const parts = harga.toFixed(0).split('.');
        let mainPart = parts[0];

        // Reverse string utama untuk memudahkan penyisipan koma
        let reversedMainPart = mainPart.split('').reverse().join('');
        let formattedMainPart = '';

        // Sisipkan koma setiap tiga digit
        for (let i = 0; i < reversedMainPart.length; i++) {
            if (i > 0 && i % 3 === 0) {
                formattedMainPart += '.'; // Tambahkan koma setiap 3 digit
            }
            formattedMainPart += reversedMainPart[i];
        }

        // Balikkan kembali string yang sudah diformat dan gabungkan dengan bagian desimal
        mainPart = formattedMainPart.split('').reverse().join('');
        return `${mainPart}`;
    }

    function updateFilters(checkbox, id) {
        let currentUrl = window.location.href;
        let newUrl = new URL(currentUrl);
        let searchParams = newUrl.searchParams;

        let existingFilters = searchParams.get('filter') ? searchParams.get('filter').split(',') : [];
        if (checkbox.checked) {
            existingFilters.push(id); // Add the checked id
        } else {
            existingFilters = existingFilters.filter(item => item != id); 
        }
        searchParams.set('filter', existingFilters.join(','));

        window.location.href = newUrl.toString();
    }
    </script>

    <div class="row gap-20 align-items-center mobile-nav-bar">
        <div class="upper-nav-search">
            <i class="icon fa fa-search" aria-hidden="true"></i>
            <!-- <input />  -->
            <select id="searchbar">
                <option></option>
                <optgroup label="Composer">
                    @foreach($composerSearch as $c)
                    <option value="composer-{{$c->id}}" <?php if(isset($type)) { ?> @if($type=="composer" && $c->id==$id) selected @endif <?php } ?>>{{$c->name}}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Collection">
                    @foreach($collectionSearch as $c)
                    <option value="collection-{{$c->id}}" <?php if(isset($type)) { ?> @if($type=="collection" && $c->id==$id) selected @endif <?php } ?>>{{$c->name}}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Sheet Music">
                    @foreach($partiturSearch as $c)
                    <option value="sheetmusic-{{$c->id}}" <?php if(isset($type)) { ?> @if($type=="sheetmusic" && $c->id==$id) selected @endif <?php } ?>>{{$c->name}}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Merchandise">
                    @foreach($merchandiseSearch as $c)
                    <option value="merchandise-{{$c->id}}" <?php if(isset($type)) { ?> @if($type=="merchandise" && $c->id==$id) selected @endif <?php } ?>>{{$c->name}}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>

        @auth('customer')
                <div class="button-line">
                    <button class="nav-profile mobile-hide" type="button" onclick="javascript:location.href='{{ route('profile') }}'">
                    @if(Auth::guard('customer')->user()->photo_profile == null)
                        <img src="{{ asset('public/uploads/default.jpg') }}" />
                    @else
                        <img src="{{ asset('public/'.Auth::guard('customer')->user()->photo_profile) }}" />
                    @endif
                    </button>

                    @if($choirCount == 0)
                        <button id="show-right-cart" target-popup="lengkapi-choir" class="btn btn-black border-muted-black popup-trigger" type="button"><i class="icon fa fa-shopping-cart" aria-hidden="true"></i></button>
                        @else
                        <button id="show-right-cart" target-popup="right-cart" class="btn btn-black popup-trigger" type="button"><i class="icon fa fa-shopping-cart" aria-hidden="true"></i></button>
                    @endif

                    <button type="submit" class="btn btn-white border-muted-black mobile-hide" onclick="logout()">Logout</button>
                </div>

                <button id="show-nav-mobile" target-popup="nav-mobile" class="btn btn-black popup-trigger mobile-show" type="button"><i class="fa-solid fa-bars"></i></button>
            @endauth

            @guest('customer')
                <div class="button-merge">
                    <button class="btn btn-black border-muted-black mobile-hide" type="button" onclick="javascript:location.href='{{ route('login') }}'">Login</button>
                    <button class="btn btn-white border-muted-black mobile-hide" type="button" onclick="javascript:location.href='{{ route('register') }}'">Register</button>

                    <button id="show-nav-mobile" target-popup="nav-mobile" class="btn btn-black popup-trigger mobile-show" type="button"><i class="fa-solid fa-bars"></i></button>
                </div>
            @endguest
    </div>
</nav>
<!-- END UPPER NAV BAR -->