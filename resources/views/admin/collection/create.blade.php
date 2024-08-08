@extends('admin.layouts.master')
@section('title','Add Collection')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Collection</h1>
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
                    <h3 class="card-title">Add Collection Data</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{route('collection.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">Collection Name (*)</label>
                            <input type="text" class="form-control" name="nama" placeholder="Enter Collection Name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description (*)</label>
                            <textarea class="form-control" name="description" placeholder="Enter Description" rows=3 required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="foto">Cover (*)</label>
                            <input type="file" class="form-control" name="cover" placeholder="Masukkan Foto" accept="image/png,image/jpeg" required>
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