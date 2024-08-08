@extends('admin.layouts.master')
@section('title','Customer Choir')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{$customer->name}} Customer Choir</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{route('customer.index')}}" class="btn btn-secondary float-sm-right"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                    <h3 class="card-title">{{$customer->name}} Customer Choir Data</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="data" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <td width="5%">#</td>
                            <td>Choir Name</td>
                            <td>Address</td>
                            <td>Conductor</td>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$d->name}} @if($d->is_default==1)<span class="badge bg-danger">MAIN</span>@endif</td>
                                <td>{{$d->address}}</td>
                                <td>{{$d->conductor}}</td>
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
</section>
@endsection