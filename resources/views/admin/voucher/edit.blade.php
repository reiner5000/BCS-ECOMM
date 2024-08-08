@extends('admin.layouts.master')
@section('title','Edit Voucher')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Voucher</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{route('voucher.index')}}" class="btn btn-secondary float-sm-right"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                    <h3 class="card-title">Edit Voucher Data</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{ route('voucher.update', ['voucher' => $data->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Voucher Name (*)</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Voucher Name" value="{{$data->name}}" required>
                        </div>
                        <div class="form-group">
                            <label for="potongan">Discount (*)</label>
                            <input type="number" class="form-control" name="potongan" placeholder="Enter Discount" value="{{$data->potongan}}" required>
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