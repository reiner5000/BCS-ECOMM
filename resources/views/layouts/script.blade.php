<script>
    $('#searchbar').select2({
        placeholder: 'Search',
        allowClear: false,
        containerCssClass: 'custom-container-class',
        dropdownCssClass: 'custom-dropdown-class'
    });

    $('#searchbar').on('change', function() {
        let selectedValue = $(this).val();
        let parts = selectedValue.split('-');
        let type = parts[0];
        let id = parts[1];

        let url = "";

        if(selectedValue == ""){
            url = '{{ route("publisher") }}';
        }else{
            url = '{{ route("publisher") }}' + '?t=' + type + '&s=' + id;
        }

        window.location.href = url;
    });

    function logout() {
        Swal.fire({
            title: "Confirmation",
            text: "Do you want to logout?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then(function(result) {
            if (result.value) {
                window.location.href = "{{ route('logout') }}";
            }
        });
    }

    function changeChoir(name, url) {
        Swal.fire({
            title: "Confirmation",
            text: "Do you want set this choir to be the main choir?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'PUT',
                    url: url,
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                                location.reload();
                            }
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    }

    function changeShipment(name, url) {
        Swal.fire({
            title: "Confirmation",
            text: "Do you want set this address to be the main address?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'PUT',
                    url: url,
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                                location.reload();
                            }
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    }

    function deleteChoir(name, url) {
        Swal.fire({
            title: "Confirmation",
            text: "Do you want delete this choir?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                                location.reload();
                            }
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    }

    function deleteAddress(name, url) {
        Swal.fire({
            title: "Confirmation",
            text: "Do you want delete this address?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                                location.reload();
                            }
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', (event) => {
        const audioShow = document.querySelectorAll('.audio-show');
        audioShow.forEach((element) => {
            element.addEventListener('click', (event) => {
                const popup = document.getElementById(element.getAttribute('target-popup'));
                let file = element.getAttribute('partitur-file');

                let title = popup.querySelector('.modal-title');
                let content = popup.querySelector('.preview_partitur_customer');

                title.innerHTML = element.getAttribute('partitur-title');

                let contentPreview = '';
                if (file.includes('<iframe')) {
                    contentPreview = `<br>${file}`;
                }
                content.innerHTML = contentPreview;

                popup.classList.toggle('active');

                if (popup.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        });

        const partiturShow = document.querySelectorAll('.partitur-show');
        partiturShow.forEach((element) => {
            element.addEventListener('click', (event) => {
                const popup = document.getElementById(element.getAttribute('target-popup'));
                let file = element.getAttribute('partitur-file');

                let title = popup.querySelector('.modal-title');
                let content = popup.querySelector('.preview_partitur_customer');

                title.innerHTML = element.getAttribute('partitur-title');

                let contentPreview = `<img src="` + file + `"></img>`;
                content.innerHTML = contentPreview;

                popup.classList.toggle('active');

                if (popup.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        });

        const videoShow = document.querySelectorAll('.video-show');
        videoShow.forEach((element) => {
            element.addEventListener('click', (event) => {
                const popup = document.getElementById(element.getAttribute('target-popup'));
                let file = element.getAttribute('partitur-file');

                let title = popup.querySelector('.modal-title');
                let content = popup.querySelector('.preview_partitur_customer');

                title.innerHTML = element.getAttribute('partitur-title');

                let contentPreview = '';
                if (file.includes('<iframe')) {
                    contentPreview = `<br>${file}`;
                }
                content.innerHTML = contentPreview;

                popup.classList.toggle('active');

                if (popup.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        });

        const profileShow = document.querySelectorAll('.profile-show');
        profileShow.forEach((element) => {
            element.addEventListener('click', (event) => {
                const popup = document.getElementById(element.getAttribute('target-popup'));

                popup.querySelector("input[name='nama-customer']").value = element.getAttribute(
                    'customer-name');
                popup.querySelector("input[name='customer-id']").value = element.getAttribute(
                    'customer-id');
                popup.querySelector("input[name='phone-customer']").value = element
                    .getAttribute(
                        'customer-phone');
                popup.querySelector("input[name='email-customer']").value = element
                    .getAttribute(
                        'customer-email');

                let gender = element.getAttribute('customer-gender');
                if (gender == 'Male') {
                    popup.querySelector("input[id='male']").checked = true;
                } else if (gender == 'Female') {
                    popup.querySelector("input[id='female']").checked = true;
                }

                popup.classList.toggle('active');

                if (popup.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        });

        const choirShow = document.querySelectorAll('.choir-show');
        choirShow.forEach((element) => {
            element.addEventListener('click', (event) => {
                const popup = document.getElementById(element.getAttribute('target-popup'));

                popup.querySelector("input[name='nama-choir']").value = element.getAttribute(
                    'choir-name');
                popup.querySelector("input[name='alamat-choir']").value = element.getAttribute(
                    'choir-address');
                popup.querySelector("input[name='nama-konduktor']").value = element
                    .getAttribute('choir-conductor');
                popup.querySelector("input[name='choir-id']").value = element.getAttribute(
                    'choir-id')

                popup.classList.toggle('active');

                if (popup.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        });

        const alamatShow = document.querySelectorAll('.alamat-show-popup');
        alamatShow.forEach((element) => {
            element.addEventListener('click', (event) => {
                const popup = document.getElementById(element.getAttribute('target-popup'));

                popup.querySelector("input[name='alamat-id']").value = element.getAttribute(
                    'alamat-id');
                popup.querySelector("input[name='nama-penerima']").value = element.getAttribute(
                    'alamat-nama');
                popup.querySelector("input[name='nomor-penerima']").value = element
                    .getAttribute('alamat-telp');
                popup.querySelector("input[name='alamat']").value = element.getAttribute(
                    'alamat-informasi-tambahan');
                popup.querySelector("input[name='informasi-penerima']").value = element
                    .getAttribute('alamat-detail-informasi-tambahan');

                var provinceSelectGroup = $('#province-select-group2');
                var provinceInputGroup = $('#province-input-group2');
                var citySelectGroup = $('#city-select-group2');
                var cityInputGroup = $('#city-input-group2');

                const countrySelect = popup.querySelector("select[name='negara']");
                $(countrySelect).val(element.getAttribute('alamat-negara')).trigger('change');

                if (element.getAttribute('alamat-negara') == 'Indonesia') {
                    provinceSelectGroup.show();
                    provinceInputGroup.hide();
                    citySelectGroup.show();
                    cityInputGroup.hide();

                    const provinceSelect = popup.querySelector("select[name='provinsi']");
                    const citySelect = popup.querySelector("select[name='kota']");

                    setTimeout(() => {
                        $(provinceSelect).val(element.getAttribute('alamat-provinsi'))
                            .trigger('change');
                        loadCitiesBasedOnProvince2(element.getAttribute(
                            'alamat-provinsi'), element.getAttribute(
                            'alamat-kota'));
                    }, 500);

                } else {
                    provinceSelectGroup.hide();
                    provinceInputGroup.show();
                    citySelectGroup.hide();
                    cityInputGroup.show();

                    popup.querySelector("input[name='provinsi-input']").value = element
                        .getAttribute(
                            'alamat-provinsi');
                    popup.querySelector("input[name='kota-input']").value = element
                        .getAttribute(
                            'alamat-kota');
                }

                popup.querySelector("input[name='kecamatan']").value = element.getAttribute(
                    'alamat-kecamatan');
                popup.querySelector("input[name='kode_pos']").value = element.getAttribute(
                    'alamat-kode-pos');

                popup.classList.toggle('active');

                if (popup.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        });

        function loadCitiesBasedOnProvince2(provinceId, cityId) {
            var citySelect = $('#city-select2');
            citySelect.empty().trigger('change');

            if (provinceId !== '') {
                let provinceUrl =
                    "{{ route('getCitiesByProvinces', ['provinces_id' => ':provincesId']) }}";

                // Isi kembali province select sesuai dengan negara
                fetch(provinceUrl.replace(':provincesId', provinceId))
                    .then(response => response.json())
                    .then(data => {
                        var newOption = new Option("Select a City", "", false, false);
                        citySelect.append(newOption);

                        data.forEach(city => {
                            var isSelected = city.city_name == cityId;
                            newOption = new Option(city.city_name, city.city_name, false,
                                isSelected);
                            citySelect.append(newOption);
                        });


                        citySelect.trigger('change');
                    })
                    .catch(error => console.log(error));
            }
        }

        const filterGroups = document.querySelectorAll('.filter-group');

        filterGroups.forEach((element) => {
            element.addEventListener('click', (event) => {
                const targetId = element.getAttribute('filter-target');
                const targetElement = document.getElementById(targetId);
                const icon = element.querySelector('.filter-icon i');

                if (targetElement) {
                    targetElement.classList.toggle('active');
                    if (icon.classList.contains('fa-chevron-up')) {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    } else {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    }
                }
            });
        });

        const tabTrigger = document.querySelectorAll('.tab-trigger');
        const tabContainer = document.querySelectorAll('.tab-container');

        tabTrigger.forEach((element) => {
            element.addEventListener('click', (event) => {
                const targetId = element.getAttribute('tab-target');
                const targetElement = document.getElementById(targetId);

                tabTrigger.forEach((element) => {
                    element.classList.remove('active');
                });

                tabContainer.forEach((element) => {
                    element.classList.remove('active');
                });

                if (targetElement) {
                    event.currentTarget.classList.toggle('active');
                    targetElement.classList.toggle('active');
                }
            });
        });

        const cartPopupTrigger = document.querySelectorAll('.popup-trigger');

        cartPopupTrigger.forEach((element) => {
            element.addEventListener('click', (event) => {
                const popup = document.getElementById(element.getAttribute('target-popup'));

                popup.classList.toggle('active');

                if (popup.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        });

        // Function open detail publisher
        const openPublisherDetail = document.querySelectorAll('.open-publisher-detail');

        openPublisherDetail.forEach((element) => {
            element.addEventListener('click', (event) => {
                const openParameter = element.getAttribute('data-target');

                location.href = '{{ route('publisher') }}' + '/' + openParameter;
            });
        });

        // Komposer open detail komposer
        const openKomposerDetail = document.querySelectorAll('.open-komposer-detail');

        openKomposerDetail.forEach((element) => {
            element.addEventListener('click', (event) => {
                const openParameter = element.getAttribute('data-target');

                location.href = '{{ route('composer') }}' + '/' + openParameter;
            });
        });

        // Koleksi open detail koleksi
        const openKoleksiDetail = document.querySelectorAll('.open-koleksi-detail');

        openKoleksiDetail.forEach((element) => {
            element.addEventListener('click', (event) => {
                const openParameter = element.getAttribute('data-target');

                location.href = '{{ route('collection') }}' + '/' + openParameter;
            });
        });

        const requiredInput = document.querySelectorAll('input.required');

        function inputRequirementCheck() {
            requiredInput.forEach((element) => {
                element.addEventListener('click', (event) => {
                    if (elment.value == "") {
                        document.closest('.input-hint').classList.add('active');
                    } else {
                        document.closest('.input-hint').classList.remove('active');
                    }
                });
            });
        }
    });

    //remove-filter (filter tag)
    function removeFilterAssign() {
        const removeFilter = document.querySelectorAll('.remove-filter');

        removeFilter.forEach((element) => {
            element.addEventListener('click', (event) => {
                const filter = document.getElementById(element.getAttribute('data-target'));
                filter.checked = false;
                element.closest('.selected-filter').remove();
                fetchPartitur(1); // Panggil fetchPartitur() setelah filter dihapus
            });
        });
    }

    function removeFilterAssignMerchandise() {
        const removeFilter = document.querySelectorAll('.remove-filter-merchandise');

        removeFilter.forEach((element) => {
            element.addEventListener('click', (event) => {
                const filter = document.getElementById(element.getAttribute('data-target'));
                filter.checked = false;
                element.closest('.selected-filter-merchandise').remove();
                fetchMerchandise(1); // Panggil fetchPartitur() setelah filter dihapus
            });
        });
    }

    $(document).ready(function() {
        let owl = $(".home-carousel").owlCarousel({
            items: 1,
            loop: true,
            nav: true,
            navText: [
                '<span class="fa-solid fa-circle-chevron-left"></span>',
                '<span class="fa-solid fa-circle-chevron-right"></span>'
            ],
            navClass: ['owl-prev', 'owl-next'],
            autoplay: false,
            autoplayTimeout: 3000
        });

        let owl2 = $(".prod-img-list").owlCarousel({
            items: 3,
            loop: true,
            margin: 10,
            autoplay: false,
            dots: true
        });
    });
</script>

@if (request()->routeIs('collection') || request()->routeIs('collection-sort'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('collection-sort-by').addEventListener('click', function() {
                let currentSort = getCurrentSortFromUrl();
                let nextSort;

                if (currentSort === 'newest') {
                    nextSort = 'a-z';
                } else if (currentSort === 'a-z') {
                    nextSort = 'z-a';
                } else {
                    nextSort = 'newest';
                }

                window.location.href = `{{ route('collection-sort-blank') }}/${nextSort}`;
            });

            function getCurrentSortFromUrl() {
                const urlParams = new URLSearchParams(window.location.search);
                const pathSegments = window.location.pathname.split('/');
                const sortSegment = pathSegments.find(segment => segment === 'newest' || segment === 'a-z' ||
                    segment === 'z-a');

                return sortSegment;
            }
        });
    </script>
@endif

@if (request()->routeIs('publisher'))
    <script>
        $(document).ready(function() {
            filterAsignment();
            filterAsignmentMerchandise();

            fetchPartitur();
            fetchMerchandise();
            $('.sheetmusic-checkbox').change(function() {
                fetchPartitur(1);
            });
            $('.merchand-checkbox').change(function() {
                fetchMerchandise(1);
            });
        });

        function filterAsignment() {
            //checkbox checked element creation
            const customCheckbox = document.querySelectorAll('.custom-checkbox input');
            const selectedFilterContainer = document.querySelector('.selected-filter-container');

            customCheckbox.forEach((element) => {
                element.addEventListener('click', (event) => {
                    const filterValue = element.closest('.custom-checkbox').innerText;

                    if (element.checked == true) {
                        const filterElement = document.createElement('div');
                        filterElement.id = `remove-filter-${element.id}`;
                        filterElement.className = 'selected-filter';
                        filterElement.innerHTML =
                            `${filterValue} <button class="remove-filter"  data-target="${element.id}"><i class="fa-regular fa-circle-xmark"></i></button>`;

                        selectedFilterContainer.appendChild(filterElement);
                        removeFilterAssign();
                    } else {
                        document.getElementById(`remove-filter-${element.id}`).remove();
                    }
                });
            });
        }

        function filterAsignmentMerchandise() {
            //checkbox checked element creation
            const customCheckbox = document.querySelectorAll('.custom-checkbox-merchandise input');
            const selectedFilterContainer = document.querySelector('.selected-filter-container-merchandise');

            customCheckbox.forEach((element) => {
                element.addEventListener('click', (event) => {
                    const filterValue = element.closest('.custom-checkbox-merchandise').innerText;

                    if (element.checked == true) {
                        const filterElement = document.createElement('div');
                        filterElement.id = `remove-filter-merchandise-${element.id}`;
                        filterElement.className = 'selected-filter-merchandise';
                        filterElement.innerHTML =
                            `${filterValue} <button class="remove-filter-merchandise"  data-target="${element.id}"><i class="fa-regular fa-circle-xmark"></i></button>`;

                        selectedFilterContainer.appendChild(filterElement);
                        removeFilterAssignMerchandise();
                    } else {
                        document.getElementById(`remove-filter-merchandise-${element.id}`).remove();
                    }
                });
            });
        }

        function getQueryParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }
        
        function fetchPartitur(page = 1) {
            let details = {};
            $('input.sheetmusic-checkbox:checked').each(function() {
                let categoryId = $(this).data(
                    'category-id'); // Pastikan ini sesuai dengan data attribute pada checkbox
                let detailId = $(this).val();
                if (!details[categoryId]) {
                    details[categoryId] = [];
                }
                details[categoryId].push(detailId);
            });

            let dataToSend = {
                page: page,
                details: details,
            };

            let type = getQueryParameter('t');
            let id = getQueryParameter('s');

            if (type && id) {
                dataToSend.type = type;
                dataToSend.id = id;
            }

            // Iterasi setiap kategori untuk mengambil detail yang dipilih
            $('input.sheetmusic-checkbox:checked').each(function() {
                let name = $(this).attr('name');
                let value = $(this).val();
                if (!dataToSend[name]) {
                    dataToSend[name] = [];
                }
                dataToSend[name].push(value);
            });
            $.ajax({
                url: '{{ route('fetch.partitur') }}', // Pastikan route ini sesuai dengan yang Anda definisikan di Laravel
                data: dataToSend,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let partiturs = response.data;

                    if (partiturs.length === 0) {
                        $('.tab-container#partitur-tab .koleksi-container').html('<div class="no-data">No Data Found</div>');
                        $('.tab-container#partitur-tab .hint-datatable-page').html('Showing 0 to 0 of 0 items');
                    } else {
                        let html = '';
                        $.each(partiturs, function(index, partitur) {
                            let detailUrlTemplate = "{{ route('publisher.detail', ['name' => 'PLACEHOLDER']) }}";
                            let encodedName = encodeURIComponent(partitur.name);
                            let detailUrl = detailUrlTemplate.replace('PLACEHOLDER', encodedName);
                            let imgUrl = partitur.file_image_first ? 'public/' + partitur.file_image_first :
                                'assets/images/favicon.png';
                            let classCover = partitur.file_image_first ? '' : 'class="contain-img-remove"';

                            html += '<a class="tab-koleksi-link" href="' + detailUrl +
                                '" data-target="' + partitur.id + '">' +
                                '<img '+classCover+' src="' + imgUrl +
                                '" onerror="this.onerror=null; this.src=\'assets/images/favicon.png\'" />' +
                                '<div class="tab-koleksi-title">' + partitur.name + '</div>' +
                                '<div class="tab-koleksi-sub"></div>' +
                                '</a>';
                        });
                        $('.tab-container#partitur-tab .koleksi-container').html(html);
                        updatePagination(response);
                    }
                }
            });
        }

        function fetchMerchandise(page = 1) {
            let details = {};
            $('input.merchand-checkbox:checked').each(function() {
                let categoryId = $(this).data(
                    'category-id'); // Pastikan ini sesuai dengan data attribute pada checkbox
                let detailId = $(this).val();
                if (!details[categoryId]) {
                    details[categoryId] = [];
                }
                details[categoryId].push(detailId);
            });

            let dataToSendMerchand = {
                page: page,
                details: details
            };

            let type = getQueryParameter('t');
            let id = getQueryParameter('s');

            if (type && id) {
                dataToSendMerchand.type = type;
                dataToSendMerchand.id = id;
            }

            // Iterasi setiap kategori untuk mengambil detail yang dipilih
            $('input.merchand-checkbox:checked').each(function() {
                let name = $(this).attr('name');
                let value = $(this).val();
                if (!dataToSendMerchand[name]) {
                    dataToSendMerchand[name] = [];
                }
                dataToSendMerchand[name].push(value);
            });
            $.ajax({
                url: '{{ route('fetch.merchandise') }}',
                data: dataToSendMerchand,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let merchandises = response.data;

                    if (merchandises.length === 0) {
                        $('.tab-container#merchandise-tab .koleksi-container').html('<div class="no-data">No Data Found</div>');
                        $('.tab-container#merchandise-tab .hint-datatable-page2').html('Showing 0 to 0 of 0 items');
                    } else {
                        let html = '';
                        $.each(merchandises, function(index, merchandise) {
                            let detailUrlTemplate = "{{ route('merchandise.detail', ['name' => 'PLACEHOLDER']) }}";
                            let encodedName = encodeURIComponent(merchandise.name);
                            let detailUrl = detailUrlTemplate.replace('PLACEHOLDER', encodedName);
                            var imgUrl = merchandise.photo ? 'public/' + merchandise.photo :
                                'assets/images/favicon.png';

                            let classCover = merchandise.photo ? '' : 'class="contain-img-remove"';

                            html += '<a class="tab-koleksi-link" href="' + detailUrl +
                                '" data-target="' + merchandise.id + '">' +
                                '<img '+classCover+' src="' + imgUrl +
                                '" onerror="this.onerror=null; this.src=\'assets/images/favicon.png\'" />' +
                                '<div class="tab-koleksi-title">' + merchandise.name + '</div>' +
                                '<div class="tab-koleksi-sub"></div>' +
                                '</a>';
                        });
                        $('.tab-container#merchandise-tab .koleksi-container').html(html);
                        updatePaginationMerchandise(response);
                    }
                }
            });
        }

        function updatePagination(data) {
            let paginationHtml = '';
            let showingHint = `Showing ${data.from ? data.from : 0} to ${data.to ? data.to : 0} of ${data.total} items`;
            $('.hint-datatable-page').text(showingHint);

            if (data.prev_page_url) {
                paginationHtml += '<button onclick="fetchPartitur(' + (data.current_page - 1) +
                    ')"><i class="fa-solid fa-angle-left"></i></button>';
            }

            // Menghasilkan button untuk semua halaman
            for (let i = 1; i <= data.last_page; i++) {
                paginationHtml +=
                    `<button ${data.current_page === i ? 'class="active"' : ''} onclick="fetchPartitur(${i})">${i}</button>`;
            }

            if (data.next_page_url) {
                paginationHtml += '<button onclick="fetchPartitur(' + (data.current_page + 1) +
                    ')"><i class="fa-solid fa-angle-right"></i></button>';
            }

            $('.navigation-datatable-page').html(paginationHtml);
        }

        function updatePaginationMerchandise(data) {
            let paginationHtml = '';
            let showingHint = `Showing ${data.from ? data.from : 0} to ${data.to ? data.to : 0} of ${data.total} items`;
            $('.hint-datatable-page2').text(showingHint);

            if (data.prev_page_url) {
                paginationHtml += '<button onclick="fetchMerchandise(' + (data.current_page - 1) +
                    ')"><i class="fa-solid fa-angle-left"></i></button>';
            }

            // Menghasilkan button untuk semua halaman
            for (let i = 1; i <= data.last_page; i++) {
                paginationHtml +=
                    `<button ${data.current_page === i ? 'class="active"' : ''} onclick="fetchMerchandise(${i})">${i}</button>`;
            }

            if (data.next_page_url) {
                paginationHtml += '<button onclick="fetchMerchandise(' + (data.current_page + 1) +
                    ')"><i class="fa-solid fa-angle-right"></i></button>';
            }

            $('.navigation-datatable-page2').html(paginationHtml);
        }
    </script>
@endif
