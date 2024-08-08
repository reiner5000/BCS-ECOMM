@extends('admin.layouts.master')
@section('title','Add User')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add User</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{route('user.index')}}" class="btn btn-secondary float-sm-right"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                    <h3 class="card-title">Add User Data</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{route('user.store')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">User Name (*)</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter User Name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email (*)</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password (*)</label>
                            <input type="password" class="form-control" min="8" name="password" placeholder="Enter Password" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role (*)</label>
                            <select class="form-control" name="role" required>
                                @foreach($role as $r)
                                    <option value="{{$r->id}}">{{$r->name}}</option>
                                @endforeach
                            </select>
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