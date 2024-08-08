@extends('admin.layouts.master')
@section('title','Add Category')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Category</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{route('category.index')}}" class="btn btn-secondary float-sm-right"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                    <h3 class="card-title">Add Category Data</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{route('category.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">Category Name (*)</label>
                            <input type="text" class="form-control" name="nama" placeholder="Enter Category Name" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Type (*)</label>
                            <select class="form-control" name="type" required>
                                <option>Merchandise</option>
                                <option>Sheet Music</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="detail">Detail</label>
                            <table class="table table-bordered">
                                <thead>
                                    <th>Category Detail Name</th>
                                    <th>Delete</th>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="button" onclick="addRow()" class="btn btn-info">
                            <span class="indicator-label">Add Row</span>
                        </button>
                        &nbsp;
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
<script>
    $(document).ready(function () {
        addRow();
    });

    function addRow(){
        var newRow = '<tr>' +
            '<td><input type="text" class="form-control nama_detail" name="nama_detail[]" placeholder="Enter Category Detail Name"></td>' +
            '<td><button type="button" class="btn btn-danger deleteRow">Delete</button></td>' +
            '</tr>';
        var newElement = $(newRow);
        $("tbody").append(newElement);
    }

    $("tbody").on("click", ".deleteRow", function () {
        $(this).closest("tr").remove();
    });
</script>
@endsection