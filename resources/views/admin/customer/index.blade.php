@extends('admin.layouts.master')
@section('title','Customer')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Customer</h1>
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
                    <h3 class="card-title">Customer Data</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="data" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <td width="5%">#</td>
                            <td>Photo</td>
                            <td>Customer Name</td>
                            <td>Gender</td>
                            <td>Email</td>
                            <td>Phone Number</td>
                            <td>Action</td>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td class="w-25 p-3"><img src="{{ file_exists('public/'.$d->photo_profile) && $d->photo_profile ? asset('public/' . $d->photo_profile) : asset('assets/images/favicon.png') }}" class='img-thumbnail' alt="{{ $d->photo_profile }}" > </td>
                                <td>{{$d->name}}</td>
                                <td>{{$d->gender}}</td>
                                <td>{{$d->email}}</td>
                                <td>{{$d->phone_number}}</td>
                                <td>
                                    <a href="{{route('customer.alamat', ['customer' => $d->id])}}" type="button" class="btn btn-primary">Address</a>
                                    <a href="{{route('customer.choir', ['customer' => $d->id])}}" type="button" class="btn btn-info">Choir</a>
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
</section>
@endsection