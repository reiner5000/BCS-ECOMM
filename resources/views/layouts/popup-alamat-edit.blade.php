<div class="popup-center" id="edit-alamat">
    <div class="cart-modal">
        <div class="modal-row sticky-title">
            <div class="modal-title">Edit Address</div>
            <button class="modal-exit popup-trigger" target-popup="edit-alamat"><i class="fa-solid fa-x"></i></button>
        </div>

        <form action="{{ route('update-shipment') }}" method="POST" id="alamatFormEdit">
            @csrf
            <div class="modal-col">
                <div class="form-col">
                    <div class="col-form-group">
                        <label for="nama-penerima">Recipient's Name</label>
                        <input type="hidden" id="alamat-id" name="alamat-id" />
                        <input class="required" id="nama-penerima" placeholder="Recipient's Name" name="nama-penerima"
                            />
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="nomor-penerima">Mobile Phone Number</label>
                        <input class="required" name="nomor-penerima" id="nomor-penerima"
                            placeholder="Mobile Phone Number" />
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="alamat-penerima">Address</label>
                        <input class="required" name="alamat" id="alamat-penerima" placeholder="Address" />
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="informasi-penerima">Additional information</label>
                        <input id="informasi-penerima" name="informasi-penerima"
                            placeholder="(example: block/unit no)" />
                    </div>

                    <div class="col-form-group">
                        <label for="informasi-penerima">Country Name</label>
                        <select class="required" id="country-select2" name="negara" placeholder="Country Name" >
                            <option value="">Select a country</option>
                            @foreach ($countries as $c)
                                <option value="{{ $c->country_name }}">{{ $c->country_name }}</option>
                            @endforeach
                        </select>
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group" id="province-select-group2">
                        <label for="informasi-penerima">Province Name</label>
                        <select  id="province-select2" name="provinsi" id="provinsi">
                            <option value="">Select a Province</option>
                        </select>
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group" id="province-input-group2" style="display:none;">
                        <label for="provinsi-input">Province Name</label>
                        <input type="text" id="provinsi-input2" name="provinsi-input" placeholder="Province Name" />
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group" id="city-select-group2">
                        <label for="informasi-penerima">City Name</label>
                        <select id="city-select2" name="kota" id="kota">
                            <option value="">Select a City</option>
                        </select>
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group" id="city-input-group2" style="display:none;">
                        <label for="kota-input">City Name</label>
                        <input type="text" id="kota-input2" name="kota-input" placeholder="City Name" />
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="kecamatan">Subdistrict</label>
                        <input class="required" id="kecamatan" name="kecamatan" placeholder="Subdistrict Name" />
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="kote_pos">Post Code</label>
                        <input class="required" type="number" id="kote_pos" name="kode_pos" placeholder="Post Code" />
                        <div class="input-hint">*Required to fill*</div>
                    </div>
                </div>
            </div>

            <div class="modal-row right-flex margin-top-auto pb-30">
                <button type="button" class="btn btn-black popup-trigger" target-popup="edit-alamat">Cancel</button>
                <button type="button" class="btn-white btn" onclick="submitAlamatEdit()">Save</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#country-select2').select2({
            placeholder: "Select a country",
        });

        $('#province-select2').select2({
            placeholder: "Select a province",
        }).on('select2:select', function(e) {
            // Hanya tangani event ketika pengguna memilih sebuah opsi
            loadCitiesBasedOnProvince2(e.params.data.id);
        });

        $('#city-select2').select2({
            placeholder: "Select a city",
        });

        $('#country-select2').on('change', function() {
            var countryId = this.value;
            var provinceSelectGroup = $('#province-select-group2');
            var provinceSelect = $('#province-select2');
            var provinceInputGroup = $('#province-input-group2');
            var provinceInput = $('#provinsi-input2');
            var citySelectGroup = $('#city-select-group2');
            var citySelect = $('#city-select2');
            var cityInputGroup = $('#city-input-group2');
            var cityInput = $('#kota-input2');

            // Mengosongkan dropdown province dan inputan
            $('#province-select2').empty().trigger('change');
            $('#city-select2').empty().trigger('change');
            $('#provinsi-input2').val('');
            $('#kota-input2').val('');

            provinceSelect.removeClass('required');
            provinceInput.removeClass('required');
            citySelect.removeClass('required');
            cityInput.removeClass('required');

            if (countryId === '236' || countryId ===
                'Indonesia') { // Anggap '236' adalah ID untuk Indonesia
                provinceSelectGroup.show();
                provinceInputGroup.hide();
                citySelectGroup.show();
                cityInputGroup.hide();

                provinceSelect.addClass('required');
                citySelect.addClass('required');
                
                provinceSelectGroup.addClass('active');
                provinceInputGroup.removeClass('active');
                citySelectGroup.addClass('active');
                cityInputGroup.removeClass('active');

                let provinceUrl =
                    "{{ route('getProvincesByCountry', ['country_id' => ':countryId']) }}";

                // Isi kembali province select sesuai dengan negara
                fetch(provinceUrl.replace(':countryId', countryId))
                    .then(response => response.json())
                    .then(data => {
                        var newOption = new Option("Select a Province", "", false, false);
                        $('#province-select2').append(newOption).trigger('change');

                        data.forEach(province => {
                            newOption = new Option(province.province, province.province,
                                false, false);
                            $('#province-select2').append(newOption).trigger('change');
                        });

                        // Set ulang placeholder setelah data dimuat
                        $('#province-select2').select2({
                            placeholder: "Select a province"
                        });
                    })
                    .catch(error => console.log(error));
            } else {
                provinceSelectGroup.hide();
                provinceInputGroup.show();
                citySelectGroup.hide();
                cityInputGroup.show();

                provinceInput.addClass('required');
                cityInput.addClass('required');
            }
        });

        function loadCitiesBasedOnProvince2(provinceId) {
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
                            newOption = new Option(city.city_name, city.city_name, false,
                                false); // Asumsikan response mengandung id
                            citySelect.append(newOption);
                        });

                        citySelect.trigger('change');
                    })
                    .catch(error => console.log(error));
            }
        }
    });
    function submitAlamatEdit() {
        let form = document.getElementById('alamatFormEdit');
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
</script>
