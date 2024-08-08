@extends('admin.layouts.master')
@section('title', 'Edit Sheet Music')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Sheet Music</h1>
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
                            <h3 class="card-title" id="card-title">Edit Sheet Music Data</h3>
                        </div>
                        <form action="{{ route('sheet-music.update', ['sheet_music' => $data->id]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div id="partiturMaster">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nama">Sheet Music Name (*)</label>
                                        <input type="text" class="form-control" name="nama" id="nama"
                                            placeholder="Enter Sheet Music Name" value="{{ $data->name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description (*)</label>
                                        <textarea class="form-control" name="description" id="description" placeholder="Enter Description" rows=3
                                            required>{{ $data->deskripsi }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="composer">Category (*)</label>
                                        <select class="form-control select2" name="category[]" id="category" multiple>
                                            @foreach ($category as $c)
                                            @php
                                                $selectedIds = explode(',', $data->details->first()->category_detail_id);
                                            @endphp
                                            <option value="{{ $c->id }}"
                                                    @if (in_array($c->id, $selectedIds)) selected @endif>
                                                {{ $c->name }} ({{ $c->category->name }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="composer">Composer (*)</label>
                                        <select class="form-control select2" name="composer" id="composer">
                                            <option value="">-- Choose Composer --</option>
                                            @foreach ($composers as $composer)
                                                <option value="{{ $composer->id }}"
                                                    @if ($composer->id == $data->composer_id) selected @endif>{{ $composer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="collection">Collection (*)</label>
                                        <select class="form-control select2" name="collection" id="collection">
                                            <option value="">-- Choose Collection --</option>
                                            @foreach ($collections as $collection)
                                                <option value="{{ $collection->id }}"
                                                    @if ($collection->id == $data->collection_id) selected @endif>
                                                    {{ $collection->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="collection">Choose Image (*Can choose > 1)</label>
                                        <input type="file" class="form-control" name="file_images[]" id="file_image"
                                            multiple accept="image/png,image/jpeg">
                                    </div>
                                    <div class="form-group">
                                        <label>Uploaded Image</label>
                                        <div class="uploaded-images">
                                            @if ($data->file_image)
                                                @php
                                                    $images = explode(',', $data->file_image);
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
                                    </div>
                                    <input type="hidden" id="imagesToDelete" name="imagesToDelete" value="">


                                </div>
                                <div class="card-footer">
                                    <button type="button" id="nextToDetails" class="btn btn-primary">Next</button>
                                </div>
                            </div>

                            <div id="partiturDetails" style="display: none;">
                                <input type="hidden" id="idDelete" name="idDelete">
                                <div class="card-body">
                                    <div id="partiturDetailTemplate">
                                        @php($no = 1)
                                        @foreach ($data->details as $d)
                                            <div class="partitur-detail-section">
                                                @if ($loop->iteration > 1)
                                                    <hr>
                                                @endif
                                                @php($no = $loop->iteration)
                                                <h2>Detail <span class="detail-number">{{ $loop->iteration }}</span>
                                                    <button type="button"
                                                        class="btn btn-danger remove-partitur-detail-id">Delete</button>
                                                </h2>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="nama_detail">Detail Name (*)</label>
                                                            <input type="hidden" class="form-control" name="id_detail[]"
                                                                value="{{ $d->id }}">
                                                            <input type="text" class="form-control"
                                                                name="nama_detail[]" value="{{ $d->name }}"
                                                                placeholder="Enter Detail Name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="file_type">File Type (*)</label>
                                                            <select class="form-control" name="file_type[]">
                                                                <option value="hardcopy"
                                                                    @if ($d->file_type == 'hardcopy') selected @endif>Hard
                                                                    Copy</option>
                                                                <option value="softcopy"
                                                                    @if ($d->file_type == 'softcopy') selected @endif>Soft
                                                                    Copy</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="harga">Price (*)</label>
                                                            <input type="number" class="form-control" name="harga[]"
                                                                value="{{ $d->harga }}" placeholder="Enter Price"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="minimum_order">Minimum Order (*)</label>
                                                            <input type="number" class="form-control"
                                                                name="minimum_order[]" value="{{ $d->minimum_order }}"
                                                                placeholder="Enter Minimum Order" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="preview_audio">Audio Preview @if ($d->preview_audio != null || $d->preview_audio != '')
                                                                    <!-- <button type="button"
                                                                        class="btn btn-info btn-sm btn-audio"
                                                                        data-file="{{ asset('public/' . $d->preview_audio) }}">Look</button>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm remove-audio">Delete</button> -->
                                                                @endif
                                                            </label>
                                                            <input type="text" class="form-control"
                                                                name="preview_audio[]" placeholder="Enter Embed Preview Audio" value="{{ $d->preview_audio}}">
                                                            <input type="hidden" class="form-control"
                                                                name="preview_audio_old[]"
                                                                value="{{ str_replace('uploads/partitur/audio/', '', $d->preview_audio) }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="preview_video">Video Preview @if ($d->preview_video != null || $d->preview_video != '')
                                                                    <!-- <button type="button"
                                                                        class="btn btn-info btn-sm btn-video"
                                                                        data-file="{{ asset('public/' . $d->preview_video) }}">Look</button>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm remove-preview_video">Delete</button> -->
                                                                @endif
                                                            </label>
                                                            <input type="text" class="form-control"
                                                                name="preview_video[]" placeholder="Enter Embed Preview Video" value="{{ $d->preview_video}}">
                                                            <input type="hidden" class="form-control"
                                                                name="preview_video_old[]"
                                                                value="{{ str_replace('uploads/partitur/video/', '', $d->preview_video) }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="preview_partitur">Sheet Music Preview @if ($d->preview_partitur != null || $d->preview_partitur != '')
                                                                    <button type="button"
                                                                        class="btn btn-info btn-sm btn-partitur"
                                                                        data-file="{{ asset('public/' . $d->preview_partitur) }}">Look</button>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm remove-preview_partitur">Delete</button>
                                                                @endif
                                                            </label>
                                                            <input type="file" class="form-control"
                                                                name="preview_partitur[]" accept="image/png,image/jpeg">
                                                            <input type="hidden" class="form-control"
                                                                name="preview_partitur_old[]"
                                                                value="{{ str_replace('uploads/partitur/image/', '', $d->preview_partitur) }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="sheet_music">Original Sheet Music @if ($d->partitur_ori != null || $d->partitur_ori != '')
                                                                    <button type="button"
                                                                        class="btn btn-info btn-sm btn-sheet_music"
                                                                        onclick="window.open('{{ asset('public/' . $d->partitur_ori) }}', '_blank')">Look</button>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm remove-sheet_music">Delete</button>
                                                                @endif
                                                            </label>
                                                            <input type="file" class="form-control"
                                                                name="sheet_music[]">
                                                            <input type="hidden" class="form-control"
                                                                name="sheet_music_old[]"
                                                                value="{{ str_replace('uploads/partitur/image/', '', $d->partitur_ori) }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="nama">Deskripsi (*)</label>
                                                    <textarea name="deskripsidet[]" id="deskripsidet" rows="5" class="form-control"
                                                        placeholder="Masukan Deskripsi">{{ $d->deskripsi }}</textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" id="addPartiturDetail" class="btn btn-info">Add
                                        Detail</button>
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

    <!-- Audio Preview Modal -->
    <div class="modal fade" id="audioPreviewModal" tabindex="-1" role="dialog"
        aria-labelledby="audioPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="audioPreviewModalLabel">Preview Audio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <audio controls id="audioPreview" style="width:100%">
                        <source src="" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Preview Modal -->
    <div class="modal fade" id="videoPreviewModal" tabindex="-1" role="dialog"
        aria-labelledby="videoPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoPreviewModalLabel">Preview Video</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <video controls id="videoPreview" style="width:100%">
                        <source src="" type="">Browser Anda tidak mendukung tag video ini.
                    </video>
                </div>
            </div>
        </div>
    </div>

    <!-- Partitur Preview Modal -->
    <div class="modal fade" id="partiturPreviewModal" tabindex="-1" role="dialog"
        aria-labelledby="partiturPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="partiturPreviewModalLabel">Preview Partitur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img style="width:100%" src="" class='img-thumbnail' id='partiturPreview'>
                </div>
            </div>
        </div>
    </div>

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
            });

            $('.btn-audio').click(function() {
                var file = $(this).data('file');
                $('#audioPreview source').attr('src', file);
                $('#audioPreview')[0].load();
                $('#audioPreviewModal').modal('show');
            });

            function getVideoType(fileName) {
                var extension = fileName.split('.').pop().toLowerCase();
                switch (extension) {
                    case 'mp4':
                        return 'video/mp4';
                    case 'webm':
                        return 'video/webm';
                    case 'ogg':
                        return 'video/ogg';
                    default:
                        return 'video/mp4';
                }
            }

            $('.btn-video').click(function() {
                var videoSrc = $(this).attr('data-file');
                var videoType = getVideoType(videoSrc);

                var videoElement = $('#videoPreview');
                videoElement.find('source').attr('src', videoSrc).attr('type', videoType);
                videoElement[0].load();
                $('#videoPreviewModal').modal('show');
            });

            $('.btn-partitur').click(function() {
                var file = $(this).data('file');
                console.log(file);
                $('#partiturPreview').attr('src', file);
                $('#partiturPreviewModal').modal('show');
            });

            // Remove Audio
            $('.remove-audio').click(function() {
                var that = this;

                Swal.fire({
                    title: "Confirmation",
                    text: "Are you sure want to delete this audio?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                }).then(function(result) {
                    if (result.value) {
                        $(that).closest('.form-group').find('input[name="preview_audio_old[]"]')
                            .val('');
                        $(that).siblings('.btn-audio').hide();
                        $(that).hide();
                    }
                });
            });

            // Remove Video
            $('.remove-preview_video').click(function() {
                var that = this;

                Swal.fire({
                    title: "Confirmation",
                    text: "Are you sure want to delete this video?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                }).then(function(result) {
                    if (result.value) {
                        $(that).closest('.form-group').find('input[name="preview_video_old[]"]')
                            .val('');
                        $(that).siblings('.btn-video').hide();
                        $(that).hide();
                    }
                });
            });

            // Remove Partitur
            $('.remove-preview_partitur').click(function() {
                var that = this;

                Swal.fire({
                    title: "Confirmation",
                    text: "Are you sure want to delete this partitur?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                }).then(function(result) {
                    if (result.value) {
                        $(that).closest('.form-group').find('input[name="preview_partitur_old[]"]')
                            .val('');
                        $(that).siblings('.btn-partitur').hide();
                        $(that).hide();
                    }
                });
            });

            // Remove Sheet Music
            $('.remove-sheet_music').click(function() {
                var that = this;

                Swal.fire({
                    title: "Confirmation",
                    text: "Are you sure want to delete this sheet music?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                }).then(function(result) {
                    if (result.value) {
                        $(that).closest('.form-group').find('input[name="sheet_music_old[]"]')
                            .val('');
                        $(that).siblings('.btn-sheet_music').hide();
                        $(that).hide();
                    }
                });
            });
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
                        title: 'Nama Partitur Required!',
                        text: '',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else if (description.trim() == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Deskripsi Required!',
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
                    cardTitle.textContent = 'Edit Detail Sheet Music Data';
                    partiturMasterSection.style.display = 'none';
                    partiturDetailsSection.style.display = 'block';
                }
            });

            backToMasterBtn.addEventListener('click', function() {
                cardTitle.textContent = 'Edit Sheet Music Data';
                partiturMasterSection.style.display = 'block';
                partiturDetailsSection.style.display = 'none';
            });

            const addPartiturDetailBtn = document.getElementById('addPartiturDetail');
            let detailCount = {{$no}};

            $('.remove-partitur-detail-id').click(function() {
                var currentIdDeleteValue = $('#idDelete').val();
                var idDetail = $(this).closest('.partitur-detail-section').find('input[name="id_detail[]"]')
                    .val();

                if (currentIdDeleteValue) {
                    $('#idDelete').val(currentIdDeleteValue + ',' + idDetail);
                } else {
                    $('#idDelete').val(idDetail);
                }

                $(this).closest('.partitur-detail-section').remove();
                detailCount--;
                renumberDetails();
            });

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
