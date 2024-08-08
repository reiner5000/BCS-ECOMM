@extends('admin.layouts.master')
@section('title', 'Add Merchandise')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Merchandise</h1>
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
                            <h3 class="card-title" id="card-title">Add Merchandise Data</h3>
                        </div>
                        <form action="{{ route('merchandise.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div id="partiturMaster">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nama">Merchandise Name (*)</label>
                                        <input type="text" class="form-control" name="nama" id="nama"
                                            placeholder="Enter Merchandise Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="composer">Category (*)</label>
                                        <select class="form-control select2" name="category[]" id="category" multiple>
                                            @foreach ($category as $c)
                                                <option value="{{ $c->id }}">{{ $c->name }} ({{$c->category->name}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="harga">Price (*)</label>
                                        <input type="number" class="form-control" name="harga" id="harga" placeholder="Enter Price" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="stok">Stock (*)</label>
                                        <input type="number" class="form-control" name="stok" id="stok" placeholder="Enter Stock" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description (*)</label>
                                        <textarea class="form-control" name="description" id="description" placeholder="Enter Description" rows=3
                                            required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="collection">Image (*Can choose > 1)</label>
                                        <input type="file" class="form-control" name="file_images[]" id="file_image"
                                            multiple required accept="image/png,image/jpeg">
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" id="nextToDetails" class="btn btn-primary">Next</button>
                                </div>
                            </div>

                            <div id="partiturDetails" style="display: none;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div id="partiturDetailTemplate">
                                                <div class="partitur-detail-section">
                                                    <h3>Size <span class="detail-number">1</span></h3>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="size_detail[]"
                                                                    placeholder="Enter Size" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div id="partiturDetailTemplate2">
                                                <div class="partitur-detail-section2">
                                                    <h3>Color <span class="detail-number2">1</span></h3>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                                <div class="form-group">
                                                            <input type="text" class="form-control" name="color_detail[]"
                                                                    placeholder="Enter Color" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" id="addPartiturDetail" class="btn btn-info">Add Size</button>
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
            
            let detailCount = 1;
            let detailCount2 = 2;

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
                                placeholder="Enter Size">
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
                                placeholder="Enter Color">
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
