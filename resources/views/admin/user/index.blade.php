@extends('admin.layouts.master')
@section('title','User')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>User</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{route('user.create')}}" class="btn btn-primary float-sm-right"><i class="fa fa-plus" aria-hidden="true"></i> Add Data</a>
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
                    <h3 class="card-title">User Data</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="data" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <td width="5%">#</td>
                            <td>User Name</td>
                            <td>Email</td>
                            <td>Role</td>
                            <td>Action</td>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$d->name}}</td>
                                <td>{{$d->email}}</td>
                                <td>{{$d->role->name}}</td>
                                <td>
                                    <a href="{{route('user.edit', ['user' => $d->id])}}" type="button" class="btn btn-info">Edit</a>
                                    <button type="button" class="btn btn-danger" onclick="delData('{{route('user.destroy', ['user' => $d->id])}}')">Delete</button>
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