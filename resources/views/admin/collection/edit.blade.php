@extends('admin.layouts.master')
@section('title','Edit Collection')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Collection</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{route('collection.index')}}" class="btn btn-secondary float-sm-right"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            </div>
        </div>
        
    </div>
    @if(session('success'))
        <br><div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <br><div class="alert alert-danger">
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
                    <h3 class="card-title">Edit Collection Data</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{route('collection.update', ['collection' => $data->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">Collection Name (*)</label>
                            <input type="text" class="form-control" name="nama" placeholder="Enter Collection Name" value="{{$data->name}}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description (*)</label>
                            <textarea class="form-control" name="description" placeholder="Enter Description" rows=3 required>{{$data->short_description}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="foto">Cover (Leave blank if not changed)</label>
                            <input type="file" class="form-control" name="cover" placeholder="Masukkan Foto" accept="image/png,image/jpeg">
                            @if($data->cover)
                                <div class="mt-2">
                                    <img src="{{ asset('public/'.$data->cover) }}" class="img-fluid" style="max-width: 200px;">
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        &nbsp;
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                </form>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
@endsection