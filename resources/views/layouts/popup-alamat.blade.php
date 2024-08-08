<style>
    .select2-container {
        border: 1px solid #e2e2e2 !important;
    }
</style>
<div class="popup-center" id="right-alamat">
    <div class="cart-modal">
        <div class="modal-row sticky-title">
            <div class="modal-title">Add New Address</div>
            <button class="modal-exit popup-trigger" target-popup="right-alamat"><i class="fa-solid fa-x"></i></button>
        </div>

        <form action="{{ route('save-shipment') }}" method="POST" id="alamatForm">
            @csrf
            <div class="modal-col">
                <div class="form-col">
                    @if (request()->routeIs('checkout'))
                        <input type="hidden" id="from" name="from" value="checkout" />
                    @else
                        <input type="hidden" id="from" name="from" value="address" />
                    @endif
                    <div class="col-form-group">
                        <label for="nama-penerima">Recipient's Name</label>
                        <input type="hidden" id="alamat-id" name="alamat-id" />
                        <input class="required" id="nama-penerima" placeholder="Recipient's Name" name="nama-penerima"
                            required />
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="nomor-penerima">Mobile Phone Number</label>
                        <input class="required" name="nomor-penerima" id="nomor-penerima"
                            placeholder="Mobile Phone Number" required />
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="alamat-penerima">Address</label>
                        <input class="required" name="alamat" id="alamat-penerima" placeholder="Address" required />
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="informasi-penerima">Additional information</label>
                        <input id="informasi-penerima" name="informasi-penerima"
                            placeholder="(example: block/unit no)" />
                    </div>

                    <div class="col-form-group">
                        <label for="informasi-penerima">Country Name</label>
                        <select class="required" id="country-select" name="negara" placeholder="Country Name" required>
                            <option value="">Select a country</option>
                            @foreach ($countries as $c)
                                <option value="{{ $c->country_name }}">{{ $c->country_name }}</option>
                            @endforeach
                        </select>
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group" id="province-select-group">
                        <label for="informasi-penerima">Province Name</label>
                        <select class="required" id="province-select" name="provinsi" id="provinsi">
                            <option value="">Select a Province</option>
                        </select>
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group" id="province-input-group" style="display:none;">
                        <label for="provinsi-input">Province Name</label>
                        <input type="text" id="provinsi-input" name="provinsi-input" placeholder="Province Name" />
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group" id="city-select-group">
                        <label for="informasi-penerima">City Name</label>
                        <select class="required" id="city-select" name="kota" id="kota">
                            <option value="">Select a City</option>
                        </select>
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group" id="city-input-group" style="display:none;">
                        <label for="kota-input">City Name</label>
                        <input type="text" id="kota-input" name="kota-input" placeholder="City Name" />
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="informasi-penerima">Subdistrict</label>
                        <input class="required" id="informasi-penerima" name="kecamatan" placeholder="Subdistrict Name" required />
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="informasi-penerima">Post Code</label>
                        <input class="required" type="number" id="informasi-penerima" name="kode_pos" placeholder="Post Code"
                            required />
                        <div class="input-hint">*Required to fill*</div>
                    </div>
                </div>
            </div>

            <div class="modal-row right-flex margin-top-auto pb-30">
                <button type="button" class="btn btn-black popup-trigger" target-popup="right-alamat">Cancel</button>
                <button type="button" id="submitBtn" class="btn-white btn" onclick="disableButton()">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#country-select').select2({
            placeholder: "Select a country",
        });

        $('#province-select').select2({
            placeholder: "Select a province",
        }).on('select2:select', function(e) {
            // Hanya tangani event ketika pengguna memilih sebuah opsi
            loadCitiesBasedOnProvince(e.params.data.id);
        });

        $('#city-select').select2({
            placeholder: "Select a city",
        });

        $('#country-select').on('change', function() {
            var countryId = this.value;
            var provinceSelectGroup = $('#province-select-group');
            var provinceSelect = $('#province-select');
            var provinceInputGroup = $('#province-input-group');
            var provinceInput = $('#provinsi-input');
            var citySelectGroup = $('#city-select-group');
            var citySelect = $('#city-select');
            var cityInputGroup = $('#city-input-group');
            var cityInput = $('#kota-input');

            // Mengosongkan dropdown province dan inputan
            $('#province-select').empty().trigger('change');
            $('#city-select').empty().trigger('change');
            $('#provinsi-input').val('');
            $('#kota-input').val('');

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

                // Isi kembali province select sesuai dengan negara
                let provinceUrl =
                    "{{ route('getProvincesByCountry', ['country_id' => ':countryId']) }}";

                // Isi kembali province select sesuai dengan negara
                fetch(provinceUrl.replace(':countryId', countryId))
                    .then(response => response.json())
                    .then(data => {
                        var newOption = new Option("Select a Province", "", false, false);
                        $('#province-select').append(newOption).trigger('change');

                        data.forEach(province => {
                            newOption = new Option(province.province, province.province,
                                false, false);
                            $('#province-select').append(newOption).trigger('change');
                        });

                        // Set ulang placeholder setelah data dimuat
                        $('#province-select').select2({
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

        function loadCitiesBasedOnProvince(provinceId) {
            var citySelect = $('#city-select');
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
</script>
