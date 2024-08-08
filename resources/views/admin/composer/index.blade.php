@extends('admin.layouts.master')
@section('title', 'Composer')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Composer</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('composer.create') }}" class="btn btn-primary float-sm-right ml-2"><i class="fa fa-plus"
                            aria-hidden="true"></i> Add Data</a>
                    <!-- Button trigger modal -->
                    <a href="#" class="btn btn-success float-sm-right ml-2" data-toggle="modal"
                        data-target="#importModal">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Import
                    </a>

                    <a href="{{asset('assets/template/composer_template.xlsx')}}" class="btn btn-secondary float-sm-right ml-2" download><i
                            class="fa-file-excel-o" aria-hidden="true"></i> Download Template</a>
                </div>
            </div>

        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Composer Data</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="data" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <td width="5%">#</td>
                                    <td>Photo</td>
                                    <td>Composer Name</td>
                                    <td>Country</td>
                                    <td>Description</td>
                                    <td>Action</td>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="w-25 p-3"><img src="{{ file_exists('public/'.$d->photo_profile) && $d->photo_profile ? asset('public/' . $d->photo_profile) : asset('assets/images/favicon.png') }}"
                                                    class='img-thumbnail' alt="{{ $d->photo_profile }}"> </td>
                                            <td>{{ $d->name }}</td>
                                            <td>{{ $d->asal_negara }}</td>
                                            <td>{{ $d->profile_desc }}</td>
                                            <td>
                                                <a href="{{ route('composer.edit', ['composer' => $d->id]) }}"
                                                    type="button" class="btn btn-info">Edit</a>
                                                <button type="button" class="btn btn-danger"
                                                    onclick="delData('{{ route('composer.destroy', ['composer' => $d->id]) }}')">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

        <!-- Import Data Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Composer Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('composer.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="fileInput">Select the excel file</label>
                                <input type="file" class="form-control-file" id="fileInput" name="excel" required>
                                <small class="form-text text-muted">Upload files in .xls or .xlsx format</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
@endsection
