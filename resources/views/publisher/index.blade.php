<html>

<head>
    @include('layouts.import')
    <title>Bandung Choral Society | Publisher</title>
</head>

<body>
    @include('layouts.header')
    <style>
        .col-group {
            padding-bottom: 10px;
        }   
    </style>

    <div class="resp-page-section left-flex no-first" style="margin-bottom: 60px !important;">
        <div class="section-title">PUBLISHER</div>
        <hr />
        <div class="section-row flex-start l-col ">
            <div class="min-col l-popup-trigger-container">
                <div class="filter-title">Filter Data</div>

                <div class="selected-filter-container">

                </div>

                <div class="selected-filter-container-merchandise" style="display:none">

                </div>

                <div class="section-col l-row mt-20 flex-wrap">
                    {{-- filter group php --}}
                    @foreach ($categories1 as $cat)
                        <div class="col-group">
                            <div class="filter-group" filter-target="fg-{{ $cat->id }}">
                                <div class="filter-name" style="width:100%">{{ $cat->name }}</div>
                                <div class="filter-icon"><i class="fa-solid fa-chevron-up"></i></div>
                            </div>
                            <div id="fg-{{ $cat->id }}"
                                class="filter-list {{ request('c') && $cat->details->contains('name', request('c')) ? 'active' : '' }}">
                                @foreach ($cat->details as $item)
                                    <label class="custom-checkbox">
                                        <input type="checkbox" class="sheetmusic-checkbox" id="checkbox{{ $item->id }}"
                                            name="details[{{ $cat->id }}][]" value="{{ $item->id }}"
                                            {{ $item->name == request('c') ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                        {{ $item->name }}
                                    </label>
                                    @if ($item->name == request('c'))
                                        <script>
                                            const filterElement2 = document.createElement('div');
                                            filterElement2.id = `remove-filter-checkbox{{ $item->id }}`;
                                            filterElement2.className = 'selected-filter';
                                            filterElement2.innerHTML =
                                                `{{ $item->name }} <button class="remove-filter"  data-target="checkbox{{ $item->id }}"><i class="fa-regular fa-circle-xmark"></i></button>`;

                                            document.querySelector('.selected-filter-container').appendChild(filterElement2);
                                            const removeFilter2 = document.querySelectorAll('.remove-filter');
                                            removeFilter2.forEach((element) => {
                                                element.addEventListener('click', (event) => {
                                                    const filter2 = document.getElementById(element.getAttribute('data-target'));
                                                    filter2.checked = false;
                                                    element.closest('.selected-filter').remove();
                                                    fetchPartitur(1);
                                                });
                                            });
                                        </script>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                    @endforeach
                    {{-- end --}}
                </div>
                                            
                <div class="section-col-merchandise l-row mt-20 flex-wrap">
                    {{-- filter group php --}}
                    @foreach ($categories2 as $cat2)
                        <div class="col-group">
                            <div class="filter-group" filter-target="fg-{{ $cat2->id }}">
                                <div class="filter-name" style="width:100%">{{ $cat2->name }}</div>
                                <div class="filter-icon"><i class="fa-solid fa-chevron-up"></i></div>
                            </div>
                            <div id="fg-{{ $cat2->id }}"
                                class="filter-list">
                                @foreach ($cat2->details as $item)
                                    <label class="custom-checkbox-merchandise">
                                        <input type="checkbox" class="merchand-checkbox" id="checkbox{{ $item->id }}"
                                            name="details[{{ $cat2->id }}][]" value="{{ $item->id }}">
                                        <span class="checkmark"></span>
                                        {{ $item->name }}
                                    </label>
                                @endforeach

                            </div>
                        </div>
                    @endforeach
                    {{-- end --}}
                </div>
            </div>

            <div class="max-col">
                <div class="trigger-container">
                    <div id="btn-partitur" class="tab-trigger @if ($type != 'merchandise') active @endif"
                        tab-target="partitur-tab">Sheet Music</div>
                    <div id="btn-merchandise" class="tab-trigger @if ($type == 'merchandise') active @endif"
                        tab-target="merchandise-tab">Merchandise</div>
                </div>

                <!-- TAB KOLEKSI -->
                <div class="tab-container @if ($type != 'merchandise') active @endif" id="partitur-tab">
                    <div class="koleksi-container">
                        {{-- dari js fetchPartitur --}}
                    </div>

                    <div class="grid-datatable-page">
                        <div class="hint-datatable-page"></div>
                        <!-- Tempat untuk menunjukkan info "showing X from Y items" -->
                        <div class="navigation-datatable-page">
                            <!-- Button pagination akan diisi oleh JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- TAB MERCHANDISE -->
                <div class="tab-container @if ($type == 'merchandise') active @endif" id="merchandise-tab">
                    <div class="koleksi-container">
                        {{-- @foreach ($merchandise as $m)
                            <a class="tab-koleksi-link open-publisher-detail" data-target="1">
                                <img
                                    src="{{ file_exists('public/' . $m->photo) && $m->photo ? asset('public/' . $m->photo) : 'assets/images/favicon.png' }}" />
                                <div class="tab-koleksi-title">{{ $m->name }}</div>
                                <div class="tab-koleksi-sub "></div>
                            </a>
                        @endforeach --}}
                    </div>
                    <div class="grid-datatable-page">
                        <div class="hint-datatable-page2"></div>
                        <div class="navigation-datatable-page2">
                            <!-- Button pagination akan diisi oleh JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".selected-filter-container, .section-col").show();
            $(".selected-filter-container, .section-col-merchandise").hide();

            $(".selected-filter-container").show();
            $(".selected-filter-container-merchandise").hide();
            
            $("#btn-merchandise").click(function() {
                $(".selected-filter-container, .section-col").hide();
                $(".selected-filter-container, .section-col-merchandise").show();

                $(".selected-filter-container").hide();
                $(".selected-filter-container-merchandise").show();
            });

            $("#btn-partitur").click(function() {
                $(".selected-filter-container, .section-col").show();
                $(".selected-filter-container, .section-col-merchandise").hide();

                $(".selected-filter-container").show();
                $(".selected-filter-container-merchandise").hide();
            });
        });
    </script>
    @include('layouts.footer')
</body>

</html>
