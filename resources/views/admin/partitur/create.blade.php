@extends('admin.layouts.master')
@section('title', 'Add Sheet Music')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Sheet Music</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('sheet-music.index') }}" class="btn btn-secondary float-sm-right"><i
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
                            <h3 class="card-title" id="card-title">Add Sheet Music Data</h3>
                        </div>
                        <form action="{{ route('sheet-music.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div id="partiturMaster">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nama">Sheet Music Name (*)</label>
                                        <input type="text" class="form-control" name="nama" id="nama"
                                            placeholder="Enter Sheet Music Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description (*)</label>
                                        <textarea class="form-control" name="description" id="description" placeholder="Enter Description" rows=3
                                            required></textarea>
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
                                        <label for="composer">Composer (*)</label>
                                        <select class="form-control select2" name="composer" id="composer">
                                            <option value="">-- Choose Composer --</option>
                                            @foreach ($composers as $composer)
                                                <option value="{{ $composer->id }}">{{ $composer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="collection">Collection (*)</label>
                                        <select class="form-control select2" name="collection" id="collection">
                                            <option value="">-- Choose Collection --</option>
                                            @foreach ($collections as $collection)
                                                <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="collection">Image (*Can choose > 1)</label>
                                        <input type="file" class="form-control" name="file_images[]" id="file_image"
                                            multiple accept="image/png,image/jpeg" required>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" id="nextToDetails" class="btn btn-primary">Next</button>
                                </div>
                            </div>

                            <div id="partiturDetails" style="display: none;">
                                <div class="card-body">
                                    <div id="partiturDetailTemplate">
                                        <div class="partitur-detail-section">
                                            <h2>Detail <span class="detail-number">1</span></h2>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="nama_detail">Detail Name (*)</label>
                                                        <input type="text" class="form-control" name="nama_detail[]"
                                                            placeholder="Enter Detail Name" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="file_type">File Type (*)</label>
                                                        <select class="form-control" name="file_type[]">
                                                            <option value="hardcopy">Hard Copy</option>
                                                            <option value="softcopy">Soft Copy</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="harga">Price (*)</label>
                                                        <input type="number" class="form-control" name="harga[]"
                                                            placeholder="Enter Price" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="minimum_order">Minimum Order (*)</label>
                                                        <input type="number" class="form-control" name="minimum_order[]"
                                                            placeholder="Enter Minimum Order" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="preview_audio">Audio Preview</label>
                                                        <input type="text" class="form-control" name="preview_audio[]"
                                                            placeholder="Enter Embed Preview Audio">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="preview_video">Video Preview</label>
                                                        <input type="text" class="form-control" name="preview_video[]"
                                                             placeholder="Enter Embed Preview Video">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="preview_partitur">Sheet Music Preview</label>
                                                        <input type="file" class="form-control"
                                                            name="preview_partitur[]" accept="image/png,image/jpeg">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="sheet_music">Original Sheet Music</label>
                                                        <input type="file" class="form-control"
                                                            name="sheet_music[]">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- <div class="row"> --}}
                                            <div class="form-group">
                                                <label for="nama">Description (*)</label>
                                                <textarea name="deskripsidet[]" id="deskripsidet" rows="5" class="form-control"
                                                    placeholder="Enter Description"></textarea>
                                            </div>
                                            {{-- </div> --}}

                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="button" id="addPartiturDetail" class="btn btn-info">Add Row</button>
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
                var composer = document.getElementById('composer').value;
                var collection = document.getElementById('collection').value;

                if (nama.trim() == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Sheet Music Name Required!',
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
                } else if (composer == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Composer Required!',
                        text: '',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else if (collection == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Collection Required!',
                        text: '',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    cardTitle.textContent = 'Add Detail Sheet Music Data';
                    partiturMasterSection.style.display = 'none';
                    partiturDetailsSection.style.display = 'block';
                }
            });

            backToMasterBtn.addEventListener('click', function() {
                cardTitle.textContent = 'Add Sheet Music Data';
                partiturMasterSection.style.display = 'block';
                partiturDetailsSection.style.display = 'none';
            });

            const addPartiturDetailBtn = document.getElementById('addPartiturDetail');
            let detailCount = 1;

            function addPartiturDetail() {
                detailCount++;
                const detailSection = document.createElement('div');
                detailSection.classList.add('partitur-detail-section');
                detailSection.innerHTML = `<hr>
                <h2>Detail <span class='detail-number'>${detailCount}</span> <button type="button" class="btn btn-danger remove-partitur-detail">Delete</button></h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_detail">Detail Name (*)</label>
                            <input type="text" class="form-control" name="nama_detail[]" placeholder="Enter Detail Name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file_type">File Type (*)</label>
                            <select class="form-control" name="file_type[]">
                                <option value="hardcopy">Hard Copy</option>
                                <option value="softcopy">Soft Copy</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="harga">Price (*)</label>
                            <input type="number" class="form-control" name="harga[]" placeholder="Enter Price" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="minimum_order">Minimum Order (*)</label>
                            <input type="number" class="form-control" name="minimum_order[]" placeholder="Enter Minimum Order" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="preview_audio">Audio Preview</label>
                            <input type="text" class="form-control" name="preview_audio[]" placeholder="Enter Embed Preview Audio">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="preview_video">Video Preview</label>
                            <input type="text" class="form-control" name="preview_video[]" placeholder="Enter Embed Preview Video">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="preview_partitur">Sheet Music Preview</label>
                            <input type="file" class="form-control" name="preview_partitur[]" accept="image/png,image/jpeg">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sheet_music">Original Sheet Music</label>
                            <input type="file" class="form-control"
                                name="sheet_music[]">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nama">Description (*)</label>
                            <textarea name="deskripsidet[]" id="deskripsidet" rows="5" class="form-control"
                                placeholder="Enter Description"></textarea>
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

            addPartiturDetailBtn.addEventListener('click', addPartiturDetail);

            function renumberDetails() {
                const allDetails = document.querySelectorAll('.partitur-detail-section');
                allDetails.forEach((detailSection, index) => {
                    const detailNumberSpan = detailSection.querySelector('.detail-number');
                    if (detailNumberSpan) {
                        detailNumberSpan.textContent = index + 1;
                    }
                });
            }

        });
    </script>
@endsection
