@extends('admin.layouts.master')
@section('title', 'Edit Merchandise')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Merchandise</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('merchandise.index') }}" class="btn btn-secondary float-sm-right"><i
                            class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                </div>
            </div>
        </div>
        @if (session('success'))
            <br>
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <br>
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" id="card-title">Edit Merchandise Data</h3>
                        </div>
                        <form action="{{ route('merchandise.update', ['merchandise' => $data->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div id="partiturMaster">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nama">Merchandise Name (*)</label>
                                        <input type="text" class="form-control" name="nama" id="nama"
                                            placeholder="Enter Merchandise Name" value="{{$data->name}}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="composer">Category (*)</label>
                                        <select class="form-control select2" name="category[]" id="category" multiple>
                                            @foreach ($category as $c)
                                            @php
                                                $selectedIds = explode(',', $data->category_detail_id);
                                            @endphp
                                            <option value="{{ $c->id }}"
                                                    @if (in_array($c->id, $selectedIds)) selected @endif>
                                                {{ $c->name }} ({{ $c->category->name }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="harga">Price (*)</label>
                                        <input type="number" class="form-control" name="harga" id="harga" value="{{$data->harga}}" placeholder="Enter Price" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="stok">Stock (*)</label>
                                        <input type="number" class="form-control" name="stok" id="stok" value="{{$data->stok}}" placeholder="Enter Stock" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description (*)</label>
                                        <textarea class="form-control" name="description" id="description" placeholder="Enter Description" rows=3
                                            required>{{$data->deskripsi}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="collection">Image (*Can choose > 1)</label>
                                        <input type="file" class="form-control" name="file_images[]" id="file_image"
                                            multiple accept="image/png,image/jpeg">
                                    </div>
                                    <div class="form-group">
                                        <label>Uploaded Image</label>
                                        <div class="uploaded-images">
                                            @if ($data->photo)
                                                @php
                                                    $images = explode(',', $data->photo);
                                                @endphp
                                                @foreach ($images as $image)
                                                    <div class="uploaded-image" data-image="{{ $image }}">
                                                        <img src="{{ asset('public/' . $image) }}" alt="Uploaded Image"
                                                            style="width: 150px; height: auto;">
                                                        <button type="button" class="btn btn-danger btn-sm remove-image"
                                                            data-image="{{ 'public/' . $image }}">Delete</button>
                                                    <br>
                                                    <br>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <input type="hidden" id="imagesToDelete" name="imagesToDelete" value="">
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" id="nextToDetails" class="btn btn-primary">Next</button>
                                </div>
                            </div>

                            <div id="partiturDetails" style="display: none;">
                                <input type="hidden" id="idDelete" name="idDelete">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div id="partiturDetailTemplate">
                                                @php($no1 = 0)
                                                @foreach ($data->details as $d)
                                                @if($d->size != '')
                                                @php($no1++)
                                                <div class="merchandise-detail-section">
                                                    <div class="partitur-detail-section">
                                                        <h3>Size <span class="detail-number">{{ $no1 }}</span>
                                                            <button type="button"
                                                            class="btn btn-danger remove-merchandise-detail-id">Delete</button>
                                                        </h3>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <input type="hidden" class="form-control" name="id_detail[]"
                                                                        value="{{ $d->id }}">
                                                                    <input type="text" class="form-control" name="size_detail[]"
                                                                        placeholder="Enter Size" value="{{$d->size}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div id="partiturDetailTemplate2" >
                                                @php($no2 = 0)
                                                @foreach ($data->details as $d)
                                                @if($d->color != '')
                                                    @php($no2++)
                                                    <div class="merchandise-detail-section2">
                                                        <div class="partitur-detail-section2">
                                                            <h3>Color <span class="detail-number2">{{ $no2 }}</span>
                                                                <button type="button"
                                                                class="btn btn-danger remove-merchandise-detail-id2">Delete</button>
                                                            </h3>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                    <input type="hidden" class="form-control" name="id_detail2[]"
                                                                        value="{{ $d->id }}">
                                                                    <input type="text" class="form-control" name="color_detail[]"
                                                                            placeholder="Enter Color" value="{{$d->color}}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" id="addPartiturDetail" class="btn btn-info">Add Row</button>
                                    &nbsp;
                                    <button type="button" id="addPartiturDetail2" class="btn btn-info">Add Color</button>
                                    &nbsp;
                                    <button type="button" id="backToMaster" class="btn btn-secondary">Back</button>
                                    &nbsp;
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $('.remove-image').click(function() {
                var image = $(this).data('image');
                var imagesToDelete = $('#imagesToDelete').val();
                if (imagesToDelete) {
                    imagesToDelete += ',' + image;
                } else {
                    imagesToDelete = image;
                }
                $('#imagesToDelete').val(imagesToDelete);

                $(this).closest('.uploaded-image').remove();
            });

            $('.select2').select2({
                theme: 'bootstrap4'
            })
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            const nextToDetailsBtn = document.getElementById('nextToDetails');
            const partiturMasterSection = document.getElementById('partiturMaster');
            const partiturDetailsSection = document.getElementById('partiturDetails');
            const backToMasterBtn = document.getElementById('backToMaster');
            const cardTitle = document.getElementById('card-title');

            nextToDetailsBtn.addEventListener('click', function() {
                var nama = document.getElementById('nama').value;
                var description = document.getElementById('description').value;
                var price = document.getElementById('harga').value;
                var stock = document.getElementById('stok').value;

                if (nama.trim() == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Merchandise Name Required!',
                        text: '',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }  else if (price == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Price Required!',
                        text: '',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else if (stock == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Stock Required!',
                        text: '',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else if (description.trim() == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Description Required!',
                        text: '',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    cardTitle.textContent = 'Add Detail Merchandise Data';
                    partiturMasterSection.style.display = 'none';
                    partiturDetailsSection.style.display = 'block';
                }
            });

            backToMasterBtn.addEventListener('click', function() {
                cardTitle.textContent = 'Add Merchandise Data';
                partiturMasterSection.style.display = 'block';
                partiturDetailsSection.style.display = 'none';
            });

            const addPartiturDetailBtn = document.getElementById('addPartiturDetail');
            const addPartiturDetailBtn2 = document.getElementById('addPartiturDetail2');

            let detailCount = {{$no1}};
            let detailCount2 = {{$no2}};

            $('.remove-merchandise-detail-id').click(function() {
                var currentIdDeleteValue = $('#idDelete').val();
                var idDetail = $(this).closest('.merchandise-detail-section').find('input[name="id_detail[]"]')
                    .val();

                if (currentIdDeleteValue) {
                    $('#idDelete').val(currentIdDeleteValue + ',' + idDetail);
                } else {
                    $('#idDelete').val(idDetail);
                }

                $(this).closest('.merchandise-detail-section').remove();
                detailCount--;
                renumberDetails();
            });

            $('.remove-merchandise-detail-id2').click(function() {
                var currentIdDeleteValue = $('#idDelete').val();
                var idDetail = $(this).closest('.merchandise-detail-section2').find('input[name="id_detail2[]"]')
                    .val();

                if (currentIdDeleteValue) {
                    $('#idDelete').val(currentIdDeleteValue + ',' + idDetail);
                } else {
                    $('#idDelete').val(idDetail);
                }

                $(this).closest('.merchandise-detail-section2').remove();
                detailCount2--;
                renumberDetails2();
            });

            function addPartiturDetail() {
                detailCount++;
                const detailSection = document.createElement('div');
                detailSection.classList.add('partitur-detail-section');
                detailSection.innerHTML = `
                <h3>Size <span class='detail-number'>${detailCount}</span> <button type="button" class="btn btn-danger remove-partitur-detail">Delete</button></h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" class="form-control" name="size_detail[]"
                                placeholder="Enter Size" >
                        </div>
                    </div>
                </div>
            `;

                detailSection.querySelector('.remove-partitur-detail').addEventListener('click', function() {
                    detailSection.remove();
                    detailCount--;
                    renumberDetails();
                });

                const partiturDetailsContainer = document.querySelector('#partiturDetailTemplate');

                partiturDetailsContainer.appendChild(detailSection);
                renumberDetails();
            }

            function addPartiturDetail2() {
                detailCount2++;
                const detailSection2 = document.createElement('div');
                detailSection2.classList.add('partitur-detail-section2');
                detailSection2.innerHTML = `
                <h3>Color <span class='detail-number2'>${detailCount2}</span> <button type="button" class="btn btn-danger remove-partitur-detail2">Delete</button></h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" class="form-control" name="color_detail[]"
                                placeholder="Enter Color" >
                        </div>
                    </div>
                </div>
            `;

                detailSection2.querySelector('.remove-partitur-detail2').addEventListener('click', function() {
                    detailSection2.remove();
                    detailCount2--;
                    renumberDetails2();
                });

                const partiturDetailsContainer = document.querySelector('#partiturDetailTemplate2');

                partiturDetailsContainer.appendChild(detailSection2);
                renumberDetails2();
            }

            addPartiturDetailBtn.addEventListener('click', addPartiturDetail);
            addPartiturDetailBtn2.addEventListener('click', addPartiturDetail2);

            function renumberDetails() {
                const allDetails = document.querySelectorAll('.partitur-detail-section');
                allDetails.forEach((detailSection, index) => {
                    const detailNumberSpan = detailSection.querySelector('.detail-number');
                    if (detailNumberSpan) {
                        detailNumberSpan.textContent = index + 1;
                    }
                });
            }

            function renumberDetails2() {
                const allDetails2 = document.querySelectorAll('.partitur-detail-section2');
                allDetails2.forEach((detailSection2, index) => {
                    const detailNumberSpan = detailSection2.querySelector('.detail-number2');
                    if (detailNumberSpan) {
                        detailNumberSpan.textContent = index + 1;
                    }
                });
            }

        });
    </script>
@endsection
