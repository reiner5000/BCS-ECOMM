@extends('admin.layouts.master')
@section('title','Edit Category')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Category</h1>
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
                    <h3 class="card-title">Edit Category Data</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{route('category.update', ['category' => $data->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">Category Name (*)</label>
                            <input type="text" class="form-control" name="nama" placeholder="Enter Category Name" value="{{$data->name}}" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Type (*)</label>
                            <select class="form-control" name="type" required>
                                <option @if($data->type == 'Merchandise') selected @endif>Merchandise</option>
                                <option @if($data->type == 'Sheet Music') selected @endif>Sheet Music</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="detail">Detail</label>
                            <table class="table table-bordered">
                                <thead>
                                    <th>ID</th>
                                    <th>Category Detail Name</th>
                                    <th>Delete</th>
                                </thead>
                                <tbody>
                                    @foreach($detail as $d)
                                    <tr>
                                        <td>{{$d->id}}</td>
                                        <td><input type="hidden" name="id_detail[]" value="{{$d->id}}"><input type="text" class="form-control nama_detail" name="nama_detail[]" placeholder="Enter Category Detail Name" value="{{$d->name}}"></td>
                                        <td><button type="button" class="btn btn-danger deleteRowId">Delete</button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <input type="hidden" name="id_delete">
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
    function addRow(){
        var newRow = '<tr>' +
            '<td></td><td><input type="text" class="form-control nama_detail" name="nama_detail[]" placeholder="Enter Category Detail Name"></td>' +
            '<td><button type="button" class="btn btn-danger deleteRow">Delete</button></td>' +
            '</tr>';
        var newElement = $(newRow);
        $("tbody").append(newElement);
    }

    $("tbody").on("click", ".deleteRow", function () {
        $(this).closest("tr").remove();
    });

    $("tbody").on("click", ".deleteRowId", function () {
        var id_detail = $(this).closest("tr").find("input[name='id_detail[]']").val();
        var currentIdDeleteValue = $("input[name='id_delete']").val();
        var updatedIdDeleteValue = currentIdDeleteValue + (currentIdDeleteValue ? ',' : '') + id_detail;
        $("input[name='id_delete']").val(updatedIdDeleteValue);
        $(this).closest("tr").remove();
    });
</script>
@endsection